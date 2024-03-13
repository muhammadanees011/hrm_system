<?php

namespace App\Http\Controllers;

use App\Models\RetirementType;
use Illuminate\Http\Request;

class RetirementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Termination Type'))
        {
            $retirementtypes = RetirementType::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('retirementtype.index', compact('retirementtypes'));
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
        if(\Auth::user()->can('Create Termination Type'))
        {
            return view('retirementtype.create');
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
        if(\Auth::user()->can('Create Termination Type'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $retirementtype             = new RetirementType();
            $retirementtype->name       = $request->name;
            $retirementtype->created_by = \Auth::user()->creatorId();
            $retirementtype->save();

            return redirect()->route('retirementtype.index')->with('success', __('RetirementType  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RetirementType $retirementType)
    {
        //
    }

    public function edit(RetirementType $retirementtype)
    {
        if(\Auth::user()->can('Edit Termination Type'))
        {
            // if($retirementType->created_by == \Auth::user()->creatorId())
            // {

                return view('retirementtype.edit', compact('retirementtype'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Permission denied.')], 401);
            // }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, RetirementType $retirementtype)
    {   
        if(\Auth::user()->can('Edit Termination Type'))
        {
            if($retirementtype->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',

                                   ]
                );

                $retirementtype->name = $request->name;
                $retirementtype->save();

                return redirect()->route('retirementtype.index')->with('success', __('RetirementType successfully updated.'));
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
    public function destroy(RetirementType $retirementtype)
    {
        if(\Auth::user()->can('Delete Termination Type'))
        {
            if($retirementtype->created_by == \Auth::user()->creatorId())
            {
                // $retirementType     = Termination::where('termination_type',$retirementType->id)->get();
                // if(count($termination) == 0)
                // {
                    $retirementtype->delete();
                // }
                // else
                // {
                //     return redirect()->route('retirementtype.index')->with('error', __('This RetirementType has Retirement. Please remove the Retirement from this RetirementType.'));
                // }

                return redirect()->route('retirementtype.index')->with('success', __('RetirementType successfully deleted.'));
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
