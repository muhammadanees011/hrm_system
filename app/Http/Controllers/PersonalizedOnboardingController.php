<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\EmployeeOnboardingFile;
use App\Models\EmployeeOnboardingQuestion;
use App\Models\EmployeeOnboardingTemplate;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
                    'word_count' => $questionData['word_count'],
                    'employee_onboarding_template_id' => $onboardingTemplate->id,
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
    public function show($id)
    {
        $template = EmployeeOnboardingTemplate::where('id', '=', $id)->with(['questions', 'files', 'branches', 'departments'])->first();
        return view('PersonalizedOnboarding.show', compact('template'));
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

            Storage::delete('uploads/employeeOnboardingTemplate/header/' .$template->image_file_path);
            Storage::delete('uploads/employeeOnboardingTemplate/header/' .$template->video_file_path);
            $template->delete();

            return redirect()->route('personlized-onboarding.index')->with('success', __('Employee Onboarding Template deleted successfully.'));
        } else {
            return redirect()->route('personlized-onboarding.index')->with('error', __('Permission denied.'));
        }
    }
}
