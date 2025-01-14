<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSkills;
use Illuminate\Http\Request;

class EmployeeSkillsController extends Controller
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
        $validated = $request->validate([
            'employee_id' => 'required',
            'skill_name' => 'required|string|max:255',
            'skill_description' => 'nullable|string',
        ]);

        EmployeeSkills::create($validated);

        return redirect()->back()->with('success', 'Skill added successfully.');


    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeSkills $employeeSkills)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeSkills $employeeSkills)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeSkills $employeeSkills)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeSkills $employeeSkills)
    {
        
    }
}
