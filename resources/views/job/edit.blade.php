@php
$chatgpt = Utility::getValByName('enable_chatgpt');
@endphp


@extends('layouts.admin')
@section('page-title')
{{ __('Edit Job') }}
@endsection
@push('css-page')

<link href="{{ asset('public//libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
@endpush
@push('script-page')
{{-- <script src='{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}'></script> --}}
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
@endpush



@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('job.index') }}">{{ __('Manage Job') }}</a></li>
<li class="breadcrumb-item">{{ __('Edit Job') }}</li>
@endsection

@section('content')

@if ($chatgpt == 'on')
<div class="text-end">
    <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['job']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
        <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
    </a>
</div>
@endif

{{Form::model($job,array('route' => array('job.update', $job->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-md-6 ">
        <div class="card card-fluid job2-card" style="height: auto !important;">
            <div class="card-body ">
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('title', __('Job Title'),['class'=>'form-label']) !!}
                        {!! Form::text('title', null, ['class' => 'form-control','required' => 'required', 'placeholder' => 'Enter job title']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('branch', __('Branch'),['class'=>'form-label']) !!}
                        {{ Form::select('branch', $branches,null, array('class' => 'form-control select','required'=>'required')) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('department_id', __('Department'), ['class' => 'form-label']) }}
                        <select class="form-control department_id" name="department" id="department_id" placeholder="Select Department">
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="contract_type" class="form-label">{{ __('Contract Type') }}</label>
                        <select name="contract_type" id="contract_type" class="form-control select2" required>
                            <option value="">Select Contract Type</option>
                            <option value="Permanent" {{ ($job->contract_type == 'Permanent') ? 'selected' : '' }}>Permanent</option>
                            <option value="Part-time" {{ ($job->contract_type == 'Part-time') ? 'selected' : '' }}>Part-time</option>
                            <option value="Internship" {{ ($job->contract_type == 'Internship') ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('category', __('Job Category'),['class'=>'form-label']) !!}
                        {{ Form::select('category', $categories,null, array('class' => 'form-control select','required'=>'required')) }}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('position', __('No. of Positions*'),['class'=>'form-label']) !!}
                        {!! Form::text('position', null, ['class' => 'form-control','required' => 'required', 'placeholder' => 'Enter job Positions']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('status', __('Status'),['class'=>'form-label']) !!}
                        {{ Form::select('status', $status,null, array('class' => 'form-control select','required'=>'required')) }}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('start_date', __('Start Date'),['class'=>'form-label']) !!}
                        {!! Form::date('start_date', null, ['class' => 'form-control ', 'autocomplete' => 'off']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        {!! Form::label('end_date', __('End Date'),['class'=>'form-label']) !!}
                        {!! Form::date('end_date', null, ['class' => 'form-control ', 'autocomplete' => 'off']) !!}
                    </div>

                    <div class="form-group col-md-12">
                        <label class="col-form-label" for="skill">{{ __('Skill Box') }}</label>
                        <input type="text" class="form-control" value="{{$job->skill}}" data-toggle="tags" name="skill" placeholder="Skill" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="card card-fluid job2-card" style="height: auto !important;">
            <div class="card-body ">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <h6>{{__('Need to ask ?')}}</h6>
                            <div class="my-4">
                                <div class="form-check custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="applicant[]" value="gender" id="check-gender" {{(in_array('gender',$job->applicant)?'checked':'')}}>
                                    <label class="form-check-label" for="check-gender">{{__('Gender')}} </label>
                                </div>
                                <div class="form-check custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="applicant[]" value="dob" id="check-dob" {{(in_array('dob',$job->applicant)?'checked':'')}}>
                                    <label class="form-check-label" for="check-dob">{{__('Date Of Birth')}}</label>
                                </div>
                                <div class="form-check custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="applicant[]" value="address" id="check-address" {{(in_array('address',$job->applicant)?'checked':'')}}>
                                    <label class="form-check-label" for="check-address">{{__('Address')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <h6>{{__('Need to show option ?')}}</h6>
                            <div class="my-4">
                                <div class="form-check custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="visibility[]" value="profile" id="check-profile" {{(in_array('profile',$job->visibility)?'checked':'')}}>
                                    <label class="form-check-label" for="check-profile">{{__('Profile Image')}} </label>
                                </div>
                                <div class="form-check custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="visibility[]" value="resume" id="check-resume" {{(in_array('resume',$job->visibility)?'checked':'')}}>
                                    <label class="form-check-label" for="check-resume">{{__('Resume')}}</label>
                                </div>
                                <div class="form-check custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="visibility[]" value="letter" id="check-letter" {{(in_array('letter',$job->visibility)?'checked':'')}}>
                                    <label class="form-check-label" for="check-letter">{{__('Cover Letter')}}</label>
                                </div>
                                <div class="form-check custom-checkbox">
                                    <input type="checkbox" class="form-check-input" name="visibility[]" value="terms" id="check-terms" {{(in_array('terms',$job->visibility)?'checked':'')}}>
                                    <label class="form-check-label" for="check-terms">{{__('Terms And Conditions')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <h6>{{__('Custom Questions')}}</h6>
                        <div class="my-4">
                            @foreach($customQuestion as $question)
                            <div class="form-check custom-checkbox">
                                <input type="checkbox" class="form-check-input" name="custom_question[]" value="{{$question->id}}" @if($question->is_required == 'yes') required @endif id="custom_question_{{$question->id}}" {{(in_array($question->id,$job->custom_question)?'checked':'')}}>
                                <label class="form-check-label" for="custom_question_{{$question->id}}">{{$question->question}}@if ($question->is_required == 'yes')
                                    <span class="text-danger">*</span>
                                    @endif </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('attachments', __('Attachments'), ['class' => 'col-form-label']) !!}
                        <div class="col-md-12 dropzone browse-file" id="my-dropzone"></div>
                    </div>
                    @foreach ($job->JobAttachments as $file)
                                            <div class=" py-3">
                                                <div class="list-group-item ">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <h6 class="text-sm mb-0">
                                                                <a href="#!">{{ $file->files }}</a>
                                                            </h6>
                                                            <p class="card-text small text-muted">
                                                                {{ number_format(\File::size(storage_path('job_attachment/' . $file->files)) / 1048576, 2) . ' ' . _('MB') }}
                                                            </p>
                                                        </div>
                                                        @php
                                                            $attachments = \App\Models\Utility::get_file('job_attachment');
                                                        @endphp
                                                        <div class="action-btn bg-warning p-0 w-auto    ">
                                                            <a href="{{ $attachments . '/' . $file->files }}"
                                                                class=" btn btn-sm d-inline-flex align-items-center"
                                                                download="" data-bs-toggle="tooltip"
                                                                title="Download">
                                                                <span class="text-white"><i
                                                                        class="ti ti-download"></i></span>
                                                            </a>
                                                        </div>
                                                        <div class="col-auto actions">
                                                            @can('Delete Attachment')
                                                                    <div class="action-btn bg-danger ms-2">
                                                                        <form action=""></form>
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['job.files.delete', $file->id, 1]]) !!}
                                                                        <a href="#!"
                                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"
                                                                            title="{{ __('Delete') }}">
                                                                            <i class="ti ti-trash text-white"></i>
                                                                        </a>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-fluid job-card">
            <div class="card-body ">
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('description', __('Job Description'),['class'=>'form-label']) !!}
                        <textarea class="form-control summernote-simple-2" name="description" id="description" rows="3">{{$job->description}}</textarea>
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
                        {!! Form::label('requirement', __('Job Requirement'), ['class' => 'col-form-label']) !!}
                        @if ($chatgpt == 'on')
                        <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm float-end" data-ajax-popup-over="true" id="grammarCheck" data-url="{{ route('grammar', ['grammar']) }}" data-bs-placement="top" data-title="{{ __('Grammar check with AI') }}">
                            <i class="ti ti-rotate"></i> <span>{{ __('Grammar check with AI') }}</span>
                        </a>
                        @endif

                        <textarea class="form-control summernote-simple" name="requirement" id="requirement" rows="3">{{$job->requirement}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-end">
        <div class="form-group">
            <input type="submit" value="{{__('Update')}}" class="btn btn-primary">
        </div>
    </div>
    {{Form::close()}}
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
        url: "{{ route('job.edit.files.upload') }}",
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
        formData.append("job_code", "{{ $job->code }}");
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
        var b_id = $('#branch').val();
        getDepartment(b_id);
    });

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
                    var selected = ('{{$job->department}}' == key) ? 'selected' : '';
                    $('.department_id').append('<option value="' + key + '" ' + selected + '>' + value + '</option>');
                });
            }
        });
    }
</script>
@endpush