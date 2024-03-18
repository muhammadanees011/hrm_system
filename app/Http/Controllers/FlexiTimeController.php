<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\FlexiTime;
use App\Models\Utility;
use App\Notifications\SimpleNotification;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
class FlexiTimeController extends Controller
{
    protected $hours = [];
    // protected $hours = [
    //     "9:00" => "9:00",
    //     "10:00" => "10:00",
    //     "11:00" => "11:00",
    //     "12:00" => "12:00",
    //     "13:00" => "13:00",
    //     "14:00" => "14:00",
    //     "15:00" => "15:00",
    //     "16:00" => "16:00",
    //     "17:00" => "17:00",
    //     "18:00" => "18:00",
    //     "19:00" => "19:00"
    // ];

    public function __construct(){
        $startTime = Utility::getValByName('company_start_time');
        $endTime   = Utility::getValByName('company_end_time');
        $start = Carbon::createFromTimeString($startTime);
        $end = Carbon::createFromTimeString($endTime);

        $hoursArray = [];
        for ($hour = $start->copy(); $hour <= $end; $hour->addHour()) {
            $hoursArray[$hour->format('H:i')] = $hour->format('H:i');
        }
        $this->hours = $hoursArray;
    }

    public function index(Request $request)
    {
        if (\Auth::user()->can('Manage FlexiTime')) {
            $startDate = !empty($request->start_date) ? $request->input('start_date') : Carbon::now()->subDays(30)->toDateString();
            $endDate = !empty($request->end_date) ? $request->input('end_date') : Carbon::now()->toDateString();
            $employee = !empty($request->employee) ? $request->employee : null;

            $query = FlexiTime::with('updatedUser')
                    ->when(!empty($employee), function($query) use($employee){
                        $query->where('employee_id', $employee);
                    })
                    ->whereBetween('start_date', [$startDate, $endDate])
                    ->whereBetween('end_date', [$startDate, $endDate]);
            if(\Auth::user()->type=="employee"){
                $employeeAccount = Employee::where('user_id', \Auth::user()->id)->first();
                $query = $query->where('employee_id', $employeeAccount->id);
            }
            $records = $query->orderByDesc('id')->get();
            
            $employees = Employee::get()->pluck('name', 'id');
            return view('flexiTime.index', compact('records','employees','startDate','endDate','employee'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create FlexiTime')) {
            $startDate = Carbon::now()->toDateString();
            $endDate = Carbon::now()->toDateString();
            if (Auth::user()->type == 'employee') {
                $employees = Employee::where('user_id', '=', \Auth::user()->id)->first();
            } else {
                $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            }
            $hours = $this->hours;
            return view('flexiTime.create', compact('hours','employees', 'startDate', 'endDate'));   
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
{
    if (\Auth::user()->can('Create FlexiTime')) {

        $validator = \Validator::make(
            $request->all(),
            [
                'start_date' => 'required|string',
                'end_date' => 'required|string',
                'start_time' => 'required|string',
                'end_time' => 'required|string',
                'remark'=> 'required|string',
                'hours' => 'required|string'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        FlexiTime::create([
            'employee_id' => $request->employee_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'hours' => $request->hours,
            'remark' => $request->remark,
            'created_by' => \Auth::user()->creatorId()
        ]);

        return redirect()->route('flexi-time.index')->with('success', __('FlexiTime Request Generated Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
}


    public function show(FlexiTime $flexiTime)
    {
        return redirect()->route('flexi-time.index');
    }

    public function edit($id)
    {
        if (\Auth::user()->can('Edit FlexiTime')) {
            $flexiTime = FlexiTime::find($id);
            if ((\Auth::user()->type=="employee" && $flexiTime->employee_id == \Auth::user()->id) || $flexiTime->created_by== \Auth::user()->creatorId()) {
                if (Auth::user()->type == 'employee') {
                    $employees = Employee::where('user_id', '=', \Auth::user()->id)->first();
                } else {
                    $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                }
                $hours = $this->hours;
                return view('flexiTime.edit', compact('flexiTime', 'employees','hours'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, FlexiTime $flexiTime)
{
    if (\Auth::user()->can('Edit FlexiTime')) {
        if ((\Auth::user()->type=="employee" && $flexiTime->employee_id == \Auth::user()->id) || $flexiTime->created_by== \Auth::user()->creatorId()) {
            
            $validator = \Validator::make($request->all(),
                [
                    'start_date' => 'required|string',
                    'end_date' => 'required|string',
                    'start_time' => 'required|string',
                    'end_time' => 'required|string',
                    'remark'=> 'required|string',
                    'hours' => 'required|string'
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $flexiTime->employee_id = $request->employee_id;
            $flexiTime->start_date = $request->start_date;
            $flexiTime->end_date = $request->end_date;
            $flexiTime->start_time = $request->start_time;
            $flexiTime->end_time = $request->end_time;
            $flexiTime->hours = $request->hours;
            $flexiTime->remark = $request->remark;
            $flexiTime->update();

            return redirect()->route('flexi-time.index')->with('success', __('Flexi Time Request successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    } else {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
}


    public function destroy(FlexiTime $flexiTime)
    {
        if (\Auth::user()->can('Delete FlexiTime')) {
            if ((\Auth::user()->type=="employee" && $flexiTime->employee_id == \Auth::user()->id) || $flexiTime->created_by== \Auth::user()->creatorId()) {
                $flexiTime->delete();
                return redirect()->route('flexi-time.index')->with('success', __('Flexi Time Request successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
    public function rejectForm(Request $request, $id){
        if (\Auth::user()->can('Manage FlexiTime')) {
            $flexiTime = FlexiTime::find($id);
            $url = "flexi-time/save-reject-form/".$flexiTime->id;
            return view('flexiTime.comment-form', compact('url'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function saveRejectionForm(Request $request, $id){
        if (\Auth::user()->can('Approve FlexiTime')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'comment' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $exceptionMsg = "";
            try {
                $flexiTime = FlexiTime::find($id);
                $flexiTime->status = "rejected";
                $flexiTime->updated_user = \Auth::user()->id;
                $flexiTime->updated_user_comment = $request->comment;
                $flexiTime->save();

                \Notification::route('mail', $flexiTime->employee->email)
                ->notify(new SimpleNotification(['subject' => "FlexiTime Request Rejection Notification", "message"=> "Your FlexiTime Request got rejected. Please see below comment for detail information", "comment" => $request->comment]));
            } catch (\Exception $e) {
                   $exceptionMsg = __('E-Mail has been not sent due to SMTP configuration');
            }

            return redirect()->route('flexi-time.index')->with('success', __('FlexiTime Request status successfully updated.'). (!empty($exceptionMsg) ? '<br> <span class="text-danger">' . $exceptionMsg . '</span>' : ''));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function approve($id)
    {
        $FlexiTime = FlexiTime::find($id);
        $FlexiTime->status = "approved";
        $FlexiTime->update();
        return redirect()->route('flexi-time.index')->with('success', __('FlexiTime Request Updated Successfully.'));
    }
}
