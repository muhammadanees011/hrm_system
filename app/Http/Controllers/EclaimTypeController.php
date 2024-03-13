<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Eclaim;
use App\Models\EclaimType;
use App\Models\Employee;
use Illuminate\Http\Request;

class EclaimTypeController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage EclaimType')) {
            $eclaimTypes = EclaimType::where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('eclaimTypes.index', compact('eclaimTypes'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create EclaimType')) {
            return view('eclaimTypes.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create EclaimType')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $eClaimType             = new EclaimType();
            $eClaimType->title       = $request->title;
            $eClaimType->description = $request->description;
            $eClaimType->created_by = \Auth::user()->creatorId();
            $eClaimType->save();

            return redirect()->route('eclaim_type.index')->with('success', __('EclaimType  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(EclaimType $eclaimType)
    {
        return redirect()->route('eclaim_type.index');
    }

    public function edit(EclaimType $eclaimType)
    {
        if (\Auth::user()->can('Edit EclaimType')) {
            if ($eclaimType->created_by == \Auth::user()->creatorId()) {
                return view('eclaimTypes.edit', compact('eclaimType'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, EclaimType $eclaimType)
    {
        if (\Auth::user()->can('Edit EclaimType')) {
            if ($eclaimType->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title' => 'required',
                        'description' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $eclaimType->title = $request->title;
                $eclaimType->description = $request->description;
                $eclaimType->save();

                return redirect()->route('eclaim_type.index')->with('success', __('EclaimType successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(EclaimType $eclaimType)
    {
        if (\Auth::user()->can('Delete EclaimType')) {
            if ($eclaimType->created_by == \Auth::user()->creatorId()) {
                $eclaims  = Eclaim::where('type_id', $eclaimType->id)->get();
                if (count($eclaims) == 0) {
                    $eclaimType->delete();
                } else {
                    return redirect()->route('eclaim_type.index')->with('error', __('This Eclaim Types has associated eClais with it. Please remove them first.'));
                }
                return redirect()->route('eclaim_type.index')->with('success', __('EclaimType successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
