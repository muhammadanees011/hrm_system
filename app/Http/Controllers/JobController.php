<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CustomQuestion;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobApplicationAnswer;
use App\Models\JobApplicationNote;
use App\Models\JobAttachment;
use App\Models\JobCategory;
use App\Models\User;
use App\Models\JobStage;
use App\Models\JobWordCount;
use App\Models\QuestionTemplate;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('Manage Job Category')) {
            $jobs = Job::where('created_by', '=', \Auth::user()->creatorId())->with(['branches'])->get();

            $data['total']     = Job::where('created_by', '=', \Auth::user()->creatorId())->count();
            $data['active']    = Job::where('status', 'active')->where('created_by', '=', \Auth::user()->creatorId())->count();
            $data['in_active'] = Job::where('status', 'in_active')->where('created_by', '=', \Auth::user()->creatorId())->count();

            return view('job.index', compact('jobs', 'data'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $categories = JobCategory::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        // $categories->prepend('--', '');

        $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $branches->prepend('All', 0);

        $status = Job::$status;

        $customQuestion = CustomQuestion::where('created_by', \Auth::user()->creatorId())->get();

        $questionTemplate = QuestionTemplate::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');

        return view('job.create', compact('categories', 'status', 'branches', 'customQuestion', 'questionTemplate'));
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Job')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'branch' => 'required',
                    'department' => 'nullable',
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

            $job                  = new Job();
            $job->title           = $request->title;
            $job->branch          = $request->branch;
            $job->department      = $request->department;
            $job->contract_type   = $request->contract_type;
            $job->category        = $request->category;
            $job->skill           = $request->skill;
            $job->position        = $request->position;
            $job->status          = $request->status;
            $job->start_date      = $request->start_date;
            $job->end_date        = $request->end_date;
            $job->description     = $request->description;
            $job->requirement     = $request->requirement;
            $job->code            = empty(session('job_code')) ? uniqid() : session('job_code');
            $job->applicant       = !empty($request->applicant) ? implode(',', $request->applicant) : '';
            $job->visibility      = !empty($request->visibility) ? implode(',', $request->visibility) : '';
            $job->custom_question = !empty($request->custom_question) ? implode(',', $request->custom_question) : '';
            $job->question_template_id = !empty($request->question_template_id) ? $request->question_template_id : '';
            $job->created_by      = \Auth::user()->creatorId();
            $job->save();

            if (!empty(session('job_code'))) {
                session()->forget('job_code');
            }
            return redirect()->route('job.index')->with('success', __('Job  successfully created.'));
        } else {
            return redirect()->route('job.index')->with('error', __('Permission denied.'));
        }
    }

    public function show(Job $job)
    {
        $status          = Job::$status;
        $job->applicant  = !empty($job->applicant) ? explode(',', $job->applicant) : '';
        $job->visibility = !empty($job->visibility) ? explode(',', $job->visibility) : '';
        $job->skill      = !empty($job->skill) ? explode(',', $job->skill) : '';

        return view('job.show', compact('status', 'job'));
    }

    public function edit(Job $job)
    {

        $categories = JobCategory::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        // $categories->prepend('--', '');

        $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $branches->prepend('All', 0);

        $status = Job::$status;

        $job->applicant       = explode(',', $job->applicant);
        $job->visibility      = explode(',', $job->visibility);
        $job->custom_question = explode(',', $job->custom_question);

        $customQuestion = CustomQuestion::where('created_by', \Auth::user()->creatorId())->get();

        $questionTemplate = QuestionTemplate::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');


        return view('job.edit', compact('categories', 'status', 'branches', 'job', 'customQuestion', 'questionTemplate'));
    }

    public function update(Request $request, Job $job)
    {
        if (\Auth::user()->can('Edit Job')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'branch' => 'required',
                    'department' => 'nullable',
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

            $job->title           = $request->title;
            $job->branch          = $request->branch;
            $job->department      = $request->department;
            $job->contract_type   = $request->contract_type;
            $job->category        = $request->category;
            $job->skill           = $request->skill;
            $job->position        = $request->position;
            $job->status          = $request->status;
            $job->start_date      = $request->start_date;
            $job->end_date        = $request->end_date;
            $job->description     = $request->description;
            $job->requirement     = $request->requirement;
            $job->applicant       = !empty($request->applicant) ? implode(',', $request->applicant) : '';
            $job->visibility      = !empty($request->visibility) ? implode(',', $request->visibility) : '';
            $job->custom_question = !empty($request->custom_question) ? implode(',', $request->custom_question) : '';
            $job->question_template_id = !empty($request->question_template_id) ? $request->question_template_id : '';
            $job->save();

            return redirect()->route('job.index')->with('success', __('Job  successfully updated.'));
        } else {
            return redirect()->route('job.index')->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Job $job)
    {
        $application = JobApplication::where('job', $job->id)->get()->pluck('id');
        JobApplicationNote::whereIn('application_id', $application)->delete();
        JobApplication::where('job', $job->id)->delete();
        $job->delete();

        return redirect()->route('job.index')->with('success', __('Job  successfully deleted.'));
    }

    public function career($id, $lang)
    {
        $jobs = Job::where('created_by', $id)->with(['createdBy', 'branches'])->get();

        \Session::put('lang', $lang);

        \App::setLocale($lang);

        $companySettings['title_text']      = \DB::table('settings')->where('created_by', $id)->where('name', 'title_text')->first();
        $companySettings['footer_text']     = \DB::table('settings')->where('created_by', $id)->where('name', 'footer_text')->first();
        // echo "<pre>";
        // print_r($companySettings['footer_text']);
        // die();
        $companySettings['company_favicon'] = \DB::table('settings')->where('created_by', $id)->where('name', 'company_favicon')->first();
        $companySettings['company_logo']    = \DB::table('settings')->where('created_by', $id)->where('name', 'company_logo')->first();
        $companySettings['metakeyword']     = \DB::table('settings')->where('created_by', $id)->where('name', 'metakeyword')->first();
        $companySettings['metadesc']        = \DB::table('settings')->where('created_by', $id)->where('name', 'metadesc')->first();
        $languages                          = \Utility::languages();

        $currantLang = \Session::get('lang');
        if (empty($currantLang)) {
            $user        = User::find($id);
            $currantLang = !empty($user) && !empty($user->lang) ? $user->lang : 'en';
        }

       
        return view('job.career', compact('companySettings', 'jobs', 'languages', 'currantLang', 'id'));
    }

    public function jobRequirement($code, $lang)
    {
        try {
            $job = Job::where('code', $code)->first();
            if ($job->status == 'in_active') {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Page Not Found.'));
        }

        \Session::put('lang', $lang);

        \App::setLocale($lang);

        $companySettings['title_text']      = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'title_text')->first();
        $companySettings['footer_text']     = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'footer_text')->first();
        $companySettings['company_favicon'] = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'company_favicon')->first();
        $companySettings['company_logo']    = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'company_logo')->first();
        $companySettings['metakeyword']     = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'metakeyword')->first();
        $companySettings['metadesc']        = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'metadesc')->first();
        $languages                          = \Utility::languages();

        $currantLang = \Session::get('lang');
        if (empty($currantLang)) {
            $currantLang = !empty($job->createdBy) ? $job->createdBy->lang : 'en';
        }


        return view('job.requirement', compact('companySettings', 'job', 'languages', 'currantLang'));
    }

    public function jobApply($code, $lang)
    {
        \Session::put('lang', $lang);

        \App::setLocale($lang);
        try {
            $job                                = Job::where('code', $code)->first();
            $companySettings['title_text']      = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'title_text')->first();
            $companySettings['footer_text']     = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'footer_text')->first();
            $companySettings['company_favicon'] = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'company_favicon')->first();
            $companySettings['company_logo']    = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'company_logo')->first();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Page not Found');
        }
        $que = !empty($job->custom_question) ? explode(",", $job->custom_question) : [];

        $questions = CustomQuestion::wherein('id', $que)->get();
        $questionTemplate = QuestionTemplate::where('id', $job->question_template_id)->with(['questions', 'questions.options'])->first();

        $getUserJobCreatedBy = User::where('id', $job->createdBy->id)->first()->created_by;
        $getWordCountCreatedBy = User::where('id', $getUserJobCreatedBy == 0 ? 1 : $getUserJobCreatedBy)->first()->id;
        $wordCounts = JobWordCount::where('created_by', $getWordCountCreatedBy)->get();

        $languages = \Utility::languages();

        $currantLang = \Session::get('lang');
        if (empty($currantLang)) {
            $currantLang = !empty($job->createdBy) ? $job->createdBy->lang : 'en';
        }


        return view('job.apply', compact('companySettings', 'job', 'questions', 'languages', 'currantLang', 'wordCounts', 'questionTemplate'));
    }

    public function jobApplyData(Request $request, $code)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $job = Job::where('code', $code)->first();

        if (!empty($request->profile)) {


            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $dir        = 'uploads/job/profile';

            $image_path = $dir . $filenameWithExt;
            if (\File::exists($image_path)) {
                \File::delete($image_path);
            }
            $url = '';
            $path = \Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);
            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
        }

        if (!empty($request->resume)) {

            $filenameWithExt1 = $request->file('resume')->getClientOriginalName();
            $filename1        = pathinfo($filenameWithExt1, PATHINFO_FILENAME);
            $extension1       = $request->file('resume')->getClientOriginalExtension();
            $fileNameToStore1 = $filename1 . '_' . time() . '.' . $extension1;

            $dir        = 'uploads/job/resume';

            $image_path = $dir . $filenameWithExt1;
            if (\File::exists($image_path)) {
                \File::delete($image_path);
            }
            $url = '';
            $path = \Utility::upload_file($request, 'resume', $fileNameToStore1, $dir, []);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
        }

        // $stage = JobStage::where('created_by',\Auth::user()->creatorId())->first();

        $stage = JobStage::where('created_by', $job->created_by)->first();

        $jobApplication                  = new JobApplication();
        $jobApplication->job             = $job->id;
        $jobApplication->name            = $request->name;
        $jobApplication->email           = $request->email;
        $jobApplication->phone           = $request->phone;
        $jobApplication->profile         = !empty($request->profile) ? $fileNameToStore : '';
        $jobApplication->resume          = !empty($request->resume) ? $fileNameToStore1 : '';
        $jobApplication->cover_letter    = $request->cover_letter;
        $jobApplication->dob             = $request->dob;
        $jobApplication->gender          = $request->gender;
        $jobApplication->address         = $request->address;
        $jobApplication->country         = $request->country;
        $jobApplication->state           = $request->state;
        $jobApplication->stage           = $stage->id;
        $jobApplication->city            = $request->city;
        $jobApplication->zip_code        = $request->zip_code;
        $jobApplication->custom_question = json_encode($request->question);
        $jobApplication->created_by      = $job->created_by;
        $jobApplication->save();

        $requestData = $request->all();
        $questionResponses = collect($requestData)->filter(function ($value, $key) {
            return strpos($key, 'question_') === 0; // Filter keys starting with 'question_'
        })->map(function ($answer, $key) use ($jobApplication) {
            // Extract question ID from the key
            $questionId = (int) substr($key, strlen('question_'));

            // Create a JobApplicationAnswer model instance
            return new JobApplicationAnswer([
                'job_application_id' =>  $jobApplication->id,
                'question_id' => $questionId,
                'answer' => $answer
            ]);
        });

        // Save the question responses in the database
        $questionResponses->each(function ($response) {
            $response->save();
        });
        session()->flash('success', __('Job application successfully sent.'));
        return redirect()->route('career',['id'=>1,'lang'=>'eng']);
        // return redirect()->back()->with('success', __('Job application successfully send.'));
    }
    public function filesUpload(Request $request)
    {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr') {
            $request->validate(['file' => 'required']);
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $dir = 'job_attachment/';
            $path = Utility::upload_file($request, 'file', $fileNameToStore, $dir, []);

            if (empty(session('job_code'))) {
                session(['job_code' => uniqid()]);
            }

            if ($path['flag'] == 1) {
                $file = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $file                 = JobAttachment::create(
                [
                    'job_code' => session('job_code'),
                    'created_by' => \Auth::user()->id,
                    'files' => $fileNameToStore,
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['delete']     = route(
                'job.files.delete',
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
            $file                 = JobAttachment::create(
                [
                    'job_code' => $request->job_code,
                    'created_by' => \Auth::user()->id,
                    'files' => $fileNameToStore,
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['delete']     = route(
                'job.files.delete',
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
            $file = JobAttachment::where('id', $id)->first();
            if ($file) {
                $path = storage_path('job_attachment/' . $file->files);
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
    public function copyJob($id)
    {
        if (\Auth::user()->can('Copy Job')) {

            $jobRefer = Job::where('id', $id)->first();

            $job                  = new Job();
            $job->title           = $jobRefer->title;
            $job->branch          = $jobRefer->branch;
            $job->department      = $jobRefer->department;
            $job->contract_type   = $jobRefer->contract_type;
            $job->category        = $jobRefer->category;
            $job->skill           = $jobRefer->skill;
            $job->position        = $jobRefer->position;
            $job->status          = $jobRefer->status;
            $job->start_date      = $jobRefer->start_date;
            $job->end_date        = $jobRefer->end_date;
            $job->description     = $jobRefer->description;
            $job->requirement     = $jobRefer->requirement;
            $job->code            = uniqid();
            $job->applicant       = $jobRefer->applicant;
            $job->visibility      = $jobRefer->visibility;
            $job->custom_question = $jobRefer->custom_question;
            $job->question_template_id = $jobRefer->question_template_id;
            $job->created_by      = \Auth::user()->creatorId();
            $job->save();

            $jobReferAttachments = JobAttachment::where('job_code', $jobRefer->code)->get();

            if ($jobReferAttachments->isNotEmpty()) {
                foreach ($jobReferAttachments as $attachment) {
                    // Get the original file path
                    $originalFilePath = 'job_attachment/' . $attachment->files;

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

            return redirect()->route('job.index')->with('success', __('Job  successfully copied.'));
        } else {
            return redirect()->route('job.index')->with('error', __('Permission denied.'));
        }
    }
}
