<?php

namespace App\Http\Controllers;

use App\Charts\MonthlyUsersChart;
use App\Models\Branch;
use App\Models\PensionScheme;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MonthlyUsersChart $chart)
    {
        if (\Auth::user()->can('Manage Position')) {
            $positions = Position::where('created_by', '=', \Auth::user()->creatorId())->get();
            $chart = $chart->build();

            return view('position.index', compact('positions', 'chart'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (\Auth::user()->can('Create Position')) {
            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('position.create', compact('branches'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Position')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'status' => 'required',
                    'job_level' => 'required',
                    'branch' => 'required',
                    'department' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $position             = new Position();
            $position->title       = $request->title;
            $position->status       = $request->status;
            $position->job_level       = $request->job_level;
            $position->branch       = $request->branch;
            $position->department       = $request->department;
            $position->created_by = \Auth::user()->creatorId();
            $position->save();

            return redirect()->route('position.index')->with('success', __('Position successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $Position)
    {
        if (\Auth::user()->can('Edit Position')) {
            if ($Position->created_by == \Auth::user()->creatorId()) {
                $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                return view('position.edit', compact('Position', 'branches'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $Position)
    {
        if (\Auth::user()->can('Edit Position')) {
            if ($Position->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title' => 'required',
                        'status' => 'required',
                        'job_level' => 'required',
                        'branch' => 'required',
                        'department' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $Position->title       = $request->title;
                $Position->status       = $request->status;
                $Position->job_level       = $request->job_level;
                $Position->branch       = $request->branch;
                $Position->department       = $request->department;
                $Position->save();

                return redirect()->route('position.index')->with('success', __('Position successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $Position)
    {
        if (\Auth::user()->can('Delete Position')) {
            if ($Position->created_by == \Auth::user()->creatorId()) {
                $Position->delete();
                return redirect()->route('position.index')->with('success', __('Position successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
