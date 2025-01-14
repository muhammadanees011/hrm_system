<?php

namespace App\Http\Controllers;

use App\Models\EmployeeExperience;
use Illuminate\Http\Request;

class EmployeeExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $employeeExperience = new EmployeeExperience();
        $employeeExperience->company_name = $request->company_name;
        $employeeExperience->job_title = $request->job_title;
        $employeeExperience->start_date = $request->start_date;
        $employeeExperience->end_date = $request->end_date;
        $employeeExperience->employee_id = $request->employee_id;
        $employeeExperience->location = $request->location;
        $employeeExperience->description = $request->description;
        $employeeExperience->save();


        return redirect()->back()->with('success', 'Experience added successfully');


    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeExperience $employeeExperience)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeExperience $employeeExperience)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeExperience $employeeExperience)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeExperience $employeeExperience)
    {
        //
    }
}
