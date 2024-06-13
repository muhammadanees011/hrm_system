<?php

namespace App\Http\Controllers;

use App\Exports\LeaveExport;
use App\Mail\LeaveActionSend;
use App\Models\Employee;
use App\Models\Leave as LocalLeave;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\LeaveEntitlement;
use App\Models\HolidaySetting;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Spatie\GoogleCalendar\Event as GoogleEvent;

class LeaveController extends Controller
{
    public function index()
    {

        if (\Auth::user()->can('Manage Leave')) {
            if (\Auth::user()->type == 'employee') {
                $user     = \Auth::user();
                $employee = Employee::where('user_id', '=', $user->id)->first();
                $leaves   = LocalLeave::where('employee_id', '=', $employee->id)->orderBy('id', 'DESC')->get();
            } else {
                $leaves = LocalLeave::where('created_by', '=', \Auth::user()->creatorId())->with(['employees', 'leaveType'])->orderBy('id', 'DESC')->get();
            }
            
            return view('leave.index', compact('leaves'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Leave')) {
            if (Auth::user()->type == 'employee') {
                $employees = Employee::where('user_id', '=', \Auth::user()->id)->first();
            } else {
                $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            }
            $leavetypes      = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get();
            $leavetypes_days = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get();
            
            $hours = [];

            $startTime = Utility::getValByName('company_start_time');
            $endTime   = Utility::getValByName('company_end_time');
            $start = Carbon::createFromTimeString($startTime);
            $end = Carbon::createFromTimeString($endTime);

            $hours = [];
            for ($hour = $start->copy(); $hour <= $end; $hour->addHour()) {
                $hours[$hour->format('H:i')] = $hour->format('H:i');
            }
            
            return view('leave.create', compact('employees', 'leavetypes', 'leavetypes_days', 'hours'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Leave')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'leave_type_id' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'leave_reason' => 'required',
                    'remark' => 'required',
                    'is_paid_leave' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                
                return redirect()->back()->with('error', $messages->first());
            }

            $setting=HolidaySetting::where('name','book_same_day')->first();
            if($setting->value=='no'){
                $isClash = LocalLeave::where(['start_date' => $request->start_date])->count();
                if($isClash > 0){
                    return redirect()->back()->with('error', __("Selected date is already ocuppied."));
                }
            }

            // CHECK IF USER TRYING TO ADD LEAVE WITH DIFFERENT ROLE
            if(\Auth::user()->type != "employee"){
                $isAlreadyBooked = LocalLeave::where([
                    'employee_id' => $request->employee_id,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'manager_id' => \Auth::user()->id
                ])->where('type', '!=',\Auth::user()->type)->count();
                if($isAlreadyBooked > 0){
                    return redirect()->back()->with('error', __("You can not booked leave from different Role."));
                }
            }

            // $employee = Employee::where('employee_id', '=', \Auth::user()->creatorId())->first();
            $leave_type = LeaveType::find($request->leave_type_id);

            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $endDate->add(new \DateInterval('P1D'));
            // $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;
            $date = Utility::AnnualLeaveCycle();

            if (\Auth::user()->type == 'employee') {
                // Leave day
                $totalFullDayLeaves =  LocalLeave::where('employee_id', '=', $request->employee_id)->where('leave_type_id', $leave_type->id)->where('status', 'Approved')->where('leave_duration', 'full_day')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');

                $totalHalfDayLeaves = LocalLeave::where('employee_id', '=', $request->employee_id)->where('leave_type_id', $leave_type->id)->where('status', 'Approved')->where('leave_duration', 'half_day')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');
                $totalHalfDayLeaves = !empty($totalHalfDayLeaves) ? $totalHalfDayLeaves/2 : 0;

                $leaves_used   = $totalFullDayLeaves + $totalHalfDayLeaves;
                
                $leaves_pending  = LocalLeave::where('employee_id', '=', $request->employee_id)->where('leave_type_id', $leave_type->id)->where('status', 'Pending')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');
            } else {
                // Leave day
                $totalFullDayLeaves = LocalLeave::where('employee_id', '=', $request->employee_id)->where('leave_type_id', $leave_type->id)->where('status', 'Approved')->where('leave_duration', 'full_day')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');

                $totalHalfDayLeaves = LocalLeave::where('employee_id', '=', $request->employee_id)->where('leave_type_id', $leave_type->id)->where('status', 'Approved')->where('leave_duration', 'half_day')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');
                $totalHalfDayLeaves = !empty($totalHalfDayLeaves) ? $totalHalfDayLeaves/2 : 0;

                $leaves_used   = $totalFullDayLeaves + $totalHalfDayLeaves;

                $leaves_pending  = LocalLeave::where('employee_id', '=', $request->employee_id)->where('leave_type_id', $leave_type->id)->where('status', 'Pending')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');
            }

            $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;

            $return = $leave_type->days - $leaves_used;

            if (($total_leave_days > $return && $request->leave_duration !="half_day")) {
                return redirect()->back()->with('error', __('You are not eligible for leave.'));
            }

            if($return =="0.5" && $request->leave_duration=="full_day"){
                return redirect()->back()->with('error', __('You are not eligible for leave.'));
            }

            if (!empty($leaves_pending) && $leaves_pending + $total_leave_days > $return) {
                return redirect()->back()->with('error', __('Multiple leave entry is pending.'));
            }
            if ($leave_type->days >= $total_leave_days) {
                $leave    = new LocalLeave();
                if (\Auth::user()->type == "employee") {
                    $leave->employee_id = $request->employee_id;
                } else {
                    $leave->employee_id = $request->employee_id;
                }
                
                $leave->leave_type_id    = $request->leave_type_id;
                $leave->applied_on       = date('Y-m-d');
                $leave->start_date       = $request->start_date;
                $leave->end_date         = $request->end_date;
                $leave->total_leave_days = $total_leave_days;
                $leave->leave_duration   = $request->leave_duration;
                $leave->start_time       = $request->start_time;
                $leave->end_time         = $request->end_time;
                $leave->duration_hours   = $request->hours;
                $leave->leave_reason     = $request->leave_reason;
                $leave->remark           = $request->remark;
                $leave->is_paid_leave    = $request->is_paid_leave;
                $leave->status           = 'Pending';
                $leave->created_by       = \Auth::user()->creatorId();
                $leave->manager_id       = \Auth::user()->type != 'employee' ? \Auth::user()->id: null;
                $leave->type             = \Auth::user()->type;
                
                $leave->save();
                // Google celander
                if ($request->get('synchronize_type')  == 'google_calender') {

                    $type = 'leave';
                    $request1 = new GoogleEvent();
                    $request1->title = !empty(\Auth::user()->getLeaveType($leave->leave_type_id)) ? \Auth::user()->getLeaveType($leave->leave_type_id)->title : '';
                    $request1->start_date = $request->start_date;
                    $request1->end_date = $request->end_date;

                    Utility::addCalendarData($request1, $type);
                }

                return redirect()->route('leave.index')->with('success', __('Leave  successfully created.'));
            } else {
                return redirect()->back()->with('error', __('Leave type ' . $leave_type->name . ' is provide maximum ' . $leave_type->days . "  days please make sure your selected days is under " . $leave_type->days . ' days.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(LocalLeave $leave)
    {
        return redirect()->route('leave.index');
    }

    public function edit(LocalLeave $leave)
    {
        if (\Auth::user()->can('Edit Leave')) {
            if ($leave->created_by == \Auth::user()->creatorId()) {
                if (Auth::user()->type == 'employee') {
                    $employees = Employee::where('employee_id', '=', \Auth::user()->creatorId())->first();;
                } else {
                    $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                }

                // $employees  = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                // $leavetypes = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('title', 'id');
                $leavetypes      = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get();
                
                $hours = [];

                $startTime = Utility::getValByName('company_start_time');
                $endTime   = Utility::getValByName('company_end_time');
                $start = Carbon::createFromTimeString($startTime);
                $end = Carbon::createFromTimeString($endTime);

                $hours = [];
                for ($hour = $start->copy(); $hour <= $end; $hour->addHour()) {
                    $hours[$hour->format('H:i')] = $hour->format('H:i');
                }
                
                return view('leave.edit', compact('leave', 'employees', 'leavetypes', 'hours'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $leave)
    {
        $leave = LocalLeave::find($leave);
        if (\Auth::user()->can('Edit Leave')) {
            if ($leave->created_by == Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'employee_id' => 'required',
                        'leave_type_id' => 'required',
                        'start_date' => 'required',
                        'end_date' => 'required',
                        'leave_reason' => 'required',
                        'remark' => 'required',
                        'is_paid_leave' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $leave_type = LeaveType::find($request->leave_type_id);
                $employee = Employee::where('employee_id', '=', \Auth::user()->creatorId())->first();;

                $startDate = new \DateTime($request->start_date);
                $endDate = new \DateTime($request->end_date);
                $endDate->add(new \DateInterval('P1D'));
                // $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;

                $date = Utility::AnnualLeaveCycle();

                if (\Auth::user()->type == 'employee') {
                    // Leave day
                    $leaves_used   = LocalLeave::whereNotIn('id', [$leave->id])->where('employee_id', '=', $employee->id)->where('leave_type_id', $leave_type->id)->where('status', 'Approved')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');

                    $leaves_pending  = LocalLeave::whereNotIn('id', [$leave->id])->where('employee_id', '=', $employee->id)->where('leave_type_id', $leave_type->id)->where('status', 'Pending')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');
                } else {
                    // Leave day
                    $leaves_used   = LocalLeave::whereNotIn('id', [$leave->id])->where('employee_id', '=', $request->employee_id)->where('leave_type_id', $leave_type->id)->where('status', 'Approved')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');

                    $leaves_pending  = LocalLeave::whereNotIn('id', [$leave->id])->where('employee_id', '=', $request->employee_id)->where('leave_type_id', $leave_type->id)->where('status', 'Pending')->whereBetween('created_at', [$date['start_date'], $date['end_date']])->sum('total_leave_days');
                }

                $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;

                $return = $leave_type->days - $leaves_used;
                if ($total_leave_days > $return) {
                    return redirect()->back()->with('error', __('You are not eligible for leave.'));
                }

                if (!empty($leaves_pending) && $leaves_pending + $total_leave_days > $return) {
                    return redirect()->back()->with('error', __('Multiple leave entry is pending.'));
                }

                if ($leave_type->days >= $total_leave_days) {
                    $leave->employee_id      = $request->employee_id;
                    $leave->leave_type_id    = $request->leave_type_id;
                    $leave->start_date       = $request->start_date;
                    $leave->end_date         = $request->end_date;
                    $leave->total_leave_days = $total_leave_days;
                    $leave->leave_reason     = $request->leave_reason;
                    $leave->leave_duration = $request->leave_duration;
                    if($leave->leave_duration == "full_day"){
                        $leave->start_time = null;
                        $leave->end_time = null;
                        $leave->duration_hours = null;    
                    }else{
                        $leave->start_time = $request->start_time;
                        $leave->end_time = $request->end_time;
                        $leave->duration_hours = $request->duration_hours;

                    }
                    $leave->remark           = $request->remark;
                    $leave->is_paid_leave    = $request->is_paid_leave;
                    $leave->save();

                    return redirect()->route('leave.index')->with('success', __('Leave successfully updated.'));
                } else {
                    return redirect()->back()->with('error', __('Leave type ' . $leave_type->name . ' is provide maximum ' . $leave_type->days . "  days please make sure your selected days is under " . $leave_type->days . ' days.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(LocalLeave $leave)
    {
        if (\Auth::user()->can('Delete Leave')) {
            if ($leave->created_by == \Auth::user()->creatorId()) {
                $leave->delete();

                return redirect()->route('leave.index')->with('success', __('Leave successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function action($id)
    {
        $leave     = LocalLeave::find($id);
        $employee  = Employee::find($leave->employee_id);
        $leavetype = LeaveType::find($leave->leave_type_id);

        return view('leave.action', compact('employee', 'leavetype', 'leave'));
    }

    public function changeaction(Request $request)
    {
        $leave = LocalLeave::find($request->leave_id);

        $leave->status = $request->status;
        if ($leave->status == 'Approved') {
            $startDate               = new \DateTime($leave->start_date);
            $endDate                 = new \DateTime($leave->end_date);
            $endDate->add(new \DateInterval('P1D'));
            $total_leave_days        = $startDate->diff($endDate)->days;
            $leave->total_leave_days = $total_leave_days;
            $leave->status           = 'Approved';

            $interval = $startDate->diff($endDate);
            $numberOfDays = $interval->days;
            $leaveEntitlement=LeaveEntitlement::where('employee_id',$leave->employee_id)->first();
            if($leaveEntitlement){
            $leaveEntitlement->absence_count=$leaveEntitlement->absence_count+$numberOfDays;
            $leaveEntitlement->remaining_allowance=$leaveEntitlement->remaining_allowance-$numberOfDays;
            $leaveEntitlement->save();
            }
        }

        $leave->save();

        // twilio  
        $setting = Utility::settings(\Auth::user()->creatorId());
        $emp = Employee::find($leave->employee_id);
        if (isset($setting['twilio_leave_approve_notification']) && $setting['twilio_leave_approve_notification'] == 1) {
            //    $msg = __("Your leave has been").' '.$leave->status.'.';

            $uArr = [
                'leave_status' => $leave->status,
            ];

            Utility::send_twilio_msg($emp->phone, 'leave_approve_reject', $uArr);
        }

        $setings = Utility::settings();
        if ($setings['leave_status'] == 1) {
            $employee     = Employee::where('id', $leave->employee_id)->where('created_by', '=', \Auth::user()->creatorId())->first();
            $uArr = [
                'leave_status_name' => $employee->name,
                'leave_status' => $request->status,
                'leave_reason' => $leave->leave_reason,
                'leave_start_date' => $leave->start_date,
                'leave_end_date' => $leave->end_date,
                'total_leave_days' => $leave->total_leave_days,


            ];
            $resp = Utility::sendEmailTemplate('leave_status', [$employee->email], $uArr);
            return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        }

        return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.'));
    }

    public function leave_setting()
    {
        $setting=HolidaySetting::where('name','book_same_day')->first();
        return view('leave.leave_setting',compact('setting'));
    }

    public function leave_clash(Request $request)
    {
        $setting=HolidaySetting::where('name','book_same_day')->first();
        $setting->value=$request->book_same_day;
        $setting->save();
        return redirect()->back()->with('success', __('Leave Setting successfully Updated'));
    }

    public function jsoncount(Request $request)
    {
        $date = Utility::AnnualLeaveCycle();
        $leaveTypes = LeaveType::where('created_by', \Auth::user()->creatorId())->get()->toArray();
        $totalLeavesTaken = Leave::where([
            'status' => 'approved',
            'employee_id' => $request->employee_id 
        ])->whereBetween('created_at', [$date['start_date'],$date['end_date']])->get()->toArray();
    
        $leave_counts = [];

        foreach ($leaveTypes as $key => $value) {
            $typeLeaves  = array_values(array_filter($totalLeavesTaken,function($row) use($value){
                return ($row['leave_type_id']==$value['id']);
            }));
            
            $totalFullDayLeaves = collect($typeLeaves)->where('leave_duration', 'full_day')->sum('total_leave_days');
            $totalHalfDayLeaves = collect($typeLeaves)->where('leave_duration', 'half_day')->sum('total_leave_days');
            $totalHalfDayLeaves = !empty($totalHalfDayLeaves) ? $totalHalfDayLeaves/2 : 0;

            $totalLeaves = $totalFullDayLeaves + $totalHalfDayLeaves;
           
            $leave_counts[] = [
                'id' => $value['id'],
                'days' => $value['days'],
                'title' => $value['title'],
                'total_leave' => $totalLeaves
            ];
        }
        return $leave_counts;
    }

    public function export(Request $request)
    {
        $name = 'Leave' . date('Y-m-d i:h:s');
        $data = Excel::download(new LeaveExport(), $name . '.xlsx');

        return $data;
    }

    public function calender(Request $request)
    {
        $created_by = Auth::user()->creatorId();
        $Meetings = LocalLeave::where('created_by', $created_by)->get();
        $today_date = date('m');
        $current_month_event = LocalLeave::select('id', 'start_date', 'employee_id', 'created_at')->whereRaw('MONTH(start_date)=' . $today_date)->get();

        $arrMeeting = [];

        foreach ($Meetings as $meeting) {
            $arr['id']        = $meeting['id'];
            $arr['employee_id']     = $meeting['employee_id'];
            // $arr['leave_type_id']     = date('Y-m-d', strtotime($meeting['start_date']));
        }

        $leaves = LocalLeave::where('created_by', '=', \Auth::user()->creatorId())->get();
        if (\Auth::user()->type == 'employee') {
            $user     = \Auth::user();
            $employee = Employee::where('user_id', '=', $user->id)->first();
            $leaves   = LocalLeave::where('employee_id', '=', $employee->id)->get();
        } else {
            $leaves = LocalLeave::where('created_by', '=', \Auth::user()->creatorId())->get();
        }

        return view('leave.calender', compact('leaves'));
    }

    public function get_leave_data(Request $request)
    {
        $arrayJson = [];
        if ($request->get('calender_type') == 'google_calender') {
            $type = 'leave';
            $arrayJson =  Utility::getCalendarData($type);
        } else {
            $data = LocalLeave::get();

            foreach ($data as $val) {
                $end_date = date_create($val->end_date);
                date_add($end_date, date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id" => $val->id,
                    "title" => !empty(\Auth::user()->getLeaveType($val->leave_type_id)) ? \Auth::user()->getLeaveType($val->leave_type_id)->title : '',
                    "start" => $val->start_date,
                    "end" => date_format($end_date, "Y-m-d H:i:s"),
                    "className" => $val->color,
                    "textColor" => '#FFF',
                    "allDay" => true,
                    "url" => route('leave.action', $val['id']),
                ];
            }
        }

        return $arrayJson;
    }

    public function teamTimeOff()
    {
        if (\Auth::user()->type == 'employee') {
            $user     = \Auth::user();
            $employee = Employee::where('user_id', '=', $user->id)->first();
            $department_id=$employee->department_id;
            $leaves = LocalLeave::whereHas('employees', function ($query) use ($department_id) {
                $query->where('department_id', $department_id);
            })->get();
        } else {
            $leaves = LocalLeave::where('created_by', '=', \Auth::user()->creatorId())->with(['employees', 'leaveType'])->get();
        }
        $current_month_event=$leaves;
        $arrEvents=[];
        $employees=[];
        $events=$leaves;
        return view('leave.team', compact('arrEvents', 'employees','current_month_event','events'));
    }

    public function teamOff(Request $request)
    {
        if (\Auth::user()->type == 'employee') {
            $user     = \Auth::user();
            $employee = Employee::where('user_id', '=', $user->id)->first();
            $department_id=$employee->department_id;
            $leaves = LocalLeave::whereHas('employees', function ($query) use ($department_id) {
                $query->where('department_id', $department_id);
            })->get();
        } else {
            $leaves = LocalLeave::where('created_by', '=', \Auth::user()->creatorId())->with(['employees', 'leaveType'])->get();
        }
        $current_month_event=$leaves;
        $arrEvents=[];
        $employees=[];


        $arrayJson = [];
        if($request->get('calender_type') == 'google_calender')
        {
            $type ='event';
            $arrayJson =  Utility::getCalendarData($type);
        }
        else
        {
            $data = $leaves;
            
            foreach($data as $val)
            {
                $end_date=date_create($val->end_date);
                date_add($end_date,date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id"=> $val->id,
                    "title" => $val->employees->name,
                    "start" => $val->start_date,
                    "end" => date_format($end_date,"Y-m-d H:i:s"),
                    "className" => 'event-danger',
                    "allDay" => true,
                     "url"=> null,
                    // "url"=> route('trainingevent.edit', $val['id']),

                ];
            }
        }
        
        return $arrayJson;
    }
}
