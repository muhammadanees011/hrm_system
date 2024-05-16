<?php

namespace App\Http\Controllers;

use App\Models\CommissionRequest;
use App\Models\Commission;
use App\Models\Employee;
use Illuminate\Http\Request;

class CommissionRequestController extends Controller
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
        $commissions =Commission::$commissiontype;

        return view('commissionrequest.create', compact('employees','commissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        if(\Auth::user()->can('Create Commission'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'title' => 'required',
                    'amount' => 'required',
                    'description' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $commissionrequest                   = new CommissionRequest();
            $commissionrequest->employee_id      = $request->employee_id;
            $commissionrequest->title            = $request->title;
            $commissionrequest->type             = $request->type;
            $commissionrequest->amount           = $request->amount;
            $commissionrequest->description      = $request->description;
            $commissionrequest->status           = 'Pending';
            $commissionrequest->created_by       = \Auth::user()->creatorId();
            $commissionrequest->save();

            if(  $commissionrequest->type == 'percentage' )
            {
                $employee          = Employee::find($commissionrequest->employee_id);
                $comsal  = $commissionrequest->amount * $employee->salary / 100; 
                
            }

            return redirect()->back()->with('success', __('Commission Request successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CommissionRequest $commissionRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommissionRequest $commissionrequest)
    {
        $employees = Employee::get()->pluck('name','id');
        if(\Auth::user()->can('Edit Commission'))
        {   
            $commissions =Commission::$commissiontype;
            return view('commissionrequest.edit', compact('commissionrequest','commissions','employees'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommissionRequest $commissionrequest)
    {
        if(\Auth::user()->can('Edit Commission'))
        {
            if($commissionrequest->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                        'title' => 'required',
                        'amount' => 'required',
                        'description' => 'required',
                        'status' => 'required'
                    ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $commissionrequest->title  = $request->title;
                $commissionrequest->type  = $request->type;
                $commissionrequest->amount = $request->amount;
                $commissionrequest->description      = $request->description;
                $commissionrequest->status           = $request->status;
                $commissionrequest->save();

                return redirect()->back()->with('success', __('Commission Request successfully updated.'));
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
    public function destroy(CommissionRequest $commissionrequest)
    {
        
        if(\Auth::user()->can('Delete Commission'))
        {
            if($commissionrequest->created_by == \Auth::user()->creatorId())
            {

                $commissionrequest->delete();

                return redirect()->back()->with('success', __('Commission successfully deleted.'));
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
}
