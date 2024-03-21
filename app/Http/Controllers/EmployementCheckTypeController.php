<?php

namespace App\Http\Controllers;

use App\Models\EmployementCheckType;
use Illuminate\Http\Request;

class EmployementCheckTypeController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('Manage Employee'))
        {
            $employementchecktypes = EmployementCheckType::where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('employementchecktype.index', compact('employementchecktypes'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(\Auth::user()->can('Manage Employee'))
        {
            return view('employementchecktype.create');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Manage Employee'))
        {
            $validator = \Validator::make(
                $request->all(), [
                'title' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $employementchecktype             = new EmployementCheckType();
            $employementchecktype->title      = $request->title;
            $employementchecktype->created_by = \Auth::user()->creatorId();
            $employementchecktype->save();

            return redirect()->route('employementchecktype.index')->with('success', __('Employement Check Type  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployementCheckType $employementchecktype)
    {
        //
    }

    public function edit(EmployementCheckType $employementchecktype)
    {
        if(\Auth::user()->can('Manage Employee'))
        {
            return view('employementchecktype.edit', compact('employementchecktype'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, EmployementCheckType $employementchecktype)
    {   
        if(\Auth::user()->can('Manage Employee'))
        {
            if($employementchecktype->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                    'title' => 'required|max:20',
                    ]
                );

                $employementchecktype->title = $request->title;
                $employementchecktype->save();

                return redirect()->route('employementchecktype.index')->with('success', __('Employement Check Type successfully updated.'));
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
    public function destroy(EmployementCheckType $employementchecktype)
    {
        if(\Auth::user()->can('Manage Employee'))
        {
            if($employementchecktype->created_by == \Auth::user()->creatorId())
            {
                $employementchecktype->delete();
                return redirect()->route('employementchecktype.index')->with('success', __('EmployementCheckType successfully deleted.'));
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
