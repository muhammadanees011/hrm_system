<?php

namespace App\Http\Controllers;

use App\Models\Retirement;
use App\Models\RetirementType;
use App\Models\ExitProcedure;
use App\Models\Employee;
use App\Models\Utility;
use Illuminate\Http\Request;

class RetirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Retirement'))
        {
            $retirements = Retirement::where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('retirement.index', compact('retirements'));
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
        if(\Auth::user()->can('Create Retirement'))
        {
            $employees        = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $retirementtypes = RetirementType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $exitprocedures=ExitProcedure::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            return view('retirement.create', compact('employees', 'retirementtypes','exitprocedures'));
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
        if(\Auth::user()->can('Create Retirement'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'retirement_type' => 'required',
                    'notice_date' => 'required',
                    'retirement_date' => 'required|after_or_equal:notice_date',
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $retirement                   = new Retirement();
            $retirement->employee_id      = $request->employee_id;
            $retirement->retirement_type = $request->retirement_type;
            $retirement->notice_date      = $request->notice_date;
            $retirement->retirement_date = $request->retirement_date;
            $retirement->description      = $request->description;
            $retirement->created_by       = \Auth::user()->creatorId();
            $retirement->save();

            $setings = Utility::settings();
            if($setings['employee_termination'] == 1)
            {
                $employee           = Employee::find($retirement->employee_id);

            $uArr = [
                'employee_retirement_name'=>$employee->name, 
                'notice_date'=>$request->notice_date,
                'retirement_date'=>$request->termination_date, 
                'retirement_type'=>$request->termination_type, 
             ];
          $resp = Utility::sendEmailTemplate('employee_termination', [$employee->email], $uArr);
           return redirect()->route('retirement.index')->with('success', __('Retirement  successfully created.'). ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

            }

            return redirect()->route('retirement.index')->with('success', __('Retirement  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Retirement $retirement)
    {

    }

    public function edit(Retirement $retirement)
    {
        if(\Auth::user()->can('Edit Retirement'))
        {
            $employees        = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $retirementtypes = RetirementType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $exitprocedures=ExitProcedure::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            if($retirement->created_by == \Auth::user()->creatorId())
            {

                return view('retirement.edit', compact('retirement', 'employees', 'retirementtypes','exitprocedures'));
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
    public function update(Request $request, Retirement $retirement)
    {
        if(\Auth::user()->can('Edit Retirement'))
        {
            if($retirement->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'employee_id' => 'required',
                                       'retirement_type' => 'required',
                                       'notice_date' => 'required',
                                       'retirement_date' => 'required',
                                       'exitprocedure_id' => 'required',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }


                $retirement->employee_id      = $request->employee_id;
                $retirement->retirement_type  = $request->retirement_type;
                $retirement->notice_date      = $request->notice_date;
                $retirement->retirement_date  = $request->retirement_date;
                $retirement->description      = $request->description;
                $retirement->exitprocedure_id = $request->exitprocedure_id;
                $retirement->save();

                return redirect()->route('retirement.index')->with('success', __('Retirement successfully updated.'));
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
    public function destroy(Retirement $retirement)
    {
        if(\Auth::user()->can('Delete Retirement'))
        {
            if($retirement->created_by == \Auth::user()->creatorId())
            {
                $retirement->delete();

                return redirect()->route('retirement.index')->with('success', __('Retirement successfully deleted.'));
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

    public function description($id)
    {
        $retirement = Retirement::find($id);
        return view('retirement.description', compact('retirement'));
    }

    public function updateExitStage(Request $request)
    {
        
        $validator = \Validator::make(
            $request->all(), [
                'retirement_id' => 'required',
                'exit_stage' => 'required',
                ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        if($retirement->created_by == \Auth::user()->creatorId())
        {
            $retirement = Retirement::find($request->retirement_id);
            $retirement->exit_stage=$request->exit_stage;
            $retirement->save();
            return redirect()->route('retirement.index')->with('success', __('exit status successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
}
