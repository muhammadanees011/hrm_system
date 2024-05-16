<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class OvertimeRequestController extends Controller
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
        return view('overtimerequest.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Overtime'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'title' => 'required',
                    'number_of_days' => 'required',
                    'hours' => 'required',
                    'rate' => 'required',
                    'status' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $overtimerequest                 = new OvertimeRequest();
            $overtimerequest->employee_id    = $request->employee_id;
            $overtimerequest->title          = $request->title;
            $overtimerequest->number_of_days = $request->number_of_days;
            $overtimerequest->hours          = $request->hours;
            $overtimerequest->rate           = $request->rate;
            $overtimerequest->status         = $request->status;
            $overtimerequest->created_by     = \Auth::user()->creatorId();
            $overtimerequest->save();

            return redirect()->back()->with('success', __('Overtime Request successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OvertimeRequest $overtimeRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OvertimeRequest $overtimerequest)
    {
        if(\Auth::user()->can('Edit Overtime'))
        {
            $employees = Employee::get()->pluck('name','id');
            return view('overtimerequest.edit', compact('overtimerequest','employees'));
            
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OvertimeRequest $overtimerequest)
    {
        if(\Auth::user()->can('Edit Overtime'))
        {
            if($overtimerequest->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                        'title' => 'required',
                        'number_of_days' => 'required',
                        'hours' => 'required',
                        'rate' => 'required',
                        'status' => 'required',
                    ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                
                $overtimerequest->title          = $request->title;
                $overtimerequest->number_of_days = $request->number_of_days;
                $overtimerequest->hours          = $request->hours;
                $overtimerequest->rate           = $request->rate;
                $overtimerequest->status         = $request->status;
                $overtimerequest->save();

                return redirect()->back()->with('success', __('Overtime Request successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OvertimeRequest $overtimerequest)
    {
        if(\Auth::user()->can('Delete Overtime'))
        {
            $overtimerequest->delete();
            return redirect()->back()->with('success', __('Overtime Request successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
