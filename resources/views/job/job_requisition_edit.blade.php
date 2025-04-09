@php
$chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

@extends('layouts.admin')
@section('page-title')
{{ __('Create Job Requisition') }}
@endsection
@push('css-page')
<link href="{{ asset('public/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
@endpush
@push('script-page')
<!-- <script src='{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}'></script>  -->
<script src="{{ asset('public/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>

<script>
    var e = $('[data-toggle="tags"]');
    e.length && e.each(function() {
        $(this).tagsinput({
            tagClass: "badge badge-primary"
        })
    });
</script>
<script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>

<script>

    $(document).on('change', 'select[name=branch]', function() {
        var branch_id = $(this).val();
        console.log(branch_id);
        getDepartment(branch_id);
    });

    function getDepartment(bid) {
        console.log("CSRF Token: {{ csrf_token() }}");

        $.ajax({
            url: '{{ route('monthly.getdepartment') }}',
            type: 'POST',
            data: {
                "branch_id": bid,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('.department_id').empty();
                var emp_select = `<select class="form-control department_id" name="department" placeholder="Select Department"></select>`;
                $('.department_div').html(emp_select);

                $('.department_id').append('<option value=""> {{ __('Select Department') }} </option>');
                $.each(data, function(key, value) {
                    $('.department_id').append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    }
</script>
@endpush

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('job.index') }}">{{ __('Manage Job') }}</a></li>
<li class="breadcrumb-item">{{ __('Create Job Requisition') }}</li>
@endsection


@section('content')




<style>

.employees-actions{
    display:flex;
    justify-content:end;
}

.dropdown {
    position: relative;
    display: inline-block;
  }
  
  .dropbtn {
    background-color: orange;
    color: white;
    padding: 6px;
    padding-left: 1.3rem;
    padding-right: 1.3rem;
    font-size: 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
  }
  
  .dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    right: 1px;
    font-size: 12px;
  }
  
  .dropdown-content a {
    color: black !important;
    padding: 5px 12px;
    text-decoration: none;
    display: block;
  }
  
  .dropdown-content a:hover {
    background-color: orange;
    color:white !important;
}
  .dropdown:hover .dropdown-content {display: block;}
  .dropdown:hover .dropbtn {color: white;}
</style>

<div class="employees-actions">
    <div class="employees-nav me-1 mb-2">
        <div class="nav-titles">
            <div class="dropdown">
            <button class="dropbtn">Recruitment &#9660;</button>
            <div class="dropdown-content">
                <a href="{{ route('job.index') }}">{{ __("Jobs") }} </a>
                <a href="{{ route('job-requisition.index') }}">{{ __("Job Requisition") }} </a>
                <a href="{{ route('job.create') }}">{{ __("Job Create") }} </a>
                <a href="{{ route('job-template.index') }}">{{ __('Job Templates') }}</a>
                <a href="{{ route('job-application.index') }}">{{ __('Job Application') }}</a>
                <a href="{{ route('job.on.board') }}">{{ __('Job On-Boarding') }}</a>
                <a href="{{ route('personlized-onboarding.index') }}">{{ __('Job On-Boarding Templates') }}</a>
                <a href="{{ route('question-template.index') }}">{{ __('Question Templates') }}</a>
                <a href="{{ route('custom-question.index') }}">{{ __('Custom Question') }}</a>
                <a href="{{ route('interview-schedule.index') }}">{{ __('Interview Schedule') }}</a>
                <a href="{{ route('config-word-count.index') }}">{{ __('Config Word Count') }}</a>
                <a href="{{ route('career', [\Auth::user()->creatorId(), \Auth::user()->lang]) }}">{{ __('Job Opening') }}</a>
            </div>
            </div>
        </div>
    </div>
</div>






@if ($chatgpt == 'on')
<div class="text-end">
    <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['job']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
        <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
    </a>
</div>
@endif

{{ Form::open(['url' => 'job-requisition', 'method' => 'post']) }}
<div class="row mt-3">
    <div class="col-md-12 ">
        <div class="card card-fluid job-card">
            <div class="card-body ">
                <div class="row">
                    <h4>Requestor Information</h4>
                    <div class="form-group col-md-4">
                        {!! Form::label('requester_name', __('Requestor Name'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('requester_name', old('requester_name'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter job requester name',
                        ]) !!}
                    </div>
                    
                    <div class="form-group col-md-4">
                        {!! Form::label('email', __('Email'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('email', old('email'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter email',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('phone', __('Phone'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('phone', old('phone'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter phone',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('branch', __('Branch'), ['class' => 'col-form-label']) !!}
                        {{ Form::select('branch', $branches, null, ['class' => 'form-control select2', 'required' => 'required']) }}
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::label('department_id', __('Department'), ['class' => 'col-form-label']) }}
                        <select class="form-control department_id" name="department" id="department_id" placeholder="Select Department">
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('request_date', __('Date of request'), ['class' => 'col-form-label']) !!}
                        {!! Form::date('request_date', old('request_date'), [
                        'class' => 'form-control current_date',
                        'autocomplete' => 'off',
                        'placeholder' => 'Select request date',
                        ]) !!}
                    </div>

                    <h4>Position Details</h4>

                    <div class="form-group col-md-12">
                        {!! Form::label('title', __('Job Title'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('title', old('title'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter job title',
                        ]) !!}
                    </div>
                    
                    <div class="form-group col-md-12">
                        <div class="my-2 d-flex">
                            <div class="form-check custom-checkbox me-5">
                                <input type="radio" class="form-check-input" name="position_type" value="new" id="position_new">
                                <label class="form-check-label" for="position_new">{{ __('New Position') }}</label>
                            </div>
                            <div class="form-check custom-checkbox">
                                <input type="radio" class="form-check-input" name="position_type" value="replacement" id="position_replacement">
                                <label class="form-check-label" for="position_replacement">{{ __('Replacement Position') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('previous_employee', __('If replacement, previous employee'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('previous employee', old('previous_employee'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter previous employee',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('work_location', __('Work Location'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('work_location', old('work_location'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter Work Location',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4 mt-4">
                        <h5>Remote Work Allowed?</h5>
                        <div class="my-2 d-flex">
                            <div class="form-check custom-checkbox me-5">
                                <input type="radio" class="form-check-input" name="remote_work" value="yes" id="remote_work_yes">
                                <label class="form-check-label" for="remote_work_yes">{{ __('Yes') }}</label>
                            </div>
                            <div class="form-check custom-checkbox">
                                <input type="radio" class="form-check-input" name="remote_work" value="no" id="remote_work_no">
                                <label class="form-check-label" for="remote_work_no">{{ __('No') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('work_Schedule', __('Work Schedule'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('work_Schedule', old('work_Schedule'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter Work Schedule',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('start_date', __('Expected Start Date'), ['class' => 'col-form-label']) !!}
                        {!! Form::date('start_date', old('start_date'), [
                        'class' => 'form-control current_date',
                        'autocomplete' => 'off',
                        'placeholder' => 'Select start date',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4">
                        <label for="employement_type" class="col-form-label">{{ __('Employement Type') }}</label>
                        <select name="employement_type" id="employement_type" class="form-control select2" required>
                            <option value="">Select Contract Type</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Temporary">Temporary</option>
                        </select>
                    </div>

                    
                    <div class="form-group col-md-4">
                        {!! Form::label('no_of_positions', __('No. of Positions'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('no_of_positions', old('no_of_positions'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter job Positions',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('experience_required', __('Years of Experience Required'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('experience_required', old('experience_required'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter years of experience',
                        ]) !!}
                    </div>

                    <h4>Compensation & Budget</h4>

                    <div class="form-group col-md-4">
                        {!! Form::label('salary_range', __('Salary Range'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('salary_range', old('salary_range'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter salary range',
                        ]) !!}
                    </div>

                    
                    <div class="form-group col-md-4">
                        {!! Form::label('job_grade', __('Job Grade/Level'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('job_grade', old('job_grade'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter Job Grade',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('budget_code', __('Budget Code / Cost Center'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('budget_code', old('budget_code'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Budget Code / Cost Center',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4 mt-4">
                        <h5>Budgeted This Year?</h5>
                        <div class="my-2 d-flex">
                            <div class="form-check custom-checkbox me-5">
                                <input type="radio" class="form-check-input" name="budgeted" value="yes" id="position-new">
                                <label class="form-check-label" for="position-new">{{ __('Yes') }}</label>
                            </div>
                            <div class="form-check custom-checkbox">
                                <input type="radio" class="form-check-input" name="budgeted" value="no" id="position-replacement">
                                <label class="form-check-label" for="position-replacement">{{ __('No') }}</label>
                            </div>
                        </div>
                    </div>

                    <h4>Approvals</h4>

                    <div class="form-group col-md-4">
                        {!! Form::label('hiring_manager', __('Hiring Manager Name'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('hiring_manager', old('hiring_manager'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Hiring Manager Name',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('hr_bussiness_partner', __('HR Business Partner'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('hr_bussiness_partner', old('hr_bussiness_partner'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'HR Business Partner',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('budget_approval', __('Finance/Budget Approval'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('budget_approval', old('budget_approval'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Finance/Budget Approval',
                        ]) !!}
                    </div>

                    <div class="form-group col-md-4">
                        {!! Form::label('executive_approval', __('Executive Approval'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('executive_approval', old('budget_approval'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Executive Approval',
                        ]) !!}
                    </div>

                    <h4>Additional Notes</h4>

                    <div class="col-md-12">
                        <div class="card card-fluid job-card">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        {!! Form::label('comments', __('Special Requests or Comments'), ['class' => 'col-form-label']) !!}
                                        @if ($chatgpt == 'on')
                                        <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm float-end" data-ajax-popup-over="true" id="grammarCheck" data-url="{{ route('grammar', ['grammar']) }}" data-bs-placement="top" data-title="{{ __('Grammar check with AI') }}">
                                            <i class="ti ti-rotate"></i> <span>{{ __('Grammar check with AI') }}</span>
                                        </a>
                                        @endif
                                        <textarea class="form-control editor summernote-simple-2" name="comments" id="comments" rows="3"></textarea>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('attachments', __('Attachments'), ['class' => 'col-form-label']) !!}
                        <div class="col-md-12 dropzone browse-file" id="my-dropzone"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <h4>Job Description & Requirements</h4>
    <div class="col-md-6">
        <div class="card card-fluid job-card">
            <div class="card-body ">
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('position_summary', __('Position Summary'), ['class' => 'col-form-label']) !!}
                        <textarea class="form-control editor summernote-simple-2" name="position_summary" id="position_summary" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-fluid job-card">
            <div class="card-body ">
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('key_responsibilities', __('Key Responsibilities'), ['class' => 'col-form-label']) !!}
                        @if ($chatgpt == 'on')
                        <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm float-end" data-ajax-popup-over="true" id="grammarCheck" data-url="{{ route('grammar', ['grammar']) }}" data-bs-placement="top" data-title="{{ __('Grammar check with AI') }}">
                            <i class="ti ti-rotate"></i> <span>{{ __('Grammar check with AI') }}</span>
                        </a>
                        @endif
                        <textarea class="form-control editor summernote-simple-2" name="key_responsibilities" id="key_responsibilities" rows="3"></textarea>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-fluid job-card">
            <div class="card-body ">
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('required_qualifications', __('Required Qualifications'), ['class' => 'col-form-label']) !!}
                        <textarea class="form-control editor summernote-simple-2" name="required_qualifications" id="required_qualifications" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-fluid job-card">
            <div class="card-body ">
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('preferred_qualifications', __('Preferred Qualifications'), ['class' => 'col-form-label']) !!}
                        @if ($chatgpt == 'on')
                        <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm float-end" data-ajax-popup-over="true" id="grammarCheck" data-url="{{ route('grammar', ['grammar']) }}" data-bs-placement="top" data-title="{{ __('Grammar check with AI') }}">
                            <i class="ti ti-rotate"></i> <span>{{ __('Grammar check with AI') }}</span>
                        </a>
                        @endif
                        <textarea class="form-control editor summernote-simple-2" name="preferred_qualifications" id="preferred_qualifications" rows="3"></textarea>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 text-end row">
        <div class="form-group">
            <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection

@push('script-page')
<script>
    Dropzone.autoDiscover = true;
    myDropzone = new Dropzone("#my-dropzone", {
        maxFiles: 20,
        // maxFilesize: 209715200,
        parallelUploads: 1,
        // acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
        url: "{{ route('job.files.upload') }}",
        success: function(file, response) {
            if (response.is_success) {
                dropzoneBtn(file, response);
                show_toastr('{{ __('Success') }}', 'Attachment Create Successfully!', 'success');
            } else {
                myDropzone.removeFile(file);
                show_toastr('{{ __('Error') }}', 'File type must be match with Storage setting.',
                    'error');
            }
            // location.reload();
        },
        error: function(file, response) {
            myDropzone.removeFile(file);
            if (response.error) {
                show_toastr('{{ __('Error') }}', response.error, 'error');
            } else {
                show_toastr('{{ __('Error') }}', response.error, 'error');
            }
        }
    });
    myDropzone.on("sending", function(file, xhr, formData) {
        formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
    });

    function dropzoneBtn(file, response) {
        var del = document.createElement('a');
        del.setAttribute('href', response.delete);
        del.setAttribute('class', "action-btn btn-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
        del.setAttribute('data-toggle', "tooltip");
        del.setAttribute('data-original-title', "{{ __('Delete') }}");
        del.innerHTML = "<i class='ti ti-trash'></i>";

        del.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (confirm("Are you sure ?")) {
                var btn = $(this);
                $.ajax({
                    url: btn.attr('href'),
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'DELETE',
                    success: function(response) {
                        if (response.is_success) {
                            btn.closest('.dz-file-preview').remove();
                            btn.closest('.dz-image-preview').remove();
                        } else {
                            show_toastr('{{ __('Error') }}', response.error, 'error');
                        }
                    },
                    error: function(response) {
                        response = response.responseJSON;
                        if (response.is_success) {
                            show_toastr('{{ __('Error') }}', response.error, 'error');
                        } else {
                            show_toastr('{{ __('Error') }}', response.error, 'error');
                        }
                    }
                })
            }
        });

        var html = document.createElement('div');
        html.setAttribute('class', "text-center mt-10");
        html.appendChild(del);

        file.previewTemplate.appendChild(html);
    }
</script>

<script>
    $(document).ready(function() {
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });
</script>
@endpush