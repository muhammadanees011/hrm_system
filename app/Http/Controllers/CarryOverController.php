<?php

namespace App\Http\Controllers;

use App\Models\CarryOver;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\LeaveSummary;
use Illuminate\Http\Request;

class CarryOverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Leave')) {
            $carryrequests = CarryOver::where('created_by', \Auth::user()->creatorId())->get();
            return view('carryover.index', compact('carryrequests'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (\Auth::user()->can('Create Leave')) {
        $employees  = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $leavetypes = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('carryover.create', compact('employees','leavetypes'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        } 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Leave')) {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'leave_type_id' => 'required',
                    'leaves_count' => 'required',
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $summary=LeaveSummary::where('employee_id',$request->employee_id)->where('leave_type_id',$request->leave_type_id)->first();
            if(!$summary){
                return redirect()->back()->with('error', __('You have no leaves left in this category.'));
            }
            if($request->leave_count <= $summary->balance){
                $leaves = $request->leaves_count;
            }else{
                $leaves = $summary->balance;
            }

            $carryover               = new CarryOver();
            $carryover->employee_id  = $request->employee_id;
            $carryover->leave_type_id= $request->leave_type_id;
            $carryover->leaves_count = $leaves;
            $carryover->created_by   = \Auth::user()->creatorId();
            $carryover->save();
            return redirect()->route('carryover.index')->with('success', __('CarryOver Requested successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        } 
    
    }

    public function action($id)
    {
        if(\Auth::user()->can('Manage Leave'))
        {
            $carryover= CarryOver::where('id',$id)->first();
            return view('carryover.action', compact('carryover')); 
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function changeaction(Request $request)
    {
        if(\Auth::user()->can('Manage Leave'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'leave_id' => 'required',
                    'status' => 'required',
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $carryover = CarryOver::where('id',$request->leave_id)->first();
            if($request->status=='Approved'){
                $carryover->status  = 'accepted';
            }
            if($request->status=='Reject'){
                $carryover->status  = 'rejected';   
            }
            $carryover->save();
            return redirect()->route('carryover.index')->with('success', __('CarryOver Request successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(CarryOver $carryOver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CarryOver $carryover)
    { 
        if(\Auth::user()->can('Edit Leave'))
        {
            $employees  = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $leavetypes = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('carryover.edit', compact('employees','leavetypes','carryover'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        } 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CarryOver $carryover)
    {
        if(\Auth::user()->can('Edit Leave'))
        {

            $validator = \Validator::make(
                $request->all(), [
                'employee_id' => 'required',
                'leave_type_id' => 'required',
                'leaves_count' => 'required',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $summary=LeaveSummary::where('employee_id',$request->employee_id)->where('leave_type_id',$request->leave_type_id)->first();
            if(!$summary){
                return redirect()->back()->with('error', __('You have no leaves left in this category.'));
            }
            if($request->leaves_count <= $summary->balance){
                $leaves = $request->leaves_count;
            }else{
                $leaves = $summary->balance;
            }

            $carryover->employee_id  = $request->employee_id;
            $carryover->leave_type_id= $request->leave_type_id;
            $carryover->leaves_count = $leaves;
            $carryover->created_by   = \Auth::user()->creatorId();
            $carryover->save();
            return redirect()->route('carryover.index')->with('success', __('CarryOver Requested successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarryOver $carryover)
    {  
        if(\Auth::user()->can('Delete Leave'))
        {
            $carryover->delete();

            return redirect()->route('carryover.index')->with('success', __('CarryOver successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        } 
    }
}
