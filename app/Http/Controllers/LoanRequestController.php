<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanOption;
use Illuminate\Http\Request;

class LoanRequestController extends Controller
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
        $loan_options      = LoanOption::get()->pluck('name', 'id');
        $loan =loan::$Loantypes;
        return view('loanrequest.create', compact('employees','loan_options','loan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Loan'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'loan_option' => 'required',
                    'title' => 'required',
                    'amount' => 'required',
                    'reason' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $loanrequest              = new LoanRequest();
            $loanrequest->employee_id = $request->employee_id;
            $loanrequest->loan_option = $request->loan_option;
            $loanrequest->title       = $request->title;
            $loanrequest->amount      = $request->amount;
            $loanrequest->type        = $request->type;
            $loanrequest->reason      = $request->reason;
            $loanrequest->status      = 'Pending';
            $loanrequest->created_by  = \Auth::user()->creatorId();
            $loanrequest->save();

            if($loanrequest->type == 'percentage')
            {
                $employee          = Employee::find($loanrequest->employee_id);
                $loansal  = $loanrequest->amount * $employee->salary / 100;   
            }
            return redirect()->back()->with('success', __('Loan Request successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanRequest $loanRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanRequest $loanrequest)
    {
        if(\Auth::user()->can('Edit Loan'))
        {
            $employees = Employee::get()->pluck('name','id');
            $loan_options = LoanOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $loans =loan::$Loantypes;
            return view('loanrequest.edit', compact('employees','loanrequest', 'loan_options','loans'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanRequest $loanrequest)
    {
        if(\Auth::user()->can('Create Loan'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'loan_option' => 'required',
                    'title' => 'required',
                    'amount' => 'required',
                    'reason' => 'required',
                    'status' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $loanrequest->employee_id = $request->employee_id;
            $loanrequest->loan_option = $request->loan_option;
            $loanrequest->title       = $request->title;
            $loanrequest->amount      = $request->amount;
            $loanrequest->type        = $request->type;
            $loanrequest->reason      = $request->reason;
            $loanrequest->status      = $request->status;
            $loanrequest->save();

            if($loanrequest->type == 'percentage')
            {
                $employee          = Employee::find($loanrequest->employee_id);
                $loansal  = $loanrequest->amount * $employee->salary / 100;   
            }
            return redirect()->back()->with('success', __('Loan Request successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanRequest $loanrequest)
    {
        if(\Auth::user()->can('Delete Loan'))
        {
            $loanrequest->delete();
            return redirect()->back()->with('success', __('Loan Request successfully deleted.'));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
