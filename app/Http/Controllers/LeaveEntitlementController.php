<?php

namespace App\Http\Controllers;

use App\Models\LeaveEntitlement;
use App\Models\Employee;
use Illuminate\Http\Request;

class LeaveEntitlementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveentitlements=LeaveEntitlement::get();
        return view('leaveentitlement.index',compact('leaveentitlements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees=Employee::get()->pluck('name','id');
        return view('leaveentitlement.create',compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'employee_id' => 'required',
                'base_allowance' => 'required',
                'carry_over' => 'required',
                'total_allowance' => 'required',
                'absence_count' => 'required',
                'remaining_allowance' => 'required',
                'holidays_taken' => 'required',
                'maternity_paternity' => 'required',
                'sick_leaves_taken' => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $leaveentitlement                      = new LeaveEntitlement();
        $leaveentitlement->employee_id         = $request->employee_id;
        $leaveentitlement->base_allowance      = $request->base_allowance;
        $leaveentitlement->carry_over          = $request->carry_over;
        $leaveentitlement->total_allowance     = $request->total_allowance;
        $leaveentitlement->absence_count       = $request->absence_count;
        $leaveentitlement->remaining_allowance = $request->remaining_allowance;
        $leaveentitlement->holidays_taken      = $request->holidays_taken;
        $leaveentitlement->maternity_paternity = $request->maternity_paternity;
        $leaveentitlement->sick_leaves_taken   = $request->sick_leaves_taken;
        $leaveentitlement->save();

        return redirect()->route('leaveentitlement.index')->with('success', __('LeaveEntitlement  successfully created.'));

    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveEntitlement $leaveEntitlement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employees=Employee::get()->pluck('name','id');
        $leaveentitlement=LeaveEntitlement::find($id);
        return view('leaveentitlement.edit',compact('leaveentitlement','employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'employee_id' => 'required',
                'base_allowance' => 'required',
                'carry_over' => 'required',
                'total_allowance' => 'required',
                'absence_count' => 'required',
                'remaining_allowance' => 'required',
                'holidays_taken' => 'required',
                'maternity_paternity' => 'required',
                'sick_leaves_taken' => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $leaveentitlement                      = LeaveEntitlement::find($id);
        $leaveentitlement->employee_id         = $request->employee_id;
        $leaveentitlement->base_allowance      = $request->base_allowance;
        $leaveentitlement->carry_over          = $request->carry_over;
        $leaveentitlement->total_allowance     = $request->total_allowance;
        $leaveentitlement->absence_count       = $request->absence_count;
        $leaveentitlement->remaining_allowance = $request->remaining_allowance;
        $leaveentitlement->holidays_taken      = $request->holidays_taken;
        $leaveentitlement->maternity_paternity = $request->maternity_paternity;
        $leaveentitlement->sick_leaves_taken   = $request->sick_leaves_taken;
        $leaveentitlement->save();

        return redirect()->route('leaveentitlement.index')->with('success', __('LeaveEntitlement  successfully updated.'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $leaveentitlement=LeaveEntitlement::find($id);
        $leaveentitlement->delete();
        return redirect()->route('leaveentitlement.index')->with('success', __('LeaveEntitlement  successfully deleted.'));
    }
}
