<?php

namespace App\Http\Controllers;

use App\Models\PerformanceCycle;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class PerformanceCycleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->can('Create Goal Tracking')){
            $performancecycles = PerformanceCycle::where('created_by', '=', \Auth::user()->id)->get();
            // return $performancecycles[0]->getSelectedParticipants();
            return view('performancecycle.index',compact('performancecycles'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(\Auth::user()->can('Create Goal Tracking'))
        {
            $user  = \Auth::user();
            $roles = Role::where('created_by', '=', $user->creatorId())->get()->pluck('name', 'id');
            return view('performancecycle.create',compact('roles'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Goal Tracking'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'title'     => 'required',
                    'progress'  => 'required',
                    'roles' => 'required',
                    'status' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $performancecycle                   = new PerformanceCycle();
            $performancecycle->title            = $request->title;
            $performancecycle->progress         = $request->progress;
            $performancecycle->participants     = $request["roles"];
            $performancecycle->status           = $request->status;
            $performancecycle->created_by       = \Auth::user()->creatorId();
            $performancecycle->save();

            return redirect()->route('performancecycle.index')->with('success', __('Performance Cycle successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PerformanceCycle $performanceCycle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerformanceCycle $performancecycle)
    {
        if(\Auth::user()->can('Create Goal Tracking'))
        {
            $user  = \Auth::user();
            $roles = Role::where('created_by', '=', $user->creatorId())->get()->pluck('name', 'id');
            return view('performancecycle.edit',compact('performancecycle','roles'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerformanceCycle $performancecycle)
    {
        if(\Auth::user()->can('Create Goal Tracking'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'title'     => 'required',
                    'progress'  => 'required',
                    'roles' => 'required',
                    'status' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $performancecycle->title            = $request->title;
            $performancecycle->progress         = $request->progress;
            $performancecycle->participants     = $request["roles"];
            $performancecycle->status           = $request->status;
            $performancecycle->created_by       = \Auth::user()->creatorId();
            $performancecycle->save();

            return redirect()->route('performancecycle.index')->with('success', __('Performance Cycle successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerformanceCycle $performancecycle)
    {
        if(\Auth::user()->can('Delete Goal Tracking'))
        {
            if($performancecycle->created_by == \Auth::user()->creatorId())
            {
                $performancecycle->delete();
                return redirect()->route('performancecycle.index')->with('success', __('Performance Cycle successfully deleted.'));
            }else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
