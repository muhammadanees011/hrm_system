<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceImport;
use App\Models\AttendanceEmployee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FlexiTime;
use App\Models\Leave;
use App\Models\IpRestrict;
use App\Models\User;
use App\Models\Utility;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceEmployeeController extends Controller
{
    public function index(Request $request)
    {
        if(\Auth::user()->can('Manage Attendance'))
        {
            $employees =  Employee::all();
            $employee_count = $employees->count();
            $dates = [];
            $on_time_attendances = [];
            $late_time_attendances = [];
            $leave_count = [];
            $absentCount = [];
            
            
            

            if($request->date != null){
        
                $attendances = AttendanceEmployee::where('date', $request->date)->get();
                $on_time_attendances = AttendanceEmployee::where('date', $request->date)->where('late', '00:00:00')->get();
                $late_time_attendances = AttendanceEmployee::where('date', $request->date)->where('late', '!=', '00:00:00')->get();
                $leaves = Leave::where('start_date', '>=',$date)->where('end_date','<=', $date)->where('status','Approved')->get();
                $leave_count[] = $leaves->count();
                $absentCount[] = $employee_count - $attendances->count();
                $onTimeattendancesCount[] = $on_time_attendances->count();
                $lateTimeattendancesCount[] = $late_time_attendances->count();
                $attendancesCount[] = $attendances->count();
                $labels[] = $request->date;
            }elseif($request->type == "monthly" && $request->month != null) {
                $monthYear = $request->month; // Format: YYYY-MM
                list($year, $month) = explode('-', $monthYear);

                // Get the number of days in the specified month and year
                $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

                // Create an array to store the dates of the month
                $datesOfMonth = [];
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    
                    $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                    $attendances = AttendanceEmployee::where('date', $date)->get();
                    $on_time_attendances = AttendanceEmployee::where('date', $date)->where('late', '00:00:00')->get();
                    $late_time_attendances = AttendanceEmployee::where('date', $date)->where('late', '!=', '00:00:00')->get();
                    $leaves = Leave::where('start_date', '>=',$date)->where('end_date','<=', $date)->where('status','Approved')->get();
                    $leave_count[] = $leaves->count();
                    $attendancesCount[] = $attendances->count();
                    $absentCount[] = $employee_count - $attendances->count();
                    $onTimeattendancesCount[] = $on_time_attendances->count();
                    $lateTimeattendancesCount[] = $late_time_attendances->count();
                    $labels[] = $date;
                    $datesOfMonth[] = $date;
                }
            }else{
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i)->toDateString();
                    $attendances = AttendanceEmployee::where('date', $date)->get();
                    $on_time_attendances = AttendanceEmployee::where('date', $date)->where('late', '00:00:00')->get();
                    $late_time_attendances = AttendanceEmployee::where('date', $date)->where('late', '!=', '00:00:00')->get();
                    $leaves = Leave::where('start_date', '>=',$date)->where('end_date','<=', $date)->where('status','Approved')->get();
                    $leave_count[] = $leaves->count();
                    $onTimeattendancesCount[] = $on_time_attendances->count();
                    $lateTimeattendancesCount[] = $late_time_attendances->count();
                    $absentCount[] = $employee_count - $attendances->count();
                    $attendancesCount[] = $attendances->count();
                    $labels[] = $date;
                }
            }
            $attendanceData = [
                [
                    'name' => 'On Time',
                    'data' => $onTimeattendancesCount
                ],
                [
                    'name' => 'Late',
                    'data' => $lateTimeattendancesCount
                ],
                [
                    'name' => 'Absent',
                    'data' => $absentCount
                ],
                [
                    'name' => 'Leaves',
                    'data' => $leave_count
                ],
    
            ];
            
            $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branch->prepend('Select Branch', '');

            $department = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $department->prepend('All', '');

            if(\Auth::user()->type == 'employee')
            {

                $emp = !empty(\Auth::user()->employee) ? \Auth::user()->employee->id : 0;

                $attendanceEmployee = AttendanceEmployee::where('employee_id', $emp);

                if($request->type == 'monthly' && !empty($request->month))
                {
                    $month = date('m', strtotime($request->month));
                    $year  = date('Y', strtotime($request->month));

                    $start_date = date($year . '-' . $month . '-01');
                    $end_date = date('Y-m-t', strtotime('01-'.$month.'-'.$year));

                    // old date
                    // $end_date   = date($year . '-' . $month . '-t');

                    $attendanceEmployee->whereBetween(
                        'date', [
                                  $start_date,
                                  $end_date,
                              ]
                    );
                }
                elseif($request->type == 'daily' && !empty($request->date))
                {
                    $attendanceEmployee->where('date', $request->date);
                }
                else
                {
                    $month      = date('m');
                    $year       = date('Y');
                    $start_date = date($year . '-' . $month . '-01');
                    $end_date = date('Y-m-t', strtotime('01-'.$month.'-'.$year));
                    
                    // old date
                    // $end_date   = date($year . '-' . $month . '-t');

                    $attendanceEmployee->whereBetween(
                        'date', [
                                  $start_date,
                                  $end_date,
                              ]
                    );
                }
                $attendanceEmployee = $attendanceEmployee->get();

            }
            else
            {
                $employee = Employee::select('id')->where('created_by', \Auth::user()->creatorId());
                if(!empty($request->branch))
                {
                    $employee->where('branch_id', $request->branch);
                }

                if(!empty($request->department))
                {
                    $employee->where('department_id', $request->department);
                }

                $employee = $employee->get()->pluck('id');

                $attendanceEmployee = AttendanceEmployee::whereIn('employee_id', $employee);

                if($request->type == 'monthly' && !empty($request->month))
                {
                    $month = date('m', strtotime($request->month));
                    $year  = date('Y', strtotime($request->month));

                    $start_date = date($year . '-' . $month . '-01');
                    $end_date = date('Y-m-t', strtotime('01-'.$month.'-'.$year));
                    
                    // old date
                    // $end_date   = date($year . '-' . $month . '-t');

                    $attendanceEmployee->whereBetween(
                        'date', [
                                  $start_date,
                                  $end_date,
                              ]
                    );
                }
                elseif($request->type == 'daily' && !empty($request->date))
                {
                    $attendanceEmployee->where('date', $request->date);
                }
                else
                {
                    $month      = date('m');
                    $year       = date('Y');
                    $start_date = date($year . '-' . $month . '-01');
                    $end_date = date('Y-m-t', strtotime('01-'.$month.'-'.$year));
                    
                    // olda date
                    // $end_date   = date($year . '-' . $month . '-t');

                    $attendanceEmployee->whereBetween(
                        'date', [
                                  $start_date,
                                  $end_date,
                              ]
                    );
                }


                $attendanceEmployee = $attendanceEmployee->get();

            }
            $todayDate = date('Y-m-d');
            if(Carbon::parse($todayDate)->isWeekend()){
                $attendanceOverview = [0, 0, 0, 0, 0];
            }else{
                $todayAttendance = AttendanceEmployee::where('date', $todayDate)->where('created_by', \Auth::user()->creatorId())->get()->toArray();
                $totalPresents = $late = $totalFlexiTime = 0;
                foreach ($todayAttendance as $key => $value) {
                    if($value['status']=="Present" && $value['late']=="00:00:00" && empty($value['requested_time'])){
                        $totalPresents += 1;
                    }

                    if($value['status']=="Present" && $value['late'] != "00:00:00" && empty($value['requested_time'])){
                        $late += 1;
                    }

                    if($value['status']=="Present" && !empty($value['requested_time'])){
                        $totalFlexiTime += 1;
                    }
                }

                $onLeaves = Leave::where('start_date', '>=', $todayDate)->Where('end_date','<=', $todayDate)->where('status','Approved')->count();
                $totalEmployees = Employee::where('created_by', \Auth::user()->creatorId())->count();

                $absentEmployees = $totalEmployees - (count($todayAttendance) + $onLeaves);
                $attendanceOverview = [$totalPresents, $absentEmployees, $onLeaves,$late,$totalFlexiTime];
            }
            return view('attendance.index', compact('attendanceEmployee', 'labels', 'branch', 'department','attendanceData','attendanceOverview'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('Create Attendance'))
        {
            $employees = User::where('created_by', '=', Auth::user()->creatorId())->where('type', '=', "employee")->get()->pluck('name', 'id');

            return view('attendance.create', compact('employees'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Attendance'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   'date' => 'required',
                                   'clock_in' => 'required',
                                   'clock_out' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $startTime  = Utility::getValByName('company_start_time');
            $endTime    = Utility::getValByName('company_end_time');
            $attendance = AttendanceEmployee::where('employee_id', '=', $request->employee_id)->where('date', '=', $request->date)->where('clock_out', '=', '00:00:00')->get()->toArray();
            if($attendance)
            {
                return redirect()->route('attendanceemployee.index')->with('error', __('Employee Attendance Already Created.'));
            }
            else
            {
                $date = date("Y-m-d");

                $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);

                $hours = floor($totalLateSeconds / 3600);
                $mins  = floor($totalLateSeconds / 60 % 60);
                $secs  = floor($totalLateSeconds % 60);
                $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                //early Leaving
                $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
                $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                $secs                     = floor($totalEarlyLeavingSeconds % 60);
                $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);


                if(strtotime($request->clock_out) > strtotime($date . $endTime))
                {
                    //Overtime
                    $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                    $hours                = floor($totalOvertimeSeconds / 3600);
                    $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                    $secs                 = floor($totalOvertimeSeconds % 60);
                    $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                }
                else
                {
                    $overtime = '00:00:00';
                }

                $employeeAttendance                = new AttendanceEmployee();
                $employeeAttendance->employee_id   = $request->employee_id;
                $employeeAttendance->date          = $request->date;
                $employeeAttendance->status        = 'Present';
                $employeeAttendance->clock_in      = $request->clock_in . ':00';
                $employeeAttendance->clock_out     = $request->clock_out . ':00';
                $employeeAttendance->late          = $late;
                $employeeAttendance->early_leaving = $earlyLeaving;
                $employeeAttendance->overtime      = $overtime;
                $employeeAttendance->total_rest    = '00:00:00';
                $employeeAttendance->created_by    = \Auth::user()->creatorId();
                $employeeAttendance->save();

                return redirect()->route('attendanceemployee.index')->with('success', __('Employee attendance successfully created.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function show(Request $request)
    {
        // return redirect()->back();
        return redirect()->route('attendanceemployee.index');
    }

    public function edit($id)
    {
        if(\Auth::user()->can('Edit Attendance'))
        {
            $attendanceEmployee = AttendanceEmployee::where('id', $id)->first();
            $employees          = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('attendance.edit', compact('attendanceEmployee', 'employees'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    // public function update(Request $request, $id)
    // {
    //     if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr') {
    //         $employeeId      = AttendanceEmployee::where('employee_id', $request->employee_id)->first();
    //         $check = AttendanceEmployee::where('employee_id', '=', $request->employee_id)->where('date', $request->date)->first();
            
    //         $startTime = Utility::getValByName('company_start_time');
    //         $endTime   = Utility::getValByName('company_end_time');
            
    //         $clockIn = $request->clock_in;
    //         $clockOut = $request->clock_out;
            
    //         if ($clockIn) {
    //             $status = "present";
    //         } else {
    //             $status = "leave";
    //         }
            
    //         $totalLateSeconds = strtotime($clockIn) - strtotime($startTime);

    //         $hours = floor($totalLateSeconds / 3600);
    //         $mins  = floor($totalLateSeconds / 60 % 60);
    //         $secs  = floor($totalLateSeconds % 60);
    //         $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

    //         $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($clockOut);
    //         $hours                    = floor($totalEarlyLeavingSeconds / 3600);
    //         $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
    //         $secs                     = floor($totalEarlyLeavingSeconds % 60);
    //         $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

    //         if (strtotime($clockOut) > strtotime($endTime)) {
    //             //Overtime
    //             $totalOvertimeSeconds = strtotime($clockOut) - strtotime($endTime);
    //             $hours                = floor($totalOvertimeSeconds / 3600);
    //             $mins                 = floor($totalOvertimeSeconds / 60 % 60);
    //             $secs                 = floor($totalOvertimeSeconds % 60);
    //             $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    //         } else {
    //             $overtime = '00:00:00';
    //         }
    //         if ($check->date == date('Y-m-d')) {
    //             $check->update([
    //                 'late' => $late,
    //                 'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
    //                 'overtime' => $overtime,
    //                 'clock_in' => $clockIn,
    //                 'clock_out' => $clockOut
    //             ]);

    //             return redirect()->route('attendanceemployee.index')->with('success', __('Employee attendance successfully updated.'));
    //         } else {
    //             return redirect()->route('attendanceemployee.index')->with('error', __('You can only update current day attendance'));
    //         }
    //     }

    //     $employeeId      = !empty(\Auth::user()->employee) ? \Auth::user()->employee->id : 0;
    //     $todayAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)->where('date', date('Y-m-d'))->first();
    //     if(!empty($todayAttendance) && $todayAttendance->clock_out == '00:00:00')
    //     {
    //         $startTime = Utility::getValByName('company_start_time');
    //         $endTime   = Utility::getValByName('company_end_time');
    //         if(Auth::user()->type == 'employee')
    //         {

    //             $date = date("Y-m-d");
    //             $time = date("H:i:s");

    //             //early Leaving
    //             $totalEarlyLeavingSeconds = strtotime($date . $endTime) - time();
    //             $hours                    = floor($totalEarlyLeavingSeconds / 3600);
    //             $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
    //             $secs                     = floor($totalEarlyLeavingSeconds % 60);
    //             $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

    //             if(time() > strtotime($date . $endTime))
    //             {
    //                 //Overtime
    //                 $totalOvertimeSeconds = time() - strtotime($date . $endTime);
    //                 $hours                = floor($totalOvertimeSeconds / 3600);
    //                 $mins                 = floor($totalOvertimeSeconds / 60 % 60);
    //                 $secs                 = floor($totalOvertimeSeconds % 60);
    //                 $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    //             }
    //             else
    //             {
    //                 $overtime = '00:00:00';
    //             }

    //             $attendanceEmployee                = AttendanceEmployee::find($id);
    //             $attendanceEmployee->clock_out     = $time;
    //             $attendanceEmployee->early_leaving = $earlyLeaving;
    //             $attendanceEmployee->overtime      = $overtime;
    //             $attendanceEmployee->save();

    //             return redirect()->route('home')->with('success', __('Employee successfully clock Out.'));
    //         }
    //         else
    //         {
    //             $date = date("Y-m-d");
    //             //late
    //             $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);

    //             $hours = floor($totalLateSeconds / 3600);
    //             $mins  = floor($totalLateSeconds / 60 % 60);
    //             $secs  = floor($totalLateSeconds % 60);
    //             $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

    //             //early Leaving
    //             $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
    //             $hours                    = floor($totalEarlyLeavingSeconds / 3600);
    //             $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
    //             $secs                     = floor($totalEarlyLeavingSeconds % 60);
    //             $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);


    //             if(strtotime($request->clock_out) > strtotime($date . $endTime))
    //             {
    //                 //Overtime
    //                 $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
    //                 $hours                = floor($totalOvertimeSeconds / 3600);
    //                 $mins                 = floor($totalOvertimeSeconds / 60 % 60);
    //                 $secs                 = floor($totalOvertimeSeconds % 60);
    //                 $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    //             }
    //             else
    //             {
    //                 $overtime = '00:00:00';
    //             }

    //             $attendanceEmployee                = AttendanceEmployee::find($id);
    //             $attendanceEmployee->employee_id   = $request->employee_id;
    //             $attendanceEmployee->date          = $request->date;
    //             $attendanceEmployee->clock_in      = $request->clock_in;
    //             $attendanceEmployee->clock_out     = $request->clock_out;
    //             $attendanceEmployee->late          = $late;
    //             $attendanceEmployee->early_leaving = $earlyLeaving;
    //             $attendanceEmployee->overtime      = $overtime;
    //             $attendanceEmployee->total_rest    = '00:00:00';

    //             $attendanceEmployee->save();

    //             return redirect()->route('attendanceemployee.index')->with('success', __('Employee attendance successfully updated.'));
    //         }
    //     }
    //     else
    //     {
    //         return redirect()->back()->with('error', __('Employee are not allow multiple time clock in & clock for every day.'));
    //     }
    // }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr') {
            $employeeId      = AttendanceEmployee::where('employee_id', $request->employee_id)->first();
            $check = AttendanceEmployee::where('id', '=', $id)->where('employee_id', '=', $request->employee_id)->where('date', $request->date)->first();

            $startTime = Utility::getValByName('company_start_time');
            $endTime   = Utility::getValByName('company_end_time');

            $clockIn = $request->clock_in;
            $clockOut = $request->clock_out;

            if ($clockIn) {
                $status = "present";
            } else {
                $status = "leave";
            }

            $totalLateSeconds = strtotime($clockIn) - strtotime($startTime);

            $hours = floor($totalLateSeconds / 3600);
            $mins  = floor($totalLateSeconds / 60 % 60);
            $secs  = floor($totalLateSeconds % 60);
            $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

            $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($clockOut);
            $hours                    = floor($totalEarlyLeavingSeconds / 3600);
            $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
            $secs                     = floor($totalEarlyLeavingSeconds % 60);
            $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

            if (strtotime($clockOut) > strtotime($endTime)) {
                //Overtime
                $totalOvertimeSeconds = strtotime($clockOut) - strtotime($endTime);
                $hours                = floor($totalOvertimeSeconds / 3600);
                $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                $secs                 = floor($totalOvertimeSeconds % 60);
                $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
            } else {
                $overtime = '00:00:00';
            }
            if ($check->date == date('Y-m-d')) {
                $check->update([
                    'late' => $late,
                    'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
                    'overtime' => $overtime,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut
                ]);

                return redirect()->route('attendanceemployee.index')->with('success', __('Employee attendance successfully updated.'));
            } else {
                return redirect()->route('attendanceemployee.index')->with('error', __('You can only update current day attendance.'));
            }
        }

        $employeeId      = !empty(\Auth::user()->employee) ? \Auth::user()->employee->id : 0;
        $todayAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)->where('date', date('Y-m-d'))->first();

        $startTime = Utility::getValByName('company_start_time');
        $endTime   = Utility::getValByName('company_end_time');
        if (Auth::user()->type == 'employee') {

            $date = date("Y-m-d");

            //early Leaving
            $totalEarlyLeavingSeconds = strtotime($date . $endTime) - time();
            $hours                    = floor($totalEarlyLeavingSeconds / 3600);
            $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
            $secs                     = floor($totalEarlyLeavingSeconds % 60);
            $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

            $flexiTime = FlexiTime::where('status','approved')->where('start_date', '>=', $date)->Where('end_date','<=', $date)->first();

            if(!empty($flexiTime)){
                $time = date('H:i:s', strtotime('+'.$flexiTime->hours.' hours'));
                $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($time);
                $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                $secs                     = floor($totalEarlyLeavingSeconds % 60);
                $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

            }else {
                $time = date("H:i:s");
            }

            if (time() > strtotime($date . $endTime)) {
                //Overtime
                $totalOvertimeSeconds = time() - strtotime($date . $endTime);
                $hours                = floor($totalOvertimeSeconds / 3600);
                $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                $secs                 = floor($totalOvertimeSeconds % 60);
                $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
            } else {
                $overtime = '00:00:00';
            }

            $attendanceEmployee['clock_out']     = $time;
            $attendanceEmployee['early_leaving'] = $earlyLeaving;
            $attendanceEmployee['overtime']      = $overtime;



            if (!empty($request->date)) {
                $attendanceEmployee['date']       =  $request->date;
            }

            // reset clockout time if user clockin and clockout multiple times in a day
            $todayAttendanceArray = AttendanceEmployee::where([
                'employee_id' => $employeeId,
                'date' => $date
            ])->where('id', '!=', $id)->get()->toArray();
            
            if(!empty($todayAttendanceArray)){
                foreach ($todayAttendanceArray as $key => $value) {
                    $newClockOutTime = strtotime($value['clock_out'].' -'.$flexiTime->hours.' hours');
                    // echo 
                    AttendanceEmployee::where('id', $value['id'])->update(['clock_out' => date('H:i:s', $newClockOutTime)]);
                }
            }
            AttendanceEmployee::where('id', $id)->update($attendanceEmployee);

            return redirect()->route('dashboard')->with('success', __('Employee successfully clock Out.'));
        } else {
            $date = date("Y-m-d");
            $clockout_time = date("H:i:s");
            //late
            $totalLateSeconds = strtotime($clockout_time) - strtotime($date . $startTime);

            $hours            = abs(floor($totalLateSeconds / 3600));
            $mins             = abs(floor($totalLateSeconds / 60 % 60));
            $secs             = abs(floor($totalLateSeconds % 60));

            $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

            //early Leaving
            $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($clockout_time);
            $hours                    = floor($totalEarlyLeavingSeconds / 3600);
            $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
            $secs                     = floor($totalEarlyLeavingSeconds % 60);
            $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);


            if (strtotime($clockout_time) > strtotime($date . $endTime)) {
                //Overtime
                $totalOvertimeSeconds = strtotime($clockout_time) - strtotime($date . $endTime);
                $hours                = floor($totalOvertimeSeconds / 3600);
                $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                $secs                 = floor($totalOvertimeSeconds % 60);
                $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
            } else {
                $overtime = '00:00:00';
            }

            $attendanceEmployee                = AttendanceEmployee::find($id);
            $attendanceEmployee->clock_out     = $clockout_time;
            $attendanceEmployee->late          = $late;
            $attendanceEmployee->early_leaving = $earlyLeaving;
            $attendanceEmployee->overtime      = $overtime;
            $attendanceEmployee->total_rest    = '00:00:00';

            $attendanceEmployee->save();

            return redirect()->back()->with('success', __('Employee attendance successfully updated.'));
        }
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('Delete Attendance'))
        {
            $attendance = AttendanceEmployee::where('id', $id)->first();

            $attendance->delete();

            return redirect()->route('attendanceemployee.index')->with('success', __('Attendance successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    // public function attendance(Request $request)
    // {
    //     $settings = Utility::settings();

    //     if($settings['ip_restrict'] == 'on')
    //     {
    //         $userIp = request()->ip();
    //         $ip     = IpRestrict::where('created_by', \Auth::user()->creatorId())->whereIn('ip', [$userIp])->first();
    //         if(!empty($ip))
    //         {
    //             return redirect()->back()->with('error', __('this ip is not allowed to clock in & clock out.'));
    //         }
    //     }

    //     $employeeId      = !empty(\Auth::user()->employee) ? \Auth::user()->employee->id : 0;
    //     $todayAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)->where('date', date('Y-m-d'))->first();
    //     if(empty($todayAttendance))
    //     {

    //         $startTime = Utility::getValByName('company_start_time');
    //         $endTime   = Utility::getValByName('company_end_time');

    //         $attendance = AttendanceEmployee::orderBy('id', 'desc')->where('employee_id', '=', $employeeId)->where('clock_out', '=', '00:00:00')->first();

    //         if($attendance != null)
    //         {
    //             $attendance            = AttendanceEmployee::find($attendance->id);
    //             $attendance->clock_out = $endTime;
    //             $attendance->save();
    //         }

    //         $date = date("Y-m-d");
    //         $time = date("H:i:s");

    //         //late
    //         $totalLateSeconds = time() - strtotime($date . $startTime);
    //         $hours            = floor($totalLateSeconds / 3600);
    //         $mins             = floor($totalLateSeconds / 60 % 60);
    //         $secs             = floor($totalLateSeconds % 60);
    //         $late             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

    //         $checkDb = AttendanceEmployee::where('employee_id', '=', \Auth::user()->id)->get()->toArray();


    //         if(empty($checkDb))
    //         {
    //             $employeeAttendance                = new AttendanceEmployee();
    //             $employeeAttendance->employee_id   = $employeeId;
    //             $employeeAttendance->date          = $date;
    //             $employeeAttendance->status        = 'Present';
    //             $employeeAttendance->clock_in      = $time;
    //             $employeeAttendance->clock_out     = '00:00:00';
    //             $employeeAttendance->late          = $late;
    //             $employeeAttendance->early_leaving = '00:00:00';
    //             $employeeAttendance->overtime      = '00:00:00';
    //             $employeeAttendance->total_rest    = '00:00:00';
    //             $employeeAttendance->created_by    = \Auth::user()->id;

    //             $employeeAttendance->save();

    //             return redirect()->route('home')->with('success', __('Employee Successfully Clock In.'));
    //         }
    //         foreach($checkDb as $check)
    //         {


    //             $employeeAttendance                = new AttendanceEmployee();
    //             $employeeAttendance->employee_id   = $employeeId;
    //             $employeeAttendance->date          = $date;
    //             $employeeAttendance->status        = 'Present';
    //             $employeeAttendance->clock_in      = $time;
    //             $employeeAttendance->clock_out     = '00:00:00';
    //             $employeeAttendance->late          = $late;
    //             $employeeAttendance->early_leaving = '00:00:00';
    //             $employeeAttendance->overtime      = '00:00:00';
    //             $employeeAttendance->total_rest    = '00:00:00';
    //             $employeeAttendance->created_by    = \Auth::user()->id;

    //             $employeeAttendance->save();

    //             return redirect()->route('home')->with('success', __('Employee Successfully Clock In.'));

    //         }
    //     }
    //     else
    //     {
    //         return redirect()->back()->with('error', __('Employee are not allow multiple time clock in & clock for every day.'));
    //     }
    // }

    public function attendance(Request $request)
    {
        $settings = Utility::settings();

        if ($settings['ip_restrict'] == 'on') {
            $userIp = request()->ip();
            $ip     = IpRestrict::where('created_by', \Auth::user()->creatorId())->whereIn('ip', [$userIp])->first();
            if (!empty($ip)) {
                return redirect()->back()->with('error', __('This ip is not allowed to clock in & clock out.'));
            }
        }

        $employeeId = !empty(\Auth::user()->employee) ? \Auth::user()->employee->id : 0;

        $startTime = Utility::getValByName('company_start_time');
        $endTime = Utility::getValByName('company_end_time');

        // Find the last clocked out entry for the employee
        $lastClockOutEntry = AttendanceEmployee::orderBy('id', 'desc')
            ->where('employee_id', '=', $employeeId)
            ->where('clock_out', '!=', '00:00:00')
            ->where('date', '=', date('Y-m-d'))
            ->first();

        $date = date("Y-m-d");
        $time = date("H:i:s");

        if ($lastClockOutEntry != null) {
            // Calculate late based on the difference between the last clock-out time and the current clock-in time
            $lastClockOutTime = $lastClockOutEntry->clock_out;
            $actualClockInTime = $date . ' ' . $time;

            $totalLateSeconds = strtotime($actualClockInTime) - strtotime($date . ' ' . $lastClockOutTime);

            // Ensure late time is non-negative
            $totalLateSeconds = max($totalLateSeconds, 0);

            $hours = abs(floor($totalLateSeconds / 3600));
            $mins = abs(floor($totalLateSeconds / 60 % 60));
            $secs = abs(floor($totalLateSeconds % 60));
            $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        } else {
            // If there is no previous clock-out entry, assume no lateness
            $expectedStartTime = $date . ' ' . $startTime;
            $actualClockInTime = $date . ' ' . $time;

            $totalLateSeconds = strtotime($actualClockInTime) - strtotime($expectedStartTime);

            // Ensure late time is non-negative
            $totalLateSeconds = max($totalLateSeconds, 0);

            $hours = abs(floor($totalLateSeconds / 3600));
            $mins = abs(floor($totalLateSeconds / 60 % 60));
            $secs = abs(floor($totalLateSeconds % 60));
            $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        }

        $checkDb = AttendanceEmployee::where('employee_id', '=', \Auth::user()->id)->get()->toArray();
        $flexiTime = FlexiTime::where('status','approved')->where('start_date', '>=', $date)->Where('end_date','<=', $date)->first();
        $requestedTime  = !empty($flexiTime) ? \Auth::user()->timeFormat($flexiTime->start_time).' - '.\Auth::user()->timeFormat($flexiTime->end_time): "";

        if (empty($checkDb)) {
            $employeeAttendance                = new AttendanceEmployee();
            $employeeAttendance->employee_id   = $employeeId;
            $employeeAttendance->date          = $date;
            $employeeAttendance->status        = 'Present';
            $employeeAttendance->clock_in      = $time;
            $employeeAttendance->clock_out     = '00:00:00';
            $employeeAttendance->late          = $late;
            $employeeAttendance->early_leaving = '00:00:00';
            $employeeAttendance->overtime      = '00:00:00';
            $employeeAttendance->total_rest    = '00:00:00';
            $employeeAttendance->created_by    = \Auth::user()->creatorId();
            $employeeAttendance->requested_time = $requestedTime;

            $employeeAttendance->save();

            return redirect()->back()->with('success', __('Employee Successfully Clock In.'));
        }
        foreach ($checkDb as $check) {

            $employeeAttendance                = new AttendanceEmployee();
            $employeeAttendance->employee_id   = $employeeId;
            $employeeAttendance->date          = $date;
            $employeeAttendance->status        = 'Present';
            $employeeAttendance->clock_in      = $time;
            $employeeAttendance->clock_out     = '00:00:00';
            $employeeAttendance->late          = $late;
            $employeeAttendance->early_leaving = '00:00:00';
            $employeeAttendance->overtime      = '00:00:00';
            $employeeAttendance->total_rest    = '00:00:00';
            $employeeAttendance->created_by    = \Auth::user()->creatorId();

            $employeeAttendance->save();

            return redirect()->back()->with('success', __('Employee Successfully Clock In.'));
        }
    }

    public function bulkAttendance(Request $request)
    {
        if(\Auth::user()->can('Create Attendance'))
        {

            $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branch->prepend('Select Branch', '');

            $department = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $department->prepend('Select Department', '');

            $employees = [];
            if(!empty($request->branch) && !empty($request->department))
            {
                $employees = Employee::where('created_by', \Auth::user()->creatorId())->where('branch_id', $request->branch)->where('department_id', $request->department)->get();


            }


            return view('attendance.bulk', compact('employees', 'branch', 'department'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bulkAttendanceData(Request $request)
    {

        if(\Auth::user()->can('Create Attendance'))
        {
            if(!empty($request->branch) && !empty($request->department))
            {
                $startTime = Utility::getValByName('company_start_time');
                $endTime   = Utility::getValByName('company_end_time');
                $date      = $request->date;

                $employees = $request->employee_id;
                $atte      = [];
                foreach($employees as $employee)
                {
                    $present = 'present-' . $employee;
                    $in      = 'in-' . $employee;
                    $out     = 'out-' . $employee;
                    $atte[]  = $present;
                    if($request->$present == 'on')
                    {

                        $in  = date("H:i:s", strtotime($request->$in));
                        $out = date("H:i:s", strtotime($request->$out));

                        $totalLateSeconds = strtotime($in) - strtotime($startTime);

                        $hours = floor($totalLateSeconds / 3600);
                        $mins  = floor($totalLateSeconds / 60 % 60);
                        $secs  = floor($totalLateSeconds % 60);
                        $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        //early Leaving
                        $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($out);
                        $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                        $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                        $secs                     = floor($totalEarlyLeavingSeconds % 60);
                        $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);


                        if(strtotime($out) > strtotime($endTime))
                        {
                            //Overtime
                            $totalOvertimeSeconds = strtotime($out) - strtotime($endTime);
                            $hours                = floor($totalOvertimeSeconds / 3600);
                            $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                            $secs                 = floor($totalOvertimeSeconds % 60);
                            $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        }
                        else
                        {
                            $overtime = '00:00:00';
                        }


                        $attendance = AttendanceEmployee::where('employee_id', '=', $employee)->where('date', '=', $request->date)->first();

                        if(!empty($attendance))
                        {
                            $employeeAttendance = $attendance;
                        }
                        else
                        {
                            $employeeAttendance              = new AttendanceEmployee();
                            $employeeAttendance->employee_id = $employee;
                            $employeeAttendance->created_by  = \Auth::user()->creatorId();
                        }


                        $employeeAttendance->date          = $request->date;
                        $employeeAttendance->status        = 'Present';
                        $employeeAttendance->clock_in      = $in;
                        $employeeAttendance->clock_out     = $out;
                        $employeeAttendance->late          = $late;
                        $employeeAttendance->early_leaving = ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00';
                        $employeeAttendance->overtime      = $overtime;
                        $employeeAttendance->total_rest    = '00:00:00';
                        $employeeAttendance->save();

                    }
                    else
                    {
                        $attendance = AttendanceEmployee::where('employee_id', '=', $employee)->where('date', '=', $request->date)->first();

                        if(!empty($attendance))
                        {
                            $employeeAttendance = $attendance;
                        }
                        else
                        {
                            $employeeAttendance              = new AttendanceEmployee();
                            $employeeAttendance->employee_id = $employee;
                            $employeeAttendance->created_by  = \Auth::user()->creatorId();
                        }

                        $employeeAttendance->status        = 'Leave';
                        $employeeAttendance->date          = $request->date;
                        $employeeAttendance->clock_in      = '00:00:00';
                        $employeeAttendance->clock_out     = '00:00:00';
                        $employeeAttendance->late          = '00:00:00';
                        $employeeAttendance->early_leaving = '00:00:00';
                        $employeeAttendance->overtime      = '00:00:00';
                        $employeeAttendance->total_rest    = '00:00:00';
                        $employeeAttendance->save();
                    }
                }

                return redirect()->back()->with('success', __('Employee attendance successfully created.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Branch & department field required.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function importFile()
    {
        return view('attendance.import');
    }

    public function import(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:csv,txt,xlsx',
        ];
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $attendance = (new AttendanceImport())->toArray(request()->file('file'))[0];

        $email_data = [];
        foreach ($attendance as $key => $employee) {
            if ($key != 0) {
                echo "<pre>";
                if ($employee != null && Employee::where('email', $employee[0])->where('created_by', \Auth::user()->creatorId())->exists()) {
                    $email = $employee[0];
                } else {
                    $email_data[] = $employee[0];
                }
            }
        }
        $totalattendance = count($attendance) - 1;
        $errorArray    = [];

        $startTime = Utility::getValByName('company_start_time');
        $endTime   = Utility::getValByName('company_end_time');

        if (!empty($attendanceData)) {
            $errorArray[] = $attendanceData;
        } else {
            foreach ($attendance as $key => $value) {
                if ($key != 0) {
                    $employeeData = Employee::where('email', $value[0])->where('created_by', \Auth::user()->creatorId())->first();
                    // $employeeId = 0;
                    if (!empty($employeeData)) {
                        $employeeId = $employeeData->id;


                        $clockIn = $value[2];
                        $clockOut = $value[3];

                        if ($clockIn) {
                            $status = "present";
                        } else {
                            $status = "leave";
                        }

                        $totalLateSeconds = strtotime($clockIn) - strtotime($startTime);

                        $hours = floor($totalLateSeconds / 3600);
                        $mins  = floor($totalLateSeconds / 60 % 60);
                        $secs  = floor($totalLateSeconds % 60);
                        $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($clockOut);
                        $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                        $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                        $secs                     = floor($totalEarlyLeavingSeconds % 60);
                        $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        if (strtotime($clockOut) > strtotime($endTime)) {
                            //Overtime
                            $totalOvertimeSeconds = strtotime($clockOut) - strtotime($endTime);
                            $hours                = floor($totalOvertimeSeconds / 3600);
                            $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                            $secs                 = floor($totalOvertimeSeconds % 60);
                            $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        } else {
                            $overtime = '00:00:00';
                        }

                        $check = AttendanceEmployee::where('employee_id', $employeeId)->where('date', $value[1])->first();
                        if ($check) {
                            $check->update([
                                'late' => $late,
                                'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
                                'overtime' => $overtime,
                                'clock_in' => $value[2],
                                'clock_out' => $value[3]
                            ]);
                        } else {
                            $time_sheet = AttendanceEmployee::create([
                                'employee_id' => $employeeId,
                                'date' => $value[1],
                                'status' => $status,
                                'late' => $late,
                                'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
                                'overtime' => $overtime,
                                'clock_in' => $value[2],
                                'clock_out' => $value[3],
                                'created_by' => \Auth::user()->id,
                            ]);
                        }
                    }
                } else {
                    $email_data = implode(' And ', $email_data);
                }
            }
            if (!empty($email_data)) {
                return redirect()->back()->with('status', 'this record is not import. ' . '</br>' . $email_data);
            } else {
                if (empty($errorArray)) {
                    $data['status'] = 'success';
                    $data['msg']    = __('Record successfully imported');
                } else {

                    $data['status'] = 'error';
                    $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalattendance . ' ' . 'record');


                    foreach ($errorArray as $errorData) {
                        $errorRecord[] = implode(',', $errorData->toArray());
                    }

                    \Session::put('errorArray', $errorRecord);
                }

                return redirect()->back()->with($data['status'], $data['msg']);
            }
        }
    }

    public function getOverView(Request $request){
        try {
            $date = date('Y-m-d',strtotime($request->date));
            if(Carbon::parse($date)->isWeekend()){
                $data  = [0, 0, 0, 0, 0];
            }else{
                $todayAttendance = AttendanceEmployee::where('date', $date)->where('created_by', \Auth::user()->creatorId())->get()->toArray();

                $totalPresents = $late = $totalFlexiTime = 0;
                foreach ($todayAttendance as $key => $value) {
                    if($value['status']=="Present" && $value['late']=="00:00:00" && empty($value['requested_time'])){
                        $totalPresents += 1;
                    }

                    if($value['status']=="Present" && $value['late'] != "00:00:00" && empty($value['requested_time'])){
                        $late += 1;
                    }

                    if($value['status']=="Present" && !empty($value['requested_time'])){
                        $totalFlexiTime += 1;
                    }
                }

                $totalEmployees = Employee::where('created_by', \Auth::user()->creatorId())->count();

                $onLeaves = Leave::where('start_date', '>=', $date)->Where('end_date','<=', $date)->where('status','Approved')->count();

                $absentEmployees = $totalEmployees - (count($todayAttendance) + $onLeaves);
                
                $data = [$totalPresents, $absentEmployees, $onLeaves,$late,$totalFlexiTime];
            }
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 200);
        }
    }

    public function userGraph(Request $request)
    {
        $employeeId =  $request->id;
        return view('attendance.user-graph', compact('employeeId'));
    }


    public function getSingleUserAttendance(Request $request){
        $employeeId = $request->employeeId;
        $selectedRange = $request->selectedRange ?? 1;

        $activeTimes = $lates = $overTimes = $flexiTimes = $earlyleaves = $labels = [];
        $dates = [];
        $currentDate = Carbon::today();

        // Loop to create an array of the previous 7 dates
        for ($i = 1; $i <= $selectedRange; $i++) {
            $date = $currentDate->subDay()->toDateString();
            $dates[] = $date;
            $labels[] = date('m/d/Y', strtotime($date))." GMT";
        }

        $dates = array_reverse($dates);

        $attandance = AttendanceEmployee::where('employee_id', $employeeId)->where('date','>=',$dates[0])->where('date','<=', end($dates))->get()->toArray();

        foreach ($dates as $key => $value) {
            $specificDateData = array_values(array_filter($attandance, function($row) use ($value){
                return ($row['date']==$value);
            }));
            if(empty($specificDateData)){
                $activeTimes[] = 0;
                $lates[] = 0;
                $overTimes[] = 0;
                $earlyleaves[] = 0;
                $flexiTimes[] = 0;
            }else{
                foreach ($specificDateData as $val) {

                    if($val['clock_out'] !=="00:00:00"){
                        $clockIn = Carbon::parse($val['clock_in']);
                        $clockOut = Carbon::parse($val['clock_out']);
                        $diffInMinutes = $clockOut->diffInMinutes($clockIn);
                        $hours = floor($diffInMinutes / 60);
                        $minutes = $diffInMinutes % 60;

                        $activeTimes[] = $hours.'.'.$minutes;
                    }else{
                        $activeTimes[] = 0;
                    }
                    
                    // OVER TIME CALCULATION
                    if($val['overtime'] != "00:00:00"){
                        $overtime = Carbon::parse($val['overtime']);
                        $overTimes[] = $overtime->hour.'.'.$overtime->minute;
                    } else {
                        $overTimes[] = 0;
                    }

                    // LATE CALCULATION
                    if($val['late'] != "00:00:00"){
                        $late = Carbon::parse($val['late']);
                        $lates[] = $late->hour.'.'.$late->minute;
                    } else {
                        $lates[] = 0;
                    }

                    // EARLY LEAVE CALCULATION
                    if($val['early_leaving'] != "00:00:00"){
                        $earlyLeave = Carbon::parse($val['early_leaving']);
                        $earlyleaves[] = $earlyLeave->hour.'.'.$earlyLeave->minute;
                    } else {
                        $earlyleaves[] = 0;
                    }

                    if(!empty($val['requested_time'])){
                        $requestedTime = explode('-', $val['requested_time']);
                        $startTime = Carbon::parse($requestedTime[0]);
                        $endTime = Carbon::parse($requestedTime[1]);
                        $totalDiffInMinutes = $endTime->diffInMinutes($startTime);
                        $totalHours = floor($totalDiffInMinutes / 60);
                        $totalMinutes = $totalDiffInMinutes % 60;
                        $flexiTimes[] = $totalHours.'.'.$totalMinutes;
                    } else {
                        $flexiTimes[] = 0;
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'activeTimes' => $activeTimes,
            'lates' => $lates,
            'overTimes' => $overTimes,
            'earlyleaves' => $earlyleaves,
            'flexiTimes' => $flexiTimes,
            'labels' => $labels
        ]);
    }

    public function getUserAttendance(Employee $employee)
    {
        // dd($employee);
        
        return view('attendance.user-graph',compact('employee'));
    }
}
