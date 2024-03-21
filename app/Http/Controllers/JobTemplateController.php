<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CustomQuestion;
use App\Models\Job;
use App\Models\JobAttachment;
use App\Models\JobCategory;
use App\Models\JobTemplate;
use App\Models\JobTemplateDetail;
use App\Models\JobTemplateDetailAttachment;
use App\Models\QuestionTemplate;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobTemplateController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Job Template')) {
            $jobTemplates = JobTemplate::where('created_by', '=', \Auth::user()->creatorId())->get();

            $data['total']     = JobTemplateDetail::where('created_by', '=', \Auth::user()->creatorId())->count();
            $data['active']    = JobTemplateDetail::where('status', 'active')->where('created_by', '=', \Auth::user()->creatorId())->count();
            $data['in_active'] = JobTemplateDetail::where('status', 'in_active')->where('created_by', '=', \Auth::user()->creatorId())->count();

            return view('jobTemplate.index', compact('jobTemplates', 'data'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $categories = JobCategory::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        $categories->prepend('--', '');

        $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $branches->prepend('All', 0);

        $status = JobTemplateDetail::$status;

        $customQuestion = CustomQuestion::where('created_by', \Auth::user()->creatorId())->get();

        $questionTemplate = QuestionTemplate::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');

        return view('jobTemplate.create', compact('categories', 'status', 'branches', 'customQuestion', 'questionTemplate'));
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Job Template')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'job_template' => 'required',
                    'title' => 'required',
                    'branch' => 'required',
                    'contract_type' => 'required',
                    'category' => 'required',
                    'skill' => 'required',
                    'position' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'description' => 'required',
                    'requirement' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $jobTemplate                  = new JobTemplate();
            $jobTemplate->title           = $request->job_template;
            $jobTemplate->created_by      = \Auth::user()->creatorId();
            $jobTemplate->save();

            $jobTemplateDetail                  = new JobTemplateDetail();
            $jobTemplateDetail->job_template_id           = $jobTemplate->id;
            $jobTemplateDetail->title           = $request->title;
            $jobTemplateDetail->branch          = $request->branch;
            $jobTemplateDetail->department      = $request->department;
            $jobTemplateDetail->contract_type   = $request->contract_type;
            $jobTemplateDetail->category        = $request->category;
            $jobTemplateDetail->skill           = $request->skill;
            $jobTemplateDetail->position        = $request->position;
            $jobTemplateDetail->status          = $request->status;
            $jobTemplateDetail->start_date      = $request->start_date;
            $jobTemplateDetail->end_date        = $request->end_date;
            $jobTemplateDetail->description     = $request->description;
            $jobTemplateDetail->requirement     = $request->requirement;
            $jobTemplateDetail->code            = empty(session('job_template_code')) ? uniqid() : session('job_template_code');
            $jobTemplateDetail->applicant       = !empty($request->applicant) ? implode(',', $request->applicant) : '';
            $jobTemplateDetail->visibility      = !empty($request->visibility) ? implode(',', $request->visibility) : '';
            $jobTemplateDetail->custom_question = !empty($request->custom_question) ? implode(',', $request->custom_question) : '';
            $jobTemplateDetail->question_template_id = !empty($request->question_template_id) ? $request->question_template_id : '';
            $jobTemplateDetail->created_by      = \Auth::user()->creatorId();
            $jobTemplateDetail->save();

            if (!empty(session('job_template_code'))) {
                session()->forget('job_template_code');
            }
            return redirect()->route('job-template.index')->with('success', __('Job Template successfully created.'));
        } else {
            return redirect()->route('job-template.index')->with('error', __('Permission denied.'));
        }
    }

    public function show(JobTemplate $jobTemplate)
    {
        dd($jobTemplate);
        // $status          = Job::$status;
        // $job->applicant  = !empty($job->applicant) ? explode(',', $job->applicant) : '';
        // $job->visibility = !empty($job->visibility) ? explode(',', $job->visibility) : '';
        // $job->skill      = !empty($job->skill) ? explode(',', $job->skill) : '';

        // return view('job.show', compact('status', 'job'));
    }

    public function edit(JobTemplate $jobTemplate)
    {

        $job = JobTemplateDetail::where('job_template_id', $jobTemplate->id)->first();

        $categories = JobCategory::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        $categories->prepend('--', '');

        $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $branches->prepend('All', 0);

        $status = JobTemplateDetail::$status;

        $job->applicant       = explode(',', $job->applicant);
        $job->visibility      = explode(',', $job->visibility);
        $job->custom_question = explode(',', $job->custom_question);

        $customQuestion = CustomQuestion::where('created_by', \Auth::user()->creatorId())->get();

        $questionTemplate = QuestionTemplate::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');


        return view('jobTemplate.edit', compact('jobTemplate', 'categories', 'status', 'branches', 'job', 'customQuestion', 'questionTemplate'));
    }

    public function update(Request $request, JobTemplate $jobTemplate)
    {
        if (\Auth::user()->can('Edit Job Template')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'job_template' => 'required',
                    'title' => 'required',
                    'branch' => 'required',
                    'contract_type' => 'required',
                    'category' => 'required',
                    'skill' => 'required',
                    'position' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'description' => 'required',
                    'requirement' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $jobTemplate->title = $request->job_template;
            $jobTemplate->save();

            $jobTemplateDetails = JobTemplateDetail::where('job_template_id', $jobTemplate->id)->first();

            $jobTemplateDetails->title           = $request->title;
            $jobTemplateDetails->branch          = $request->branch;
            $jobTemplateDetails->department      = $request->department;
            $jobTemplateDetails->contract_type   = $request->contract_type;
            $jobTemplateDetails->category        = $request->category;
            $jobTemplateDetails->skill           = $request->skill;
            $jobTemplateDetails->position        = $request->position;
            $jobTemplateDetails->status          = $request->status;
            $jobTemplateDetails->start_date      = $request->start_date;
            $jobTemplateDetails->end_date        = $request->end_date;
            $jobTemplateDetails->description     = $request->description;
            $jobTemplateDetails->requirement     = $request->requirement;
            $jobTemplateDetails->applicant       = !empty($request->applicant) ? implode(',', $request->applicant) : '';
            $jobTemplateDetails->visibility      = !empty($request->visibility) ? implode(',', $request->visibility) : '';
            $jobTemplateDetails->custom_question = !empty($request->custom_question) ? implode(',', $request->custom_question) : '';
            $jobTemplateDetails->question_template_id = !empty($request->question_template_id) ? $request->question_template_id : '';
            $jobTemplateDetails->save();

            return redirect()->route('job-template.index')->with('success', __('Job Template successfully updated.'));
        } else {
            return redirect()->route('job-template.index')->with('error', __('Permission denied.'));
        }
    }

    public function destroy(JobTemplate $jobTemplate)
    {
        $jobTemplateDetails = JobTemplateDetail::where('job_template_id', $jobTemplate->id)->first();
        $jobTemplateDetailAttachments = JobTemplateDetailAttachment::where('job_template_code', $jobTemplateDetails->code)->get();
        foreach ($jobTemplateDetailAttachments as $file) {
            $filePath = storage_path('job_template_attachment/' . $file->files);

            if (file_exists($filePath)) {
                \File::delete($filePath);
            }

            $file->delete();
        }
        $jobTemplateDetails->delete();
        $jobTemplate->delete();

        return redirect()->route('job-template.index')->with('success', __('Job  successfully deleted.'));
    }

    public function makeJob($id)
    {
        if (\Auth::user()->can('Make Job')) {
            $jobTemplate = JobTemplate::where('id', $id)->first();
            $jobTemplateDetails = JobTemplateDetail::where('job_template_id', $jobTemplate->id)->first();

            $job                  = new Job();
            $job->title           = $jobTemplateDetails->title;
            $job->branch          = $jobTemplateDetails->branch;
            $job->department      = $jobTemplateDetails->department;
            $job->contract_type   = $jobTemplateDetails->contract_type;
            $job->category        = $jobTemplateDetails->category;
            $job->skill           = $jobTemplateDetails->skill;
            $job->position        = $jobTemplateDetails->position;
            $job->status          = $jobTemplateDetails->status;
            $job->start_date      = $jobTemplateDetails->start_date;
            $job->end_date        = $jobTemplateDetails->end_date;
            $job->description     = $jobTemplateDetails->description;
            $job->requirement     = $jobTemplateDetails->requirement;
            $job->code            = uniqid();
            $job->applicant       = $jobTemplateDetails->applicant;
            $job->visibility      = $jobTemplateDetails->visibility;
            $job->custom_question = $jobTemplateDetails->custom_question;
            $job->question_template_id = $jobTemplateDetails->question_template_id;
            $job->created_by      = \Auth::user()->creatorId();
            $job->save();

            $jobTemplateDetailAttachment = JobTemplateDetailAttachment::where('job_template_code', $jobTemplateDetails->code)->get();

            if ($jobTemplateDetailAttachment->isNotEmpty()) {
                foreach ($jobTemplateDetailAttachment as $attachment) {
                    // Get the original file path
                    $originalFilePath = 'job_template_attachment/' . $attachment->files;

                    // Generate the destination file path
                    $newFileName = time() . $attachment->files;
                    $destinationFilePath = 'job_attachment/' . $newFileName;

                    // Copy the file to the new location
                    Storage::copy($originalFilePath, $destinationFilePath);

                    JobAttachment::create(
                        [
                            'job_code' => $job->code,
                            'created_by' => \Auth::user()->id,
                            'files' => $newFileName,
                        ]
                    );
                }
            }
            return redirect()->route('job.index')->with('success', __('Job successfully created by using template.'));
        } else {
            return redirect()->route('job.index')->with('error', __('Permission denied.'));
        }
    }


    public function filesUpload(Request $request)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr') {
            $request->validate(['file' => 'required']);
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $dir = 'job_template_attachment/';
            $path = Utility::upload_file($request, 'file', $fileNameToStore, $dir, []);

            if (empty(session('job_template_code'))) {
                session(['job_template_code' => uniqid()]);
            }

            if ($path['flag'] == 1) {
                $file = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $file                 = JobTemplateDetailAttachment::create(
                [
                    'job_template_code' => session('job_template_code'),
                    'created_by' => \Auth::user()->id,
                    'files' => $fileNameToStore,
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['delete']     = route(
                'job-template.files.delete',
                [
                    $file->id,
                    0
                ]
            );

            return response()->json($return);
        }
    }

    public function editFilesUpload(Request $request)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr') {
            $request->validate(['file' => 'required']);
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $dir = 'job_attachment/';
            $path = Utility::upload_file($request, 'file', $fileNameToStore, $dir, []);

            if ($path['flag'] == 1) {
                $file = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $file                 = JobTemplateDetailAttachment::create(
                [
                    'job_template_code' => $request->job_code,
                    'created_by' => \Auth::user()->id,
                    'files' => $fileNameToStore,
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['delete']     = route(
                'job-template.files.delete',
                [
                    $file->id,
                    0
                ]
            );

            return response()->json($return);
        }
    }
    public function fileDelete($id, $redirect)
    {
        if (\Auth::user()->can('Delete Attachment')) {
            $file = JobTemplateDetailAttachment::where('id', $id)->first();
            if ($file) {
                $path = storage_path('job_template_attachment/' . $file->files);
                if (file_exists($path)) {
                    \File::delete($path);
                }
                $file->delete();
                if ($redirect == 1) {
                    return redirect()->back()->with('success', __('Attachment successfully deleted!'));
                } else {
                    return response()->json(
                        [
                            'is_success' => true,
                            'error' => __('Attachment successfully deleted!'),
                        ],
                        200
                    );
                }
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
