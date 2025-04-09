<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobCategory;
use App\Models\JobRequisition;
use App\Models\Branch;
use App\Models\Job;

class JobRequisitionController extends Controller
{
    //

    public function index()
    {
        // if (\Auth::user()->can('Manage Job Category')) {
        if(\Auth::user()->type=='manager' || \Auth::user()->type=='employee'){
            $jobs = JobRequisition::where('created_by', '=', \Auth::user()->creatorId())->with(['branches'])->get();
        }else{
            $jobs = JobRequisition::get();
        }
            return view('job.job_requisition_list', compact('jobs'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function create(){
        $categories = JobCategory::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        // $categories->prepend('--', '');
        $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $branches->prepend('All', 0);
        $status = Job::$status;
        // $id=\Auth::user()->id;
        return view('job.job_requisition', compact('categories', 'status', 'branches'));
    }

    public function store(Request $request)
    {
        // return $request;
        // if (\Auth::user()->can('Create Job')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'requester_name'           => 'required',
                    'email'                    => 'required|email',
                    'phone'                    => 'required',
                    'branch'                   => 'required',
                    'department'               => 'nullable',
                    'request_date'             => 'required|date',
                    'title'                => 'required',
                    'no_of_positions'                 => 'required',
                    'position_type'                 => 'required|in:new,replacement',
                    'previous_employee'        => 'required',
                    'work_location'            => 'required',
                    'remote_work'              => 'required|in:yes,no',
                    'work_Schedule'            => 'required',
                    'start_date'               => 'required|date',
                    'employement_type'         => 'required|in:Full-time,Part-time,Contract,Temporary',
                    'experience_required'      => 'required|integer|min:0',
                    'salary_range'             => 'required|numeric|min:0',
                    'job_grade'                => 'required',
                    'budget_code'              => 'required',
                    'budgeted'                 => 'required|in:yes,no',
                    'hiring_manager'           => 'required',
                    'hr_bussiness_partner'     => 'required',
                    'budget_approval'          => 'required',
                    'executive_approval'       => 'required',
                    'comments'                 => 'nullable',
                    'position_summary'         => 'nullable',
                    'key_responsibilities'     => 'nullable',
                    'required_qualifications'  => 'nullable',
                    'preferred_qualifications' => 'nullable',
                ]
                
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $job = new JobRequisition();
            $job->requester_name           = $request->requester_name;
            $job->email                    = $request->email;
            $job->phone                    = $request->phone;
            $job->branch                   = $request->branch;
            $job->department               = $request->department;
            $job->request_date             = $request->request_date;
            $job->job_title                = $request->title;
            $job->previous_employee        = $request->previous_employee;
            $job->work_location            = $request->work_location;
            $job->remote_work              = $request->remote_work;
            $job->work_Schedule            = $request->work_Schedule;
            $job->start_date               = $request->start_date;
            $job->employement_type         = $request->employement_type;
            $job->position_type                = $request->position_type;
            $job->no_of_positions                = $request->no_of_positions;
            $job->experience_required      = $request->experience_required;
            $job->salary_range             = $request->salary_range;
            $job->job_grade                = $request->job_grade;
            $job->budget_code              = $request->budget_code;
            $job->budgeted                 = $request->budgeted;
            $job->hiring_manager           = $request->hiring_manager;
            $job->hr_bussiness_partner     = $request->hr_bussiness_partner;
            $job->budget_approval          = $request->budget_approval;
            $job->executive_approval       = $request->executive_approval;
            $job->comments                 = $request->comments;
            $job->position_summary         = $request->position_summary;
            $job->key_responsibilities     = $request->key_responsibilities;
            $job->required_qualifications  = $request->required_qualifications;
            $job->preferred_qualifications = $request->preferred_qualifications;
            $job->status                   = 'Pending';
            $job->created_by               = \Auth::user()->creatorId();
            $job->save();

            return redirect()->route('job-requisition.index')->with('success', __('Job Requisition successfully created.'));
        // } else {
        //     return redirect()->route('job.index')->with('error', __('Permission denied.'));
        // }
    }

    public function approve($id){
        $job = JobRequisition::find($id);
        $job->status='Approved';
        $job->save();
        return redirect()->route('job.create')->with('success', __('Job Requisition successfully approved.'));
    }

    public function destroy(JobRequisition $job)
    {
        $job->delete();
        return redirect()->route('job.index')->with('success', __('Job Requisition successfully deleted.'));
    }
}
