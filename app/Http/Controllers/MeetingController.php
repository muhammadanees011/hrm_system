<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\MeetingTemplate;
use App\Models\Meeting as LocalMeeting;
use App\Models\MeetingEmployee;
use Illuminate\Http\Request;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Spatie\GoogleCalendar\Event as GoogleEvent;

class MeetingController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Meeting')) {
            $employees = Employee::get();
            if (Auth::user()->type == 'employee') {
                $user = \Auth::user();
                $meetings = LocalMeeting::where('organizer_id', '=', $user->id) 
                    ->orWhere('invitee_id', '=', $user->id) 
                    ->get();
            } else {
                $meetings = LocalMeeting::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('meeting.index', compact('meetings', 'employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $user=\Auth::user();
        if (\Auth::user()->can('Create Meeting')) {
            if (Auth::user()->type == 'employee') {
                $organizers = User::where('id', $user->id)->get()->pluck('name', 'id');
            } else {
                $organizers = User::where('type', '!=', 'company')->get()->pluck('name', 'id');
            }
            $invitees = User::where('type', '!=', 'company')->get()->pluck('name', 'id');
            $templates = MeetingTemplate::get()->pluck('title', 'id');
            return view('meeting.create', compact('organizers','invitees','templates'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'organizer_id' => 'required',
                'invitee_id' => 'required',
                'meeting_template_id' => 'required',
                'title' => 'required',
                'date' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        if (\Auth::user()->can('Create Meeting')) {
            $meeting                = new LocalMeeting();
            $meeting->organizer_id  = $request->organizer_id;
            $meeting->invitee_id    = $request->invitee_id;
            $meeting->meeting_template_id   =$request->meeting_template_id;
            $meeting->title         = $request->title;
            $meeting->date          = $request->date;
            $meeting->start_time    = $request->start_time;
            $meeting->end_time      = $request->end_time;
            $meeting->note          = $request->note;
            $meeting->created_by    = \Auth::user()->creatorId();
            $meeting->save();

            // google calendar
            if ($request->get('synchronize_type')  == 'google_calender') {

                $type = 'meeting';
                $request1 = new GoogleEvent();
                $request1->title = $request->title;
                $request1->start_date = $request->date;
                $request1->time = $request->start_time;
                $request1->end_date = $request->date;

                Utility::addCalendarDataTime($request1, $type);
            }

            return redirect()->route('meeting.index')->with('success', __('Meeting successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {
        $meetings = LocalMeeting::where('id',$id)->first();
        return view('meeting.show', compact('meetings'));
        // return redirect()->route('meeting.index');
    }

    public function edit($meeting)
    {
        $user=\Auth::user();
        if (\Auth::user()->can('Edit Meeting')) {
            $meeting = LocalMeeting::find($meeting);
            if ($meeting->created_by == Auth::user()->creatorId()) {
                if (Auth::user()->type == 'employee') {
                    $organizers = User::where('id', $user->id)->get()->pluck('name', 'id');
                } else {
                    $organizers = User::where('type', '!=', 'company')->get()->pluck('name', 'id');
                }
                $invitees = User::where('type', '!=', 'company')->get()->pluck('name', 'id');
                $templates = MeetingTemplate::get()->pluck('title', 'id');
                return view('meeting.edit', compact('meeting', 'invitees','templates','organizers'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, LocalMeeting $meeting)
    {
        if (\Auth::user()->can('Edit Meeting')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'organizer_id' => 'required',
                    'invitee_id' => 'required',
                    'meeting_template_id' => 'required',
                    'title' => 'required',
                    'date' => 'required',
                    'start_time' => 'required',
                    'end_time' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if ($meeting->created_by == \Auth::user()->creatorId()) {
                $meeting->organizer_id  = $request->organizer_id;
                $meeting->invitee_id    = $request->invitee_id;
                $meeting->meeting_template_id   =$request->meeting_template_id;
                $meeting->title         = $request->title;
                $meeting->date          = $request->date;
                $meeting->start_time    = $request->start_time;
                $meeting->end_time      = $request->end_time;
                $meeting->note          = $request->note;
                $meeting->created_by    = \Auth::user()->creatorId();
                $meeting->save();

                return redirect()->route('meeting.index')->with('success', __('Meeting successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(LocalMeeting $meeting)
    {
        if (\Auth::user()->can('Delete Meeting')) {
            if ($meeting->created_by == \Auth::user()->creatorId()) {
                $meeting->delete();

                return redirect()->route('meeting.index')->with('success', __('Meeting successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getdepartment(Request $request)
    {

        if ($request->branch_id == 0) {
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
        } else {
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($departments);
    }

    public function getemployee(Request $request)
    {
        if($request->department_id)
        {
            
            $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->whereIn('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
            
        }
        return response()->json($employees);
    }

    public function calender()
    {
        $employees = Employee::get();
            if (Auth::user()->type == 'employee') {
                $current_employee = Employee::where('user_id', '=', \Auth::user()->id)->first();
                $meetings         = LocalMeeting::orderBy('meetings.id', 'desc')
                    ->leftjoin('meeting_employees', 'meetings.id', '=', 'meeting_employees.meeting_id')
                    ->where('meeting_employees.employee_id', '=', $current_employee->id)
                    ->orWhere(function ($q) {
                        $q->where('meetings.department_id', '["0"]')
                            ->where('meetings.employee_id', '["0"]');
                    })
                    ->get();
            } else {
                $meetings = LocalMeeting::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

        return view('meeting.calender' , compact('meetings', 'employees'));
    }

    public function get_meeting_data(Request $request)
    {
        $arrayJson = [];
        if($request->get('calender_type') == 'google_calender')
        {
            $type ='meeting';
            $arrayJson =  Utility::getCalendarData($type);
        }
        else
        {
            $data = LocalMeeting::get();
            
            foreach($data as $val)
            {
                if (Auth::user()->type == 'employee') {
                    $url = route('meeting.show', $val['id']);
                }else{
                    $url = route('meeting.edit', $val['id']);
                }
                $end_date=date_create($val->end_date);
                date_add($end_date,date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id"=> $val->id,
                    "title" => $val->title,
                    "start" => $val->date,
                    "end" => $val->date,
                    "className" => $val->color,
                    "textColor" => '#FFF',
                    "allDay" => true,
                    "url"=> $url,
                    // "url"=> URL::to('meeting/' . $val->id . '/edit'),
                ];
            }
        }
        return $arrayJson;
    }

    public function details($id)
    {
        $meeting = LocalMeeting::where('id',$id)->first();
        return view('meeting.detail',compact('meeting'));
    }

    public function notes(Request $request,$meeting_id)
    {
        $meeting = LocalMeeting::where('id',$meeting_id)->first();
        $meeting->organizer_note=$request->organizer_note;
        $meeting->invitee_note=$request->invitee_note;
        $meeting->save();
        return redirect()->route('meeting.details',$meeting_id)->with(['meeting' => $meeting, 'success' => __('Meeting successfully updated.')]);
    }
}
