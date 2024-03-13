<?php

namespace App\Http\Controllers;

use App\Models\GPNote;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\HealthFitnessAttachment;
use App\Models\Utility;

class GPNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Health And Fitness'))
        {
            $gpnotes = GPNote::where('created_by', '=', \Auth::user()->creatorId())->get();
            
            $total_gpnotes = GPNote::where('created_by', '=', \Auth::user()->creatorId())->count();
            $curr_month  = GPNote::where('created_by', '=', \Auth::user()->creatorId())->whereMonth('created_at', '=', date('m'))->count();
            $curr_week   = GPNote::where('created_by', '=', \Auth::user()->creatorId())->whereBetween(
                'created_at',
                [
                    \Carbon\Carbon::now()->startOfWeek(),
                    \Carbon\Carbon::now()->endOfWeek(),
                ]
            )->count();
            $last_30days = GPNote::where('created_by', '=', \Auth::user()->creatorId())->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->count();

            $cnt_gpnote                = [];
            $cnt_gpnote['total']       = $total_gpnotes;
            $cnt_gpnote['this_month']  = $curr_month;
            $cnt_gpnote['this_week']   = $curr_week;
            $cnt_gpnote['last_30days'] = $last_30days;

            return view('gpnote.index', compact('gpnotes','cnt_gpnote'));
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
            return view('gpnote.create', compact('employees'));
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
                    'assessment_date' => 'required',
                    'presenting_complaint' => 'required',
                    'assessment' => 'nullable',
                    'follow_up_date' => 'required',
                    'prescription_file' => 'nullable',
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $gpnote                   = new GPNote();
            $gpnote->employee_id      = $request->employee_id;
            $gpnote->assessment_date  = $request->assessment_date;
            $gpnote->presenting_complaint = $request->presenting_complaint;
            $gpnote->assessment       = $request->assessment;
            $gpnote->follow_up_date   = $request->follow_up_date;
            $gpnote->created_by       = \Auth::user()->creatorId();
            $gpnote->save();

            return redirect()->route('gpnote.index')->with('success', __('GPNote  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GPNote $gpnote)
    {
        if ($gpnote->created_by == \Auth::user()->creatorId()) {
            $employee   = $gpnote->employee->name;

            return view('gpnote.show', compact('gpnote', 'employee'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GPNote $gpnote)
    {
        if(\Auth::user()->can('Edit Health And Fitness'))
        {
            $employees        = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            if($gpnote->created_by == \Auth::user()->creatorId())
            {
                return view('gpnote.edit', compact('gpnote', 'employees'));
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
    public function update(Request $request, GPNote $gpnote)
    {
        if(\Auth::user()->can('Create Health And Fitness'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'assessment_date' => 'required',
                    'presenting_complaint' => 'required',
                    'assessment' => 'nullable',
                    'follow_up_date' => 'required',
                    'prescription_file' => 'nullable',
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $gpnote->employee_id      = $request->employee_id;
            $gpnote->assessment_date  = $request->assessment_date;
            $gpnote->presenting_complaint = $request->presenting_complaint;
            $gpnote->assessment       = $request->assessment;
            $gpnote->follow_up_date   = $request->follow_up_date;
            $gpnote->save();

            return redirect()->route('gpnote.index')->with('success', __('GPNote  successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GPNote $gpnote)
    {   
        if(\Auth::user()->can('Delete Health And Fitness'))
        {
            if($gpnote->created_by == \Auth::user()->creatorId())
            {
                $gpnote->delete();

                return redirect()->route('gpnote.index')->with('success', __('GPNote successfully deleted.'));
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

    
    public function assessmentDetailsStore($id, Request $request)
    {
        $gpnote        = GPNote::find($id);
        $gpnote->plan  = $request->detail;
        $gpnote->save();
        return redirect()->back()->with('success', __('GPNote Detail successfully saved.'));
        
    }

    
    public function fileUpload($id, Request $request)
    {
        $gpnote = GPNote::find($id);
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr' || \Auth::user()->type == 'employee') {
            $request->validate(['file' => 'required']);
            $dir = 'gpnote_attachment/';
            $files = $request->file->getClientOriginalName();
            $path = Utility::upload_file($request, 'file', $files, $dir, []);
            if ($path['flag'] == 1) {
                $file = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $file                 = HealthFitnessAttachment::create(
                [
                    'gpnotes_id' => $gpnote->id,
                    'user_id' => \Auth::user()->id,
                    'files' => $files,
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['download']   = route(
                'gpnote.file.download',
                [
                    $gpnote->id,
                    $file->id,
                ]
            );
            $return['delete']     = route(
                'gpnote.file.delete',
                [
                    $gpnote->id,
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
        $gpnote = GPNote::find($id);
        if ($gpnote->created_by == \Auth::user()->creatorId()) {
            $file = HealthFitnessAttachment::find($file_id);
            if ($file) {
                $file_path = storage_path('gpnote_attachment/' . $file->files);

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
            $gpnote = GPNote::find($id);
            $file = HealthFitnessAttachment::find($file_id);
            if ($file) {
                $path = storage_path('gpnote_attachment/' . $file->files);
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
