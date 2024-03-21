<?php

namespace App\Http\Controllers;

use App\Models\CompanyGoalTracking;
use App\Models\Employee;
use Illuminate\Http\Request;

class CompanyGoalTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Goal Tracking')) {
            return view('companygoaltracking.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (\Auth::user()->can('Create Goal Tracking')) {
            $employees = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            return view('companygoaltracking.create',compact('employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyGoalTracking $companyGoalTracking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyGoalTracking $companyGoalTracking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyGoalTracking $companyGoalTracking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyGoalTracking $companyGoalTracking)
    {
        //
    }
}
