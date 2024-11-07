<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Department;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Team'))
        {
            $teams = Team::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('teams.index', compact('teams'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(\Auth::user()->can('Create Team'))
        {
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get();
            $departments = $departments->pluck('name', 'id');

            return view('teams.create', compact('departments'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function getMembers(Team $team)
    {   
        $employees = $team->employees;
        return view('team_members.index', compact('employees')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Team'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'department_id' => 'required',
                                   'name' => 'required|max:20',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $team               = new Team();
            $team->department_id = $request->department_id;
            $team->name          = $request->name;
            $team->created_by    = \Auth::user()->creatorId();

            $team->save();

            return redirect()->route('teams.index')->with('success', __('Team  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        if(\Auth::user()->can('Edit Team'))
        {
            if($team->created_by == \Auth::user()->creatorId())
            {

                $departments = Department::where('id', $team->department_id)->first();
                $departments = $departments->pluck('name', 'id');

                return view('teams.edit', compact('team', 'departments'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        if(\Auth::user()->can('Edit Team'))
        {
            if($team->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'department_id' => 'required',
                                       'name' => 'required|max:20',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $team->department_id = $request->department_id;
                $team->name          = $request->name;
                $team->save();

                return redirect()->route('teams.index')->with('success', __('Team successfully updated.'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        if(\Auth::user()->can('Delete Team'))
        {
            if($team->created_by == \Auth::user()->creatorId())
            {
                $team->delete();

                return redirect()->route('teams.index')->with('success', __('Team successfully deleted.'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
}
