<?php

namespace App\Http\Controllers;

use App\Models\GoalResult;
use Illuminate\Http\Request;

class GoalResultController extends Controller
{
    public function create($goal_id)
    {
        if(\Auth::user()->can('Create Goal Tracking'))
        {
            return view('goaltracking.create_result',compact('goal_id'));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request,$goal_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'title'     => 'required',
                'progress'  => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
    
        $goalResult             = new GoalResult();
        $goalResult->goal_id    = $goal_id;
        $goalResult->title      = $request->title;
        $goalResult->progress   = $request->progress;
        $goalResult->created_by = \Auth::user()->creatorId();
        $goalResult->save();

        return redirect()->route('goaltracking.goal.details',$goal_id)->with('success', __('Result successfully created.'));
    }

    public function edit($id)
    {
        if(\Auth::user()->can('Create Goal Tracking'))
        {
            $goal_result = GoalResult::find($id);
            return view('goaltracking.edit_result',compact('goal_result'));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request,$id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'title'     => 'required',
                'progress'  => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
    
        $goalResult             = GoalResult::find($id);
        $goal_id                = $goalResult->goal_id;
        $goalResult->title      = $request->title;
        $goalResult->progress   = $request->progress;
        $goalResult->save();

        return redirect()->route('goaltracking.goal.details',$goal_id)->with('success', __('Result successfully updated.'));
    }

    public function destroy($id)
    {
        $goal_result = GoalResult::find($id);
        if($goal_result)
        {
            $goal_id=$goal_result->goal_id;
            $goal_result->delete();
            return redirect()->route('goaltracking.goal.details',$goal_id)->with('success', __('Result successfully deleted.'));
        }else{
            return redirect()->back()->with('error', __('Not found.'));
        }
    }

    public function achieved($id)
    {
        $goal_result = GoalResult::find($id);
        if($goal_result)
        {
            $goal_id=$goal_result->goal_id;
            $goal_result->progress=100;
            $goal_result->save();
            return redirect()->route('goaltracking.goal.details',$goal_id)->with('success', __('Result successfully achieved.'));
        }else{
            return redirect()->back()->with('error', __('Not found.'));
        }
    }
}
