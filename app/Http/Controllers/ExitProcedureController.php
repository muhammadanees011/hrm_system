<?php

namespace App\Http\Controllers;

use App\Models\ExitProcedure;
use Illuminate\Http\Request;

class ExitProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        if(\Auth::user()->can('Manage Termination Type'))
        {
            $exitprocedures = ExitProcedure::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('exitprocedure.index', compact('exitprocedures'));
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
        if(\Auth::user()->can('Create Termination'))
        {
            $exitprocedures        = ExitProcedure::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            return view('exitprocedure.create', compact('exitprocedures'));
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
        
        if(\Auth::user()->can('Create Termination'))
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

            $exitprocedure            = new ExitProcedure();
            $exitprocedure->name      = $request->name;
            $exitprocedure->created_by   = \Auth::user()->creatorId();
            $exitprocedure->save();
           return redirect()->route('exitprocedure.index')->with('success', __('ExitProcedure  successfully created.'). ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));


        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ExitProcedure $exitProcedure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExitProcedure $exitprocedure)
    {
        if(\Auth::user()->can('Edit Termination'))
        {
            if($exitprocedure->created_by == \Auth::user()->creatorId())
            {

                return view('exitprocedure.edit', compact('exitprocedure'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExitProcedure $exitprocedure)
    {
         
        if(\Auth::user()->can('Create Termination'))
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

            $exitprocedure->name      = $request->name;
            $exitprocedure->save();
           return redirect()->route('exitprocedure.index')->with('success', __('ExitProcedure  successfully updated.'). ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));


        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExitProcedure $exitprocedure)
    {
        if(\Auth::user()->can('Delete Termination'))
        {
            if($exitprocedure->created_by == \Auth::user()->creatorId())
            {
                $exitprocedure->delete();

                return redirect()->route('exitprocedure.index')->with('success', __('ExitProcedure successfully deleted.'));
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
