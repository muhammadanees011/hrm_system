<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usr = \Auth::user();
        if ($usr->can('Manage Pay Slip')) {
            // $bonuses  = Bonus::where('created_by', '=', \Auth::user()->creatorId())->get();
            $bonuses  = Bonus::get();
            // if (Auth::user()->type == 'employee') {
            //     $emp    = Employee::where('user_id', '=', \Auth::user()->id)->first();
            //     $awards = Award::where('employee_id', '=', $emp->id)->get();
            // } else {
            //     $awards = Award::where('created_by', '=', \Auth::user()->creatorId())->with(['employee', 'awardType'])->get();
            // }

            return view('bonus.index', compact('bonuses'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (\Auth::user()->can('Create Pay Slip')) {
            return view('bonus.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Pay Slip'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $bonus = new Bonus();
            $bonus->name = $request->name;
            $bonus->created_by = \Auth::user()->creatorId();
            $bonus->save();

            return redirect()->route('bonus.index')->with('success', __('Bonus Type successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bonus $bonus)
    {
        //
    }

    public function edit(Bonus $bonus)
    {
        if (\Auth::user()->can('Manage Pay Slip')) {
            // if ($award->created_by == \Auth::user()->creatorId()) {
                return view('bonus.edit', compact('bonus'));
            // } else {
            //     return response()->json(['error' => __('Permission denied.')], 401);
            // }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Bonus $bonus)
    {
        //
    }

    public function destroy(Bonus $bonus)
    {
        if (\Auth::user()->can('Manage Pay Slip')) {
            if ($bonus->created_by == \Auth::user()->creatorId()) {
                $bonus->delete();

                return redirect()->route('bonus.index')->with('success', __('Bonus Type successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function deleteBonus($id)
    {
        if (\Auth::user()->can('Manage Pay Slip')) {
                $bonus=Bonus::find($id);
                $bonus->delete();
                return redirect()->route('bonus.index')->with('success', __('Bonus Type successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
