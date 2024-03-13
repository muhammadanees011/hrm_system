<?php

namespace App\Http\Controllers;

use App\Models\LeaveSummary;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveSummaryController extends Controller
{
    public function employees()
    {
        if (\Auth::user()->can('Manage Leave')) {
            $employees = Employee::where('created_by', \Auth::user()->creatorId())->with(['branch', 'department', 'designation'])->get();

            return view('leavesummary.employees', compact('employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function employeeLeaveSummary($id)
    {
        if(\Auth::user()->can('Manage Leave'))
        {
            $employee_id=$id;
            $leavesummaries = LeaveSummary::where('employee_id',$employee_id)->where('created_by', '=', \Auth::user()->creatorId())->get();

        return view('leavesummary.index', compact('leavesummaries','employee_id'));
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Leave'))
        {
            $leavesummaries = LeaveSummary::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('leavesummary.index', compact('leavesummaries'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {   
        if(\Auth::user()->can('Create Leave'))
        {
            $employee_id=$id;
            $employees  = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $leavetypes = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('leavesummary.create', compact('employees','leavetypes','employee_id'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$employee_id)
    {
        if(\Auth::user()->can('Create Leave'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'leave_type_id' => 'required',
                    'entitled' => 'required',
                    'taken' => 'required',
                    'pending' => 'required',
                    'carried_over' => 'required',
                    'balance' => 'required',
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $leavesummary               = new LeaveSummary();
            $leavesummary->employee_id  = $employee_id;
            $leavesummary->leave_type_id= $request->leave_type_id;
            $leavesummary->entitled     = $request->entitled;
            $leavesummary->taken        = $request->taken;
            $leavesummary->pending      = $request->pending;
            $leavesummary->carried_over = $request->carried_over;
            $leavesummary->balance      = $request->balance;
            $leavesummary->created_by   = \Auth::user()->creatorId();
            $leavesummary->save();

            return redirect()->route('leavesummary.employee',$employee_id)->with('success', __('Leave Summary  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveSummary $leavesummary)
    {
        if ($leavesummary->created_by == \Auth::user()->creatorId()) {
            $employee   = $leavesummary->employee->name;

            return view('leavesummary.show', compact('leavesummary', 'employee'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveSummary $leavesummary)
    {
        if(\Auth::user()->can('Edit Leave'))
        {
            $employees        = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $leavetypes = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get();

            if($leavesummary->created_by == \Auth::user()->creatorId())
            {

                return view('leavesummary.edit', compact('leavesummary', 'employees','leavetypes'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveSummary $leavesummary)
    {
         
        if(\Auth::user()->can('Edit Leave'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'leave_type' => 'required',
                    'entitled' => 'required',
                    'taken' => 'required',
                    'pending' => 'required',
                    'carried_over' => 'required',
                    'balance' => 'required',
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $leavesummary->employee_id  = $request->employee_id;
            $leavesummary->leave_type   = $request->leave_type;
            $leavesummary->entitled     = $request->entitled;
            $leavesummary->taken        = $request->taken;
            $leavesummary->pending      = $request->pending;
            $leavesummary->carried_over = $request->carried_over;
            $leavesummary->balance      = $request->balance;
            $leavesummary->save();

            return redirect()->route('leavesummary.index')->with('success', __('Leave Summary  successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id,$employee_id)
    {
        if(\Auth::user()->can('Delete Leave'))
        {
            $leavesummary=LeaveSummary::where('id',$id);
            $leavesummary->delete();

            return redirect()->route('leavesummary.employee',$employee_id)->with('success', __('Leave Summary successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        } 
    }
}
