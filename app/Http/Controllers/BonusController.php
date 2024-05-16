<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Employee;
use Illuminate\Http\Request;

class BonusController extends Controller
{

    public function create($id)
    {
        $employee = Employee::find($id);
        return view('bonus.create', compact('employee'));
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
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $bonus                 = new Bonus();
            $bonus->employee_id    = $request->employee_id;
            $bonus->title          = $request->title;
            $bonus->amount         = $request->amount;
            $bonus->created_by     = \Auth::user()->creatorId();
            $bonus->save();

            return redirect()->back()->with('success', __('Bonus  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('Manage Pay Slip')) {
            $bonus=Bonus::find($id);
            $employee = Employee::find($id);
            return view('bonus.edit', compact('bonus','employee'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request,$id)
    {
        
        if(\Auth::user()->can('Create Overtime'))
        {
            $validator = \Validator::make(
                $request->all(), [
                'employee_id' => 'required',
                'title' => 'required',
                'amount' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $bonus=Bonus::find($id);
            $bonus->employee_id    = $request->employee_id;
            $bonus->title          = $request->title;
            $bonus->amount         = $request->amount;
            $bonus->created_by     = \Auth::user()->creatorId();
            $bonus->save();

            return redirect()->back()->with('success', __('Bonus successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('Manage Pay Slip')) {
                $bonus=Bonus::find($id);
                $bonus->delete();
                return redirect()->back()->with('success', __('Bonus  successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
