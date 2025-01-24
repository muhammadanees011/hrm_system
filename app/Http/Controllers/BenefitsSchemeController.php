<?php

namespace App\Http\Controllers;

use App\Models\BenefitsScheme;
use App\Models\Branch;
use App\Models\PensionScheme;
use Illuminate\Http\Request;

class BenefitsSchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Branch')) {

            $schemes = BenefitsScheme::all();

            return view('benefitsScheme.index', compact('schemes'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (\Auth::user()->can('Create Scheme')) {
            return view('benefitsScheme.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Scheme')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'scheme_name' => 'required',
                    'contribution_percentage' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

           $benefitsScheme = new BenefitsScheme();
              $benefitsScheme->scheme_name = $request->scheme_name;
                $benefitsScheme->contribution_percentage = $request->contribution_percentage;
                $benefitsScheme->created_by = \Auth::user()->creatorId();
                $benefitsScheme->save();
                

            return redirect()->route('benefits-schemes.index')->with('success', __('Scheme successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('benefits-schemes.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PensionScheme $PensionScheme)
    {
        if (\Auth::user()->can('Edit Scheme')) {
            if ($PensionScheme->created_by == \Auth::user()->creatorId()) {

                return view('benefitsScheme.edit', compact('PensionScheme'));
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
    public function update(Request $request, PensionScheme $PensionScheme)
    {
        if (\Auth::user()->can('Edit Scheme')) {
            if ($PensionScheme->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'scheme_name' => 'required',
                        'contribution_percentage' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $PensionScheme->scheme_name       = $request->scheme_name;
                $PensionScheme->contribution_percentage       = $request->contribution_percentage;
                $PensionScheme->save();

                return redirect()->route('benefuts-schemes.index')->with('success', __('Scheme successfully updated.'));
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
    public function destroy(PensionScheme $PensionScheme)
    {
        if (\Auth::user()->can('Delete Scheme')) {
            if ($PensionScheme->created_by == \Auth::user()->creatorId()) {
                $PensionScheme->delete();
                return redirect()->route('benefits-schemes.index')->with('success', __('Scheme successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
