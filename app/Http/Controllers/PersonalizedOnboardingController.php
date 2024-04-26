<?php

namespace App\Http\Controllers;

use App\Mail\OnboardingResponseSubmitted;
use App\Models\Branch;
use App\Models\EmployeeOnboardingAnswer;
use App\Models\EmployeeOnboardingFile;
use App\Models\EmployeeOnboardingFileApproval;
use App\Models\EmployeeOnboardingQuestion;
use App\Models\EmployeeOnboardingTemplate;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class PersonalizedOnboardingController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Job OnBoard Template')) {
            $templates = EmployeeOnboardingTemplate::where('created_by', '=', \Auth::user()->creatorId())->with(['questions', 'files', 'branches', 'departments'])->get();
            return view('PersonalizedOnboarding.index', compact('templates'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function create()
    {
        if (\Auth::user()->can('Create Job OnBoard Template')) {
            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branches->prepend('Select Branch', 0);
            return view('PersonalizedOnboarding.create', compact('branches'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Job OnBoard Template')) {
            // Validate the request data
            $validator = \Validator::make($request->all(), [
                'branch' => 'required',
                'department' => 'required',
                'header_option' => 'required|in:video_url,video_upload,image',
                'header_description' => 'required|string',
                'header_title' => 'required|string',
                'attachments_status' => 'nullable',
                'video_url' => 'nullable|url',
                'video_file' => 'nullable|file|mimes:mp4,mov,avi',
                'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'askinfo_questions.*.name' => 'required|string|max:255',
                'askinfo_questions.*.type' => ['required', Rule::in(['text', 'textarea', 'radio', 'file'])],
                'askinfo_questions.*.word_count' => 'nullable|integer|min:1',
                'askinfo_questions.*.options.*' => 'nullable|string|max:255',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $image = null;
            $video_upload = null;
            $video_url = null;

            if ($request->header_option == 'video_upload') {
                // storing file
                $videofile = $request->file('video_file');
                $filenameWithExt = $request->file('video_file')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('video_file')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir = 'uploads/employeeOnboardingTemplate/header/';
                $videofile->storeAs($dir, $fileNameToStore);
                // Utility::upload_file($request, 'video_file', $fileNameToStore, $dir, []);
                $video_upload   = $fileNameToStore ? $fileNameToStore : null;
            } elseif ($request->header_option == 'image') {
                // storing file
                $filenameWithExt = $request->file('image_file')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('image_file')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir = 'uploads/employeeOnboardingTemplate/header/';
                Utility::upload_file($request, 'image_file', $fileNameToStore, $dir, []);
                $image   = $fileNameToStore ? $fileNameToStore : null;
            } else {
                $video_url = $request->video_url;
            }

            $onboardingTemplate = EmployeeOnboardingTemplate::create([
                'name' => $request->name,
                'branch' => $request->branch,
                'department' => $request->department,
                'header_title' => $request->header_title,
                'header_option' => $request->header_option,
                'video_url' => $video_url,
                'image_file_path' => $image,
                'video_file_path' => $video_upload,
                'header_description' => $request->header_description,
                'attachments_status' => $request->attachments_status == 'on' ? true : false,
                'created_by' => \Auth::user()->creatorId(),
            ]);

            foreach ($request->input('questions') as $questionData) {
                // Create a new Question
                $question = EmployeeOnboardingQuestion::create([
                    'name' => $questionData['name'],
                    'type' => $questionData['type'],
                    'word_count' => null,
                    'employee_onboarding_template_id' => $onboardingTemplate->id,
                    'uuid' => uniqid(),
                ]);
                // If the question type is radio, create options
                if ($questionData['type'] == 'radio') {
                    $questionAsRadio = EmployeeOnboardingQuestion::where('id', $question->id)->first();
                    $questionAsRadio->options = json_encode($questionData['options']);
                    $questionAsRadio->save();
                }
            }

            if ($request->attachments_status == 'on') {
                for ($i = 0; $i < count($request->input('files')); $i++) {
                    $file = $request->file('files')[$i]['file'];
                    $filenameWithExt = $request->file('files')[$i]['file']->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('files')[$i]['file']->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir = 'uploads/employeeOnboardingTemplate/';
                    // Utility::upload_file($request->file('files'), $request->file('files')[$i]['file'], $fileNameToStore, $dir, []);
                    $file->storeAs($dir, $fileNameToStore);

                    $fileName   = $fileNameToStore ? $fileNameToStore : null;

                    // Create a new attachment
                    EmployeeOnboardingFile::create([
                        'employee_onboarding_template_id' => $onboardingTemplate->id,
                        'uuid' => uniqid(),
                        'file_type' => $request->input('files')[$i]['type'],
                        'file_path' => $fileName,
                    ]);
                }
            }

            return redirect()->route('personlized-onboarding.index')->with('success', __('Employee Onboarding Template created successfully.'));
        } else {
            return redirect()->route('personlized-onboarding.index')->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        // dd($employeeOnboardingTemplate);
        if (\Auth::user()->can('Edit Job OnBoard Template')) {
            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branches->prepend('Select Branch', 0);
            $template = EmployeeOnboardingTemplate::where('id', '=', $id)->with(['questions', 'files'])->first();
            return view('PersonalizedOnboarding.edit', compact('template', 'branches'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('Edit Job OnBoard Template')) {
            // Validate the request data
            $validator = \Validator::make($request->all(), [
                'branch' => 'required',
                'department' => 'required',
                'header_option' => 'required|in:video_url,video_upload,image',
                'header_description' => 'required|string',
                'header_title' => 'required|string',
                'attachments_status' => 'nullable',
                'video_url' => 'nullable|url',
                'video_file' => 'nullable|file|mimes:mp4,mov,avi',
                'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'askinfo_questions.*.name' => 'required|string|max:255',
                'askinfo_questions.*.type' => ['required', Rule::in(['text', 'textarea', 'radio', 'file'])],
                'askinfo_questions.*.word_count' => 'nullable|integer|min:1',
                'askinfo_questions.*.options.*' => 'nullable|string|max:255',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $template = EmployeeOnboardingTemplate::where('id', $id)->first();
            $template->name = $request->name;
            $template->branch = $request->branch;
            $template->department = $request->department;
            $template->header_description = $request->header_description;
            $template->header_title = $request->header_title;
            // Check if header_option has changed
            if ($request->header_option !== $template->header_option) {
                // If the new header_option is 'video_upload'
                if ($request->header_option === 'video_upload') {
                    // Remove the existing image_file
                    if ($template->image_file_path) {
                        Storage::delete('uploads/employeeOnboardingTemplate/header/' . $template->image_file_path);
                        $template->image_file_path = null;
                    }

                    // Handle the video_file
                    if ($request->hasFile('video_file')) {
                        // Upload and store the new video_file
                        $videoFileName = $request->file('video_file')->store('uploads/employeeOnboardingTemplate/header/');
                        $template->video_file_path = $videoFileName;
                    }
                }

                // If the new header_option is 'image'
                if ($request->header_option === 'image') {
                    // Remove the existing video_file
                    if ($template->video_file_path) {
                        Storage::delete('uploads/employeeOnboardingTemplate/header/' . $template->video_file_path);
                        $template->video_file_path = null;
                    }

                    // Handle the image_file
                    if ($request->hasFile('image_file')) {
                        // Upload and store the new image_file
                        $imageFileName = $request->file('image_file')->store('uploads/employeeOnboardingTemplate/header/');
                        $template->image_file_path = $imageFileName;
                    }
                }

                // Update the header_option field
                $template->header_option = $request->header_option;
            } else {
                // If header_option remains the same, handle only file updates
                if ($request->header_option === 'video_upload' && $request->hasFile('video_file')) {
                    // Remove the existing video_file
                    if ($template->video_file_path) {
                        Storage::delete('uploads/employeeOnboardingTemplate/header/' . $template->video_file_path);
                        $template->video_file_path = null;
                    }

                    // Upload and store the new video_file
                    $videoFileName = $request->file('video_file')->store('uploads/employeeOnboardingTemplate/header/');
                    $template->video_file_path = $videoFileName;
                }

                if ($request->header_option === 'image' && $request->hasFile('image_file')) {
                    // Remove the existing image_file
                    if ($template->image_file_path) {
                        Storage::delete('uploads/employeeOnboardingTemplate/header/' . $template->image_file_path);
                        $template->image_file_path = null;
                    }

                    // Upload and store the new image_file
                    $imageFileName = $request->file('image_file')->store('uploads/employeeOnboardingTemplate/header/');
                    $template->image_file_path = $imageFileName;
                }
            }
            $template->save();

            foreach ($request->input('questions') as $questionUUID => $questionData) {
                $questionExist = EmployeeOnboardingQuestion::where('uuid', $questionUUID)->first();
                if ($questionExist) {
                    $questionExist->name = $questionData['name'];
                    $questionExist->type = $questionData['type'];
                    if ($questionExist->type == 'radio') {
                        $questionExist->options = json_encode($questionData['options']);
                    }
                    $questionExist->save();
                } else {
                    // Create a new Question
                    $question = EmployeeOnboardingQuestion::create([
                        'name' => $questionData['name'],
                        'type' => $questionData['type'],
                        'word_count' => null,
                        'employee_onboarding_template_id' => $template->id,
                        'uuid' => uniqid(),
                    ]);
                    // If the question type is radio, create options
                    if ($questionData['type'] == 'radio') {
                        $questionAsRadio = EmployeeOnboardingQuestion::where('id', $question->id)->first();
                        $questionAsRadio->options = json_encode($questionData['options']);
                        $questionAsRadio->save();
                    }
                }
            }

            if ($request->attachments_status == 'on' && $request->input('files')) {
                foreach ($request->input('files') as $fileUUID => $fileData) {
                    $fileExist = EmployeeOnboardingFile::where('uuid', $fileUUID)->first();
                    if ($fileExist) {
                        $fileExist->file_type = $fileData['type'];
                        $fileExist->save();
                    }
                }
            }

            if ($request->attachments_status == 'on' && $request->file('files')) {
                for ($i = 0; $i < count($request->file('files')); $i++) {
                    $file = $request->file('files')[$i]['file'];
                    $filenameWithExt = $request->file('files')[$i]['file']->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('files')[$i]['file']->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir = 'uploads/employeeOnboardingTemplate/';
                    // Utility::upload_file($request->file('files'), $request->file('files')[$i]['file'], $fileNameToStore, $dir, []);
                    $file->storeAs($dir, $fileNameToStore);

                    $fileName   = $fileNameToStore ? $fileNameToStore : null;

                    // Create a new attachment
                    EmployeeOnboardingFile::create([
                        'employee_onboarding_template_id' => $template->id,
                        'uuid' => uniqid(),
                        'file_type' => $request->input('files')[$i]['type'],
                        'file_path' => $fileName,
                    ]);
                }
            }

            return redirect()->route('personlized-onboarding.index')->with('success', __('Employee Onboarding Template updated successfully.'));
        } else {
            return redirect()->route('personlized-onboarding.edit', $id)->with('error', __('Permission denied.'));
        }
    }

    public function show($id, $jobApplicationId = null)
    {
        $template = EmployeeOnboardingTemplate::where('id', '=', $id)->with(['questions', 'files', 'branches', 'departments'])->first();
        return view('PersonalizedOnboarding.show', compact('template', 'jobApplicationId'));
    }

    public function destroyQuestion($id)
    {
        EmployeeOnboardingQuestion::where('id', $id)->first()->delete();
        return response()->json('sccuessfully deleted!', 200);
    }
    public function destroyFile($id)
    {
        $file = EmployeeOnboardingFile::where('id', $id)->first();
        Storage::delete('uploads/employeeOnboardingTemplate/' . $file->file_path);
        $file->delete();
        return response()->json('sccuessfully deleted!', 200);
    }
    public function destroy($id)
    {
        if (\Auth::user()->can('Delete Job OnBoard Template')) {
            $template = EmployeeOnboardingTemplate::where('id', $id)->first();
            if ($template->questions) {
                $template->questions()->delete();
            }

            if ($template->files) {
                $files = $template->files;
                foreach ($files as $file) {
                    Storage::delete('uploads/employeeOnboardingTemplate/' . $file->file_path);
                    $file->delete();
                }
            }

            Storage::delete('uploads/employeeOnboardingTemplate/header/' . $template->image_file_path);
            Storage::delete('uploads/employeeOnboardingTemplate/header/' . $template->video_file_path);
            $template->delete();

            return redirect()->route('personlized-onboarding.index')->with('success', __('Employee Onboarding Template deleted successfully.'));
        } else {
            return redirect()->route('personlized-onboarding.index')->with('error', __('Permission denied.'));
        }
    }

    public function responseStore(Request $request)
    {
        $templateId = $request->template_id;
        $jobApplicationId = $request->job_application_id;

        if ($request->question) {
            foreach ($request->input('question') as $questionId => $answer) {
                EmployeeOnboardingAnswer::create([
                    'employee_onboarding_template_id' => $templateId,
                    'job_application_id' => $jobApplicationId,
                    'employee_onboarding_question_id' => $questionId,
                    'answer' => $answer,
                ]);
            }
        }

        if ($request->file('question')) {
            foreach ($request->file('question') as $questionId => $responseFile) {
                $file = $responseFile;
                $filenameWithExt = $responseFile->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $responseFile->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir = 'uploads/employeeOnboardingTemplate/responseFiles/';
                $file->storeAs($dir, $fileNameToStore);

                $fileName   = $fileNameToStore ? $fileNameToStore : null;
                EmployeeOnboardingAnswer::create([
                    'employee_onboarding_template_id' => $templateId,
                    'job_application_id' => $jobApplicationId,
                    'employee_onboarding_question_id' => $questionId,
                    'answer' => $fileName,
                ]);
            }
        }

        if ($request->file_approvals) {
            foreach ($request->file_approvals as $fileId => $status) {
                EmployeeOnboardingFileApproval::create([
                    'employee_onboarding_template_id' => $templateId,
                    'job_application_id' => $jobApplicationId,
                    'employee_onboarding_file_id' => $fileId,
                    'approve_status' => $status,
                ]);
            }
        }

        $jobCreatedBy = JobApplication::where('id', $jobApplicationId)->first()->created_by;
        $jobCreatedByEmail = User::where('id', $jobCreatedBy)->first()->email;

        $email = new OnboardingResponseSubmitted(route('onboarding.personalized.response.show', $jobApplicationId));
        Mail::to($jobCreatedByEmail)->send($email);

        return redirect()->back()->with('success', __('Thank you for submitting your Onboarding details.'));
    }
    public function responseShow($jobApplicationId)
    {
        $askDetails = EmployeeOnboardingAnswer::where('job_application_id', $jobApplicationId)->with('onboardingQuestion')->get()->sortBy('id');
        $fileApprovals = EmployeeOnboardingFileApproval::where('job_application_id', $jobApplicationId)
            ->whereHas('onboardingFile', function ($query) {
                $query->where('file_type', 'read_and_approve');
            })
            ->get()
            ->sortBy('id');


        if (count($askDetails) > 0 || count($fileApprovals) > 0) {
            return view('PersonalizedOnboarding.response', compact('askDetails', 'fileApprovals'));
        } else {
            return redirect()->back()->with('error', __('Response not submitted at the moment.'));
        }
    }
}
