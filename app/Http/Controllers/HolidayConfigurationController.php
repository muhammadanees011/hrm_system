<?php

namespace App\Http\Controllers;

use App\Models\HolidayConfiguration;
use Illuminate\Http\Request;

class HolidayConfigurationController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Holiday Configuration')) {
            $configuration = HolidayConfiguration::where('created_by', '=', \Auth::user()->creatorId())->first();

            return view('holidayConfiguration.index', compact('configuration'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Holiday Configuration')) {
            return view('holidayConfiguration.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Holiday Configuration')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'annual_entitlement' => 'required',
                    'total_annual_working_days' => 'required',
                    'annual_renew_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $holidayConfiguration             = new HolidayConfiguration();
            $holidayConfiguration->annual_entitlement       = $request->annual_entitlement;
            $holidayConfiguration->total_annual_working_days       = $request->total_annual_working_days;
            $holidayConfiguration->annual_renew_date       = $request->annual_renew_date;
            $holidayConfiguration->created_by = \Auth::user()->creatorId();
            $holidayConfiguration->save();

            return redirect()->route('holiday-configuration.index')->with('success', __('Holiday Configuration successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(HolidayConfiguration $holidayConfiguration)
    {
        return redirect()->route('holiday-configuration.index', compact('holidayConfiguration'));
    }

    public function edit(HolidayConfiguration $holidayConfiguration)
    {
        if (\Auth::user()->can('Edit Holiday Configuration')) {
            if ($holidayConfiguration->created_by == \Auth::user()->creatorId()) {
                return view('holidayConfiguration.edit', compact('holidayConfiguration'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('Edit Holiday Configuration')) {
            $holidayConfiguration = HolidayConfiguration::where('id', $id)->first();
            if ($holidayConfiguration->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'annual_entitlement' => 'required',
                        'total_annual_working_days' => 'required',
                        'annual_renew_date' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $holidayConfiguration->annual_entitlement       = $request->annual_entitlement;
                $holidayConfiguration->total_annual_working_days       = $request->total_annual_working_days;
                $holidayConfiguration->annual_renew_date       = $request->annual_renew_date;
                $holidayConfiguration->save();

                return redirect()->route('holiday-configuration.index')->with('success', __('Holiday Configuration successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(HolidayConfiguration $holidayConfiguration)
    {
        if (\Auth::user()->can('Delete Holiday Configuration')) {
            $holidayConfiguration->delete();
            return redirect()->route('holiday-configuration.index')->with('success', __('Holiday Configuration successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
