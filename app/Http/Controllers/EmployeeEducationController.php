<?php

namespace App\Http\Controllers;

use App\Models\EmployeeEducation;
use Illuminate\Http\Request;

class EmployeeEducationController extends Controller
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
        $validate = $request->validate([
            'institution_name' => 'required',
            'degree_name' => 'required',
            'start_date' => 'required',
            'location' => 'required',
        ]);

        $employeeEducation = new EmployeeEducation();
        $employeeEducation->employee_id = $request->employee_id;
        $employeeEducation->institution_name = $request->institution_name;
        $employeeEducation->degree_name = $request->degree_name;
        $employeeEducation->start_date = $request->start_date;
        $employeeEducation->end_date = $request->end_date;
        $employeeEducation->total_marks = $request->total_marks;
        $employeeEducation->obtained_marks = $request->obtained_marks;
        $employeeEducation->location = $request->location;
        $employeeEducation->description = $request->description;
        $employeeEducation->save();

        return back()->with('success', 'Education added successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeEducation $employeeEducation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeEducation $employeeEducation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeEducation $employeeEducation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeEducation $employeeEducation)
    {
        //
    }
}
