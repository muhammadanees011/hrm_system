<?php

namespace App\Http\Controllers;

use App\Models\AdvanceSalaryRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class AdvanceSalaryRequestController extends Controller
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
        $employees = Employee::get()->pluck('name','id');
        return view('advancesalaryrequest.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Allowance'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'title' => 'required',
                    'reason' => 'required',
                    'month' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $advancesalaryrequest                   = new AdvanceSalaryRequest();
            $advancesalaryrequest->employee_id      = $request->employee_id;
            $advancesalaryrequest->title            = $request->title;
            $advancesalaryrequest->month            = $request->month;
            $advancesalaryrequest->reason           = $request->reason;
            $advancesalaryrequest->status           = 'Pending';
            $advancesalaryrequest->created_by       = \Auth::user()->creatorId();
            $advancesalaryrequest->save();

            return redirect()->back()->with('success', __('Salary Request successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AdvanceSalaryRequest $advanceSalaryRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdvanceSalaryRequest $advancesalaryrequest)
    {
        $employees = Employee::get()->pluck('name','id');
        return view('advancesalaryrequest.edit', compact('employees','advancesalaryrequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdvanceSalaryRequest $advancesalaryrequest)
    {
        if(\Auth::user()->can('Create Allowance'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'title' => 'required',
                    'reason' => 'required',
                    'month' => 'required',
                    'status' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $advancesalaryrequest->employee_id      = $request->employee_id;
            $advancesalaryrequest->title            = $request->title;
            $advancesalaryrequest->month            = $request->month;
            $advancesalaryrequest->reason           = $request->reason;
            $advancesalaryrequest->status           = $request->status;
            $advancesalaryrequest->created_by       = \Auth::user()->creatorId();
            $advancesalaryrequest->save();

            return redirect()->back()->with('success', __('Salary Request successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdvanceSalaryRequest $advancesalaryrequest)
    {
        if(\Auth::user()->can('Delete Allowance'))
        {
            $advancesalaryrequest->delete();
            return redirect()->back()->with('success', __('Salary Request successfully deleted.'));  
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
