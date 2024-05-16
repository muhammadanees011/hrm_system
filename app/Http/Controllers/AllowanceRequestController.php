<?php

namespace App\Http\Controllers;

use App\Models\AllowanceRequest;
use App\Models\AllowanceOption;
use App\Models\Allowance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AllowanceRequestController extends Controller
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
        $allowance_options = AllowanceOption::get()->pluck('name', 'id');
        $employees          = Employee::get()->pluck('name','id');
        $Allowancetypes =Allowance::$Allowancetype;

        return view('allowancerequest.create', compact('employees', 'allowance_options','Allowancetypes'));
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
                    'allowance_option' => 'required',
                    'title' => 'required',
                    'amount' => 'required',
                    'type' => 'required',
                    'description' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $allowancerequest                   = new AllowanceRequest();
            $allowancerequest->employee_id      = $request->employee_id;
            $allowancerequest->allowance_option = $request->allowance_option;
            $allowancerequest->title            = $request->title;
            $allowancerequest->type             = $request->type;
            $allowancerequest->amount           = $request->amount;
            $allowancerequest->description      = $request->description;
            $allowancerequest->status           = 'Pending';
            $allowancerequest->created_by       = \Auth::user()->creatorId();
            $allowancerequest->save();

            if(  $allowancerequest->type == 'percentage' )
            {
                $employee          = Employee::find($allowancerequest->employee_id);
                $empsal  = $allowancerequest->amount * $employee->salary / 100;
                
            }

            return redirect()->back()->with('success', __('Allowance Request successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AllowanceRequest $allowanceRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AllowanceRequest $allowancerequest)
    {
        if(\Auth::user()->can('Edit Allowance'))
        {
            $allowance_options = AllowanceOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $Allowancetypes =Allowance::$Allowancetype;
            $employees          = Employee::get()->pluck('name','id');

            return view('allowancerequest.edit', compact('employees','allowancerequest', 'allowance_options','Allowancetypes'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AllowanceRequest $allowancerequest)
    {
        if(\Auth::user()->can('Create Allowance'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'allowance_option' => 'required',
                    'title' => 'required',
                    'amount' => 'required',
                    'type' => 'required',
                    'description' => 'required',
                    'status' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $allowancerequest->employee_id      = $request->employee_id;
            $allowancerequest->allowance_option = $request->allowance_option;
            $allowancerequest->title            = $request->title;
            $allowancerequest->type             = $request->type;
            $allowancerequest->amount           = $request->amount;
            $allowancerequest->description      = $request->description;
            $allowancerequest->status           = $request->status;
            $allowancerequest->save();

            if(  $allowancerequest->type == 'percentage' )
            {
                $employee          = Employee::find($allowancerequest->employee_id);
                $empsal  = $allowancerequest->amount * $employee->salary / 100;
                
            }

            return redirect()->back()->with('success', __('Allowance Request successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AllowanceRequest $allowancerequest)
    {
        if(\Auth::user()->can('Delete Allowance'))
        {
            $allowancerequest->delete();
            return redirect()->back()->with('success', __('Allowance Request successfully deleted.'));  
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
