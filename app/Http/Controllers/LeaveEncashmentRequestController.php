<?php

namespace App\Http\Controllers;

use App\Models\LeaveEncashmentRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class LeaveEncashmentRequestController extends Controller
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
        return view('leaveencashmentrequest.create', compact('employees'));
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
                    'description' => 'required',
                    'days_requested' => 'required',
                    'amount_requested' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $leaveencashmentrequest                   = new LeaveEncashmentRequest();
            $leaveencashmentrequest->employee_id      = $request->employee_id;
            $leaveencashmentrequest->title            = $request->title;
            $leaveencashmentrequest->description      = $request->description;
            $leaveencashmentrequest->status           = 'Pending';
            $leaveencashmentrequest->days_requested   = $request->days_requested;
            $leaveencashmentrequest->amount_requested = $request->amount_requested;
            $leaveencashmentrequest->created_by       = \Auth::user()->creatorId();
            $leaveencashmentrequest->save();

            return redirect()->back()->with('success', __('Leave Encashment Request successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveEncashmentRequest $leaveencashmentrequest)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveEncashmentRequest $leaveencashmentrequest)
    {
        $employees = Employee::get()->pluck('name','id');
        return view('leaveencashmentrequest.edit', compact('employees','leaveencashmentrequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveEncashmentRequest $leaveencashmentrequest)
    {
        if(\Auth::user()->can('Create Allowance'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'days_requested' => 'required',
                    'amount_requested' => 'required',
                    'status' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $leaveencashmentrequest->employee_id      = $request->employee_id;
            $leaveencashmentrequest->title            = $request->title;
            $leaveencashmentrequest->description      = $request->description;
            $leaveencashmentrequest->days_requested   = $request->days_requested;
            $leaveencashmentrequest->amount_requested = $request->amount_requested;
            $leaveencashmentrequest->status           = $request->status;
            $leaveencashmentrequest->save();

            return redirect()->back()->with('success', __('Leave Encashment Request successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveEncashmentRequest $leaveencashmentrequest)
    {
        if(\Auth::user()->can('Delete Allowance'))
        {
            $leaveencashmentrequest->delete();
            return redirect()->back()->with('success', __('Leave Encashment Request successfully deleted.'));  
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
