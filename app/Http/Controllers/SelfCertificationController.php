<?php

namespace App\Http\Controllers;

use App\Models\SelfCertification;
use App\Models\Employee;
use App\Models\Utility;
use App\Models\HealthFitnessAttachment;
use Illuminate\Http\Request;

class SelfCertificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Health And Fitness'))
        {
            $selfcertifications = SelfCertification::where('created_by', '=', \Auth::user()->creatorId())->get();

            $total_selfcertifications = SelfCertification::where('created_by', '=', \Auth::user()->creatorId())->count();
            $curr_month  = SelfCertification::where('created_by', '=', \Auth::user()->creatorId())->whereMonth('created_at', '=', date('m'))->count();
            $curr_week   = SelfCertification::where('created_by', '=', \Auth::user()->creatorId())->whereBetween(
                'created_at',
                [
                    \Carbon\Carbon::now()->startOfWeek(),
                    \Carbon\Carbon::now()->endOfWeek(),
                ]
            )->count();
            $last_30days = SelfCertification::where('created_by', '=', \Auth::user()->creatorId())->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->count();

            $cnt_selfcertification                = [];
            $cnt_selfcertification['total']       = $total_selfcertifications;
            $cnt_selfcertification['this_month']  = $curr_month;
            $cnt_selfcertification['this_week']   = $curr_week;
            $cnt_selfcertification['last_30days'] = $last_30days;

            return view('selfcertification.index', compact('selfcertifications','cnt_selfcertification'));
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
        if(\Auth::user()->can('Create Health And Fitness'))
        {
            $employees        = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            return view('selfcertification.create', compact('employees'));
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
        if(\Auth::user()->can('Create Health And Fitness'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'certification_date' => 'required',
                    'certification_type' => 'required',
                    'details' => 'nullable',
                    'certification_file' => 'nullable',
                    ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $selfcertification                   = new SelfCertification();
            $selfcertification->employee_id      = $request->employee_id;
            $selfcertification->certification_date  = $request->certification_date;
            $selfcertification->certification_type = $request->certification_type;
            $selfcertification->details          = $request->details;
            $selfcertification->certification_file   = $request->certification_file;
            $selfcertification->created_by       = \Auth::user()->creatorId();
            $selfcertification->save();

            return redirect()->route('selfcertification.index')->with('success', __('SelfCertification  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SelfCertification $selfcertification)
    {
        if ($selfcertification->created_by == \Auth::user()->creatorId()) {
            $employee   = $selfcertification->employee->name;

            return view('selfcertification.show', compact('selfcertification', 'employee'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SelfCertification $selfcertification)
    {
        if(\Auth::user()->can('Edit Health And Fitness'))
        {
            $employees = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            if($selfcertification->created_by == \Auth::user()->creatorId())
            {
                return view('selfcertification.edit', compact('selfcertification', 'employees'));
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
    public function update(Request $request, SelfCertification $selfcertification)
    {
         
        if(\Auth::user()->can('Create Health And Fitness'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'certification_date' => 'required',
                    'certification_type' => 'required',
                    'details' => 'nullable',
                    'certification_file' => 'nullable',
                    ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $selfcertification->employee_id      = $request->employee_id;
            $selfcertification->certification_date  = $request->certification_date;
            $selfcertification->certification_type = $request->certification_type;
            $selfcertification->details          = $request->details;
            $selfcertification->certification_file   = $request->certification_file;
            $selfcertification->created_by       = \Auth::user()->creatorId();
            $selfcertification->save();

            return redirect()->route('selfcertification.index')->with('success', __('SelfCertification  successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SelfCertification $selfcertification)
    {
        if(\Auth::user()->can('Delete Health And Fitness'))
        {
            if($selfcertification->created_by == \Auth::user()->creatorId())
            {
                $selfcertification->delete();

                return redirect()->route('selfcertification.index')->with('success', __('Self Certification successfully deleted.'));
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

    public function detailsStore($id, Request $request)
    {
        $selfcertification        = SelfCertification::find($id);
        $selfcertification->details  = $request->detail;
        $selfcertification->save();
        return redirect()->back()->with('success', __('Self Certification Detail successfully saved.'));
    }

    public function fileUpload($id, Request $request)
    {
        $selfcertification = SelfCertification::find($id);
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr' || \Auth::user()->type == 'employee') {
            $request->validate(['file' => 'required']);
            $dir = 'selfcertification_attachment/';
            $files = $request->file->getClientOriginalName();
            $path = Utility::upload_file($request, 'file', $files, $dir, []);
            if ($path['flag'] == 1) {
                $file = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $file                 = HealthFitnessAttachment::create(
                [
                    'selfcertification_id' => $selfcertification->id,
                    'user_id' => \Auth::user()->id,
                    'files' => $files,
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['download']   = route(
                'selfcertification.file.download',
                [
                    $selfcertification->id,
                    $file->id,
                ]
            );
            $return['delete']     = route(
                'selfcertification.file.delete',
                [
                    $selfcertification->id,
                    $file->id,
                ]
            );

            return response()->json($return);
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function fileDownload($id, $file_id)
    {
        $selfcertification = SelfCertification::find($id);
        if ($selfcertification->created_by == \Auth::user()->creatorId()) {
            $file = HealthFitnessAttachment::find($file_id);
            if ($file) {
                $file_path = storage_path('selfcertification_attachment/' . $file->files);

                // $files = $file->files;

                return \Response::download(
                    $file_path,
                    $file->files,
                    [
                        'Content-Length: ' . filesize($file_path),
                    ]
                );
            } else {
                return redirect()->back()->with('error', __('File is not exist.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function fileDelete($id, $file_id)
    {
        if (\Auth::user()->can('Manage Health And Fitness')) {
            $selfcertification = SelfCertification::find($id);
            $file = HealthFitnessAttachment::find($file_id);
            if ($file) {
                $path = storage_path('selfcertification_attachment/' . $file->files);
                if (file_exists($path)) {
                    \File::delete($path);
                }
                $file->delete();

                return redirect()->back()->with('success', __('Attachment successfully deleted!'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('File is not exist.'),
                    ],
                    200
                );
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
