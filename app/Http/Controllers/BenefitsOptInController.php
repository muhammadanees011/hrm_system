<?php

namespace App\Http\Controllers;

use App\Models\BenefitOptIn;
use App\Models\BenefitsScheme;
use App\Models\Employee;
use App\Models\PensionOptIn;
use App\Models\PensionOptout;
use App\Models\PensionScheme;
use App\Models\BenefitsRequest;
use Illuminate\Http\Request;


class BenefitsOptInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Pension OptIn')) {
            $optIns = BenefitOptIn::where('created_by', '=', \Auth::user()->creatorId())->with(['employee', 'benefitScheme'])->get();
            return view('benefitsOptIn.index', compact('optIns'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (\Auth::user()->can('Create Pension OptIn')) {
            $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $benefitsSchemes = BenefitsScheme::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('scheme_name', 'id');

            return view('benefitsOptIn.create', compact(['employees', 'benefitsSchemes']));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Pension OptIn')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'benefit_scheme_id' => 'required',
                    'date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $benefitsOptIn = new BenefitOptIn();
            $benefitsOptIn->employee_id       = $request->employee_id;
            $benefitsOptIn->benefit_scheme_id       = $request->benefit_scheme_id;
            $benefitsOptIn->date       = $request->date;
            $benefitsOptIn->created_by = \Auth::user()->creatorId();
            $benefitsOptIn->save();


            // match employee_id with employee_id in benefits_request if pending mark as approved

            $benefitsRequest = BenefitsRequest::where('employee_id', $request->employee_id)->where('benefit_id',$request->benefit_scheme_id)->first();
          
            if (!empty($benefitsRequest)) {
                $benefitsRequest->status = 'Approved';
                $benefitsRequest->save();
            }

            return redirect()->route('benefits-opt-ins.index')->with('success', __('Pension Opt-in successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('benefits-opt-ins.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PensionOptIn $PensionOptIn)
    {
        if (\Auth::user()->can('Edit Pension OptIn')) {
            if ($PensionOptIn->created_by == \Auth::user()->creatorId()) {
                $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $benefitsSchemes = BenefitsScheme::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('scheme_name', 'id');

                return view('benefitsOptIn.edit', compact(['PensionOptIn', 'employees', 'benefitsSchemes']));
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
    public function update(Request $request, BenefitOptIn $benefitOptIn)
    {
        if (\Auth::user()->can('Edit Pension OptIn')) {
            if ($PensionOptIn->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'employee_id' => 'required',
                        'benefits_scheme_id' => 'required',
                        'date' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $benefitOptIn->employee_id       = $request->employee_id;
                $benefitOptIn->benefit_scheme_id       = $request->benefit_scheme_id;
                $benefitOptIn->date       = $request->date;
                $PensionOptIn->save();

                return redirect()->route('benefits-opt-ins.index')->with('success', __('Pension Opt-in successfully updated.'));
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
    public function destroy(PensionOptIn $PensionOptIn)
    {
        if (\Auth::user()->can('Delete Pension OptIn')) {
            if ($PensionOptIn->created_by == \Auth::user()->creatorId()) {
                $PensionOptIn->delete();
                return redirect()->route('benefits-opt-ins.index')->with('success', __('Pension Opt-in successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getEmployee(Request $request)
    {
        $employee = Employee::where('created_by', '=', \Auth::user()->creatorId())->with(['branch', 'department', 'designation', 'salaryType'])->where('id', $request->emp_id)->first();
        return response()->json(['employee' => $employee], 200);
    }
}
