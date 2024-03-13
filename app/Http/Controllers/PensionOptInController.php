<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PensionOptIn;
use App\Models\PensionOptout;
use App\Models\PensionScheme;
use Illuminate\Http\Request;

class PensionOptInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Pension OptIn')) {
            $optIns = PensionOptIn::where('created_by', '=', \Auth::user()->creatorId())->with(['employee', 'pensionScheme'])->get();

            return view('pensionOptIn.index', compact('optIns'));
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
            $pensionSchemes = PensionScheme::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('scheme_name', 'id');
            return view('pensionOptIn.create', compact(['employees', 'pensionSchemes']));
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
                    'pension_scheme_id' => 'required',
                    'date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $optIn             = new PensionOptIn();
            $optIn->employee_id       = $request->employee_id;
            $optIn->pension_scheme_id       = $request->pension_scheme_id;
            $optIn->date       = $request->date;
            $optIn->created_by = \Auth::user()->creatorId();
            $optIn->save();

            return redirect()->route('pension-opt-ins.index')->with('success', __('Pension Opt-in successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('pension-opt-ins.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PensionOptIn $PensionOptIn)
    {
        if (\Auth::user()->can('Edit Pension OptIn')) {
            if ($PensionOptIn->created_by == \Auth::user()->creatorId()) {
                $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $pensionSchemes = PensionScheme::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('scheme_name', 'id');

                return view('pensionOptIn.edit', compact(['PensionOptIn', 'employees', 'pensionSchemes']));
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
    public function update(Request $request, PensionOptIn $PensionOptIn)
    {
        if (\Auth::user()->can('Edit Pension OptIn')) {
            if ($PensionOptIn->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'employee_id' => 'required',
                        'pension_scheme_id' => 'required',
                        'date' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $PensionOptIn->employee_id       = $request->employee_id;
                $PensionOptIn->pension_scheme_id       = $request->pension_scheme_id;
                $PensionOptIn->date       = $request->date;
                $PensionOptIn->save();

                return redirect()->route('pension-opt-ins.index')->with('success', __('Pension Opt-in successfully updated.'));
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
                return redirect()->route('pension-opt-ins.index')->with('success', __('Pension Opt-in successfully deleted.'));
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
