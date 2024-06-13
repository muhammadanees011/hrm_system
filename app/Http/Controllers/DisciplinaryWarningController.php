<?php

namespace App\Http\Controllers;

use App\Models\DisciplinaryWarning;
use Illuminate\Http\Request;

class DisciplinaryWarningController extends Controller
{

    public function index($employee_id,$performance_cycle_id)
    {
        $warnings=DisciplinaryWarning::where('employee_id',$employee_id)->where('performancecycle_id',$performance_cycle_id)
        ->orderBy('created_at', 'desc')
        ->get();
        return view('disciplinarywarning.index',compact('warnings','employee_id','performance_cycle_id'));
    }

    public function create($employee_id,$performance_cycle_id)
    {
        return view('disciplinarywarning.create',compact('employee_id','performance_cycle_id'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'employee_id'          => 'nullable',
                'title'                => 'required',
                'description'          => 'required',
                'performance_cycle_id' => 'required',
                'progress'             => 'required'
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $user=\Auth::user();
        $warning = new DisciplinaryWarning();
        $warning->performancecycle_id   = $request->performance_cycle_id;
        $warning->employee_id           = $request->employee_id;
        $warning->title                 = $request->title;
        $warning->description           = $request->description;
        $warning->progress              = $request->progress;
        $warning->created_by            = \Auth::user()->creatorId();
        $warning->save();
        return redirect()->route('disciplinarywarning.index',['employee_id'=>$request->employee_id,'performance_cycle_id'=>$request->performance_cycle_id])
        ->with('success', __('Warning successfully created.'));
    }

    public function edit($id)
    {
        $warning = DisciplinaryWarning::find($id);
        $employee_id=$warning->employee_id;
        $performance_cycle_id= $warning->performancecycle_id;
        return view('disciplinarywarning.edit',compact('warning','employee_id','performance_cycle_id'));
    }

    public function update(Request $request,$id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'employee_id'          => 'nullable',
                'title'                => 'required',
                'description'          => 'required',
                'performance_cycle_id' => 'required',
                'progress'             => 'required'
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $user=\Auth::user();
        $warning =DisciplinaryWarning::find($id);
        $warning->performancecycle_id   = $request->performance_cycle_id;
        $warning->employee_id           = $request->employee_id;
        $warning->title                 = $request->title;
        $warning->description           = $request->description;
        $warning->progress              = $request->progress;
        $warning->save();
        return redirect()->route('disciplinarywarning.index',['employee_id'=>$request->employee_id,'performance_cycle_id'=>$request->performance_cycle_id])
        ->with('success', __('Warning successfully updated.'));
    }

    public function destroy($warning_id)
    {
        $warning = DisciplinaryWarning::find($warning_id);
        $employee_id=$warning->employee_id;
        $performance_cycle_id= $warning->performancecycle_id;
        $warning->delete();
        return redirect()->route('disciplinarywarning.index',['employee_id'=>$employee_id,'performance_cycle_id'=>$performance_cycle_id])
        ->with('success', __('Warning successfully deleted.'));
    }
}
