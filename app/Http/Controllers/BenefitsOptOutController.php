<?php

namespace App\Http\Controllers;

use App\Models\BenefitOptIn;
use App\Models\BenefitOptOut;
use App\Models\Employee;
use App\Models\PensionOptIn;
use App\Models\PensionOptout;
use Illuminate\Http\Request;

class BenefitsOptOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Pension Optout')) {

            $optouts = BenefitOptOut::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('benefitsOptout.index', compact('optouts'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (\Auth::user()->can('Create Pension Optout')) {
             $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            return view('benefitsOptout.create', compact('employees'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Pension Optout')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'date' => 'required',
                    'reasons' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $optout             = new BenefitOptOut();
            $optout->employee_id       = $request->employee_id;
            $optout->date       = $request->date;
            $optout->reasons       = $request->reasons;
            $optout->created_by = \Auth::user()->creatorId();
            $optout->save();

            // Pension Opt-in status => Left
            $optIn = BenefitOptIn::where('employee_id', $optout->employee_id)->where('created_by', $optout->created_by)->first();
            $optIn->status = 'Left';
            $optIn->save();

            return redirect()->route('benefits-optout.index')->with('success', __('Pension Opt-out successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('benefits-optout.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PensionOptout $PensionOptout)
    {
        if (\Auth::user()->can('Edit Pension Optout')) {
            if ($PensionOptout->created_by == \Auth::user()->creatorId()) {
                $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('benefitsOptout.edit', compact(['PensionOptout', 'employees']));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PensionOptout $PensionOptout)
    {
        if (\Auth::user()->can('Edit Pension Optout')) {
            if ($PensionOptout->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'employee_id' => 'required',
                        'date' => 'required',
                        'reasons' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $PensionOptout->employee_id       = $request->employee_id;
                $PensionOptout->date       = $request->date;
                $PensionOptout->reasons       = $request->reasons;
                $PensionOptout->save();

                return redirect()->route('benefits-optout.index')->with('success', __('Pension Optout successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BenefitOptOut $BenefitOptOut)
    {
        if (\Auth::user()->can('Delete Pension Optout')) {
            if ($BenefitOptOut->created_by == \Auth::user()->creatorId()) {
                $BenefitOptOut->delete();
                return redirect()->route('benefits-optout.index')->with('success', __('Pension Optout successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function getEmployee(Request $request)
    {
        $employee = Employee::where('created_by', '=', \Auth::user()->creatorId())->with(['branch', 'department', 'designation', 'benefitsOptin', 'benefitsOptin.benefitsScheme'])->where('id', $request->emp_id)->first();
        return response()->json(['employee' => $employee], 200);
    }
}
