<?php

namespace App\Http\Controllers;

use App\Models\BonusRequest;
use Illuminate\Http\Request;
use App\Models\Employee;

class BonusRequestController extends Controller
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
        $employees = Employee::get()->pluck('name', 'id');
        return view('bonusrequest.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Overtime'))
        {
            $validator = \Validator::make(
                $request->all(), [
                'employee_id' => 'required',
                'title' => 'required',
                'amount' => 'required',
                'description' => 'required',

                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $bonusrequest                 = new BonusRequest();
            $bonusrequest->employee_id    = $request->employee_id;
            $bonusrequest->title          = $request->title;
            $bonusrequest->amount         = $request->amount;
            $bonusrequest->description    = $request->description;
            $bonusrequest->created_by     = \Auth::user()->creatorId();
            $bonusrequest->save();

            return redirect()->back()->with('success', __('Bonus Request successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BonusRequest $bonusRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BonusRequest $bonusrequest)
    {
        $employees = Employee::get()->pluck('name', 'id');
        return view('bonusrequest.edit', compact('bonusrequest','employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BonusRequest $bonusrequest)
    {
        if(\Auth::user()->can('Create Overtime'))
        {
            $validator = \Validator::make(
                $request->all(), [
                'employee_id' => 'required',
                'title' => 'required',
                'amount' => 'required',
                'description' => 'required',

                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $bonusrequest->employee_id    = $request->employee_id;
            $bonusrequest->title          = $request->title;
            $bonusrequest->amount         = $request->amount;
            $bonusrequest->description    = $request->description;
            $bonusrequest->created_by     = \Auth::user()->creatorId();
            $bonusrequest->save();

            return redirect()->back()->with('success', __('Bonus Request successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BonusRequest $bonusrequest)
    {
        $bonusrequest->delete();
        return redirect()->back()->with('success', __('Bonus Request successfully deleted.'));
    }
}
