<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\GoalTracking;
use App\Models\GoalType;
use App\Models\PerformanceCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoalTrackingController extends Controller
{

    public function index()
    {
        // if(\Auth::user()->can('Manage Goal Tracking'))
        // {
            $user = \Auth::user();
            $goalTrackings = GoalTracking::with(['employee', 'performanceCycle'])
            ->whereIn('id', function ($query) {
                $query->selectRaw('MIN(id)')
                ->from('goal_trackings')
                ->groupBy('employee_id');
            });

            if ($user->type == 'employee') {
                $employee = Employee::where('user_id', $user->id)->first();
                $goalTrackings->where('employee_id', '=', $employee->id);
            } else {
                $goalTrackings->where('created_by', '=', $user->creatorId());
            }

            $goalTrackings->select(
                'goal_trackings.*',
                DB::raw('(SELECT SUM(progress) FROM goal_trackings AS gt WHERE gt.employee_id = goal_trackings.employee_id) as total_progress'),
                DB::raw('(SELECT COUNT(*) FROM goal_trackings AS gt WHERE gt.employee_id = goal_trackings.employee_id) as total_goals'),
                DB::raw('(SELECT COUNT(*) FROM goal_trackings AS gt WHERE gt.employee_id = goal_trackings.employee_id AND gt.status = "Done") as total_done'),
                DB::raw('(SELECT COUNT(*) FROM goal_trackings AS gt WHERE gt.employee_id = goal_trackings.employee_id AND gt.status = "On Track") as total_on_track'),
                DB::raw('(SELECT COUNT(*) FROM goal_trackings AS gt WHERE gt.employee_id = goal_trackings.employee_id AND gt.status = "Off Track") as total_off_track'),
                DB::raw('(SELECT COUNT(*) FROM goal_trackings AS gt WHERE gt.employee_id = goal_trackings.employee_id AND gt.visibility = "Shared") as total_shared'),
                DB::raw('(SELECT COUNT(*) FROM goal_trackings AS gt WHERE gt.employee_id = goal_trackings.employee_id AND gt.visibility = "Private") as total_private')
            )
            ->get();

            $goalTrackings = $goalTrackings->get();

            return view('goaltracking.index', compact('goalTrackings'));
        // }else{
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function create()
    {
        // if(\Auth::user()->can('Create Goal Tracking'))
        // {
            $performancecycles = PerformanceCycle::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
            $employees         = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $visibility        = GoalTracking::getVisibilityOptions();
            return view('goaltracking.create', compact('employees','visibility','performancecycles'));
        // }else{
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function store(Request $request)
    {
        // if(\Auth::user()->can('Create Goal Tracking'))
        // {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id'          => 'nullable',
                    'performancecycle_id'  => 'required',
                    'start_date'           => 'required',
                    'end_date'             => 'required|after_or_equal:start_date',
                    'title'                => 'required',
                    'description'          => 'nullable',
                    'visibility'           => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $user=\Auth::user();
            $goalTracking                     = new GoalTracking();
            if(\Auth::user()->type=="employee"){
                $employee = Employee::where('user_id', $user->id)->first();
                $employee_id=$employee->id;
                $goalTracking->employee_id    = $employee_id;
            }else{
                $employee_id=$request->employee_id;
                $goalTracking->employee_id    = $employee_id;
            }
            $goalTracking->performancecycle_id= $request->performancecycle_id;
            $goalTracking->start_date         = $request->start_date;
            $goalTracking->end_date           = $request->end_date;
            $goalTracking->title              = $request->title;
            $goalTracking->description        = $request->description;
            $goalTracking->visibility         = $request->visibility ==0 ? 'Priavte':'Shared';
            $goalTracking->created_by         = \Auth::user()->creatorId();
            $goalTracking->save();

            return redirect()->route('goaltracking.goals',$employee_id)->with('success', __('Goal successfully created.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function show(GoalTracking $goalTracking)
    {
        //
    }


    public function edit($id)
    {
        // if(\Auth::user()->can('Edit Goal Tracking'))
        // {
            $performancecycles = PerformanceCycle::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
            $employees         = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $goal = GoalTracking::find($id);
            $visibility   = GoalTracking::getVisibilityOptions();

            return view('goaltracking.edit', compact('visibility', 'goal','employees','performancecycles'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function update(Request $request, $id)
    {


        // if(\Auth::user()->can('Edit Goal Tracking'))
        // {
            $goalTracking = GoalTracking::find($id);
            $validator    = \Validator::make(
                $request->all(), [
                    'employee_id'          => 'nullable',
                    'performancecycle_id'  => 'required',
                    'start_date'           => 'required',
                    'end_date'             => 'required|after_or_equal:start_date',
                    'title'                => 'required',
                    'progress'                => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $user=\Auth::user();
            if(\Auth::user()->type=="employee"){
                $employee = Employee::where('user_id', $user->id)->first();
                $employee_id=$employee->id;
                $goalTracking->employee_id    = $employee_id;
            }else{
                $employee_id=$request->employee_id;
                $goalTracking->employee_id    = $employee_id;
            }
            $goalTracking->performancecycle_id= $request->performancecycle_id;
            $goalTracking->start_date         = $request->start_date;
            $goalTracking->end_date           = $request->end_date;
            $goalTracking->title              = $request->title;
            $goalTracking->progress           = $request->progress;
            $goalTracking->save();

            return redirect()->route('goaltracking.goals',$employee_id)->with('success', __('Goal successfully updated.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function destroy($id)
    {

        // if(\Auth::user()->can('Delete Goal Tracking'))
        // {
            $goal = GoalTracking::find($id);
            $employee_id=$goal->employee_id;
            $goal->delete();
            return redirect()->route('goaltracking.goals',$employee_id)->with('success', __('Goal successfully deleted.')); 
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function goals($id)
    {
        $goals = GoalTracking::where('employee_id', '=',$id)->with(['employee'])->get();
        return view('goaltracking.goals',compact('goals'));
    }

    public function changeGoalStatus($id,$status)
    {
        $goal=GoalTracking::where('id', '=',$id)->first();
        if($goal)
        {
            $employee_id=$goal->employee_id;
            $goal->status=$status;
            $goal->save();
            return redirect()->route('goaltracking.goals',$employee_id)->with('success', __('Status successfully changed.'));
        }else{
            return redirect()->route('goaltracking.goals',$employee_id)->with('error', __('Goal Not Found.'));
        }

    }

    public function changeVisibility($id,$visibility)
    {
        $goal=GoalTracking::where('id', '=',$id)->first();
        if($goal)
        {
            $employee_id=$goal->employee_id;
            $goal->visibility=$visibility;
            $goal->save();
            return redirect()->route('goaltracking.goals',$employee_id)->with('success', __('Visibility successfully changed.'));
        }else{
            return redirect()->route('goaltracking.goals',$employee_id)->with('error', __('Goal Not Found.'));
        }

    }

    public function goaldetails($id)
    {
        $goal = GoalTracking::where('id',$id)->with(['employee', 'performanceCycle','goalResults'])->first();
        if($goal)
        {
            return view('goaltracking.goal_details', compact('goal'));
        }
        else
        {
            return redirect()->back()->with('error', __('Not Found.'));
        }
    }
}
