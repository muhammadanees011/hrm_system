<?php

namespace App\Http\Controllers;

use App\Models\EmployementCheck;
use App\Models\EmployementCheckType;
use App\Models\Utility;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EmployementCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Employee')) {
            if (\Auth::user()->type == 'employee') {
                $employementchecktypes = EmployementCheckType::where('created_by', '=', \Auth::user()->creatorId())->get();
                $employee = Employee::where('user_id', '=', \Auth::user()->id)->first();
                $employementchecks = EmployementCheck::where('employee_id', '=',$employee->id)->with('employementcheckType')->get();
            } else {
                $employementchecktypes = EmployementCheckType::where('created_by', '=', \Auth::user()->creatorId())->get();
                $employementchecks = EmployementCheck::with('employementcheckType')->get();
            }
            return view('employementcheck.index',compact('employementchecktypes','employementchecks'));
        } else {
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
            if (\Auth::user()->type == 'employee') {
                $employementchecktypes=EmployementCheckType::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');   
                $employees = Employee::where('user_id', '=', \Auth::user()->id)->get()->pluck('name', 'id');
                return view('employementcheck.create', compact('employementchecktypes','employees'));
            }else{
                $employementchecktypes=EmployementCheckType::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');   
                $employees = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                return view('employementcheck.create', compact('employementchecktypes','employees'));
            }
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
        $validator = \Validator::make(
            $request->all(),
            [
                'file' => 'required|file|mimes:pdf|max:10240',
                'employee_id' => 'required',
                'employementchecktype' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $dir = 'employementcheck_attachment/';
        $employeeName=Employee::where('id',$request->employee_id)->value('name');
        $employeeName=strtolower(str_replace(' ', '-', $employeeName));
        $employementchecktypeName=EmployementCheckType::where('id','=',$request->employementchecktype)->value('title');
        $employementchecktypeName=strtolower(str_replace(' ', '-', $employementchecktypeName));
        $fileName=$employeeName.'-'.$employementchecktypeName.'.pdf';
        $files=$fileName;
        $path = Utility::upload_file($request, 'file', $files, $dir, []);
        if ($path['flag'] == 1) {
            $file = $path['url'];
        } else {
            return redirect()->back()->with('error', __($path['msg']));
        }
        $file = EmployementCheck::create(
            [
                'employementcheck_type' => $request->employementchecktype,
                'employee_id' => $request->employee_id,
                'files' => $files,
            ]
        );
        return redirect()->route('employementcheck.index')->with('success', __('Employement Check  successfully created.'));
    }

    public function viewFile($filename)
    {
        $path = storage_path('employementcheck_attachment/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }
    
        $file = file_get_contents($path);
    
        return Response::make($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function downloadFile($filename)
    {
        $path = storage_path('employementcheck_attachment/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }
    
        $file = file_get_contents($path);
    
        return response()->download($path, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    public function deleteFile($id)
    {
        if (\Auth::user()->can('Manage Employee')) {
            $file = EmployementCheck::find($id);
            if ($file) {
                $path = storage_path('employementcheck_attachment/' . $file->files);
                if (file_exists($path)) {
                    \File::delete($path);
                }
                $file->delete();

                return redirect()->back()->with('success', __('File successfully deleted!'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('File does not exist.'),
                    ],
                    200
                );
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

}
