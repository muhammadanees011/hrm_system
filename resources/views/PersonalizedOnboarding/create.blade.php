@php
$chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

@extends('layouts.admin')

@section('page-title')
{{ __('Create Employee Onboarding template') }}
@endsection

@push('css-page')
<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
@endpush

@push('script-page')
<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>
<script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script> --}}
{{-- <script>
        if ($(".pc-tinymce-2").length) {
            tinymce.init({
                selector: '.pc-tinymce-2',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }'
            });
        }
    </script> --}}
@endpush

@section('page-title')
{{ __('Create Employee Onboarding template') }}
@endsection

@section('title')
<div class="d-inline-block">
    <h5 class="h4 d-inline-block font-weight-400 mb-0"></h5>
</div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('personlized-onboarding.index') }}">{{ __('Manage Onboarding Templates') }}</a></li>
<li class="breadcrumb-item active" aria-current="page"></li>{{ __('Create Employee Onboarding template') }}
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            {{ Form::open(['url' => 'personlized-onboarding', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#general" class="list-group-item list-group-item-action border-0">{{ __('General') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#header" class="list-group-item list-group-item-action border-0">{{ __('Header Details') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#askinfo" class="list-group-item list-group-item-action border-0">{{ __('Ask Information/Details') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#documents" class="list-group-item list-group-item-action border-0">{{ __('Documents') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div id="general">
                        <div class="row ">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('General Details') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            {!! Form::label('name', __('Template Name'), ['class' => 'col-form-label']) !!}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('branch', __('Branch'), ['class' => 'col-form-label']) !!}
                                            {{ Form::select('branch', $branches, null, ['class' => 'form-control select2', 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('department_id', __('Department'), ['class' => 'col-form-label']) }}
                                            <select class="form-control department_id" name="department" id="department_id" placeholder="Select Department">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="header">
                        <div class="row ">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Header Details') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            {!! Form::label('header_title', __('Header Title'), ['class' => 'col-form-label']) !!}
                                            {{ Form::text('header_title', null, ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group my-3">
                                            <label>{{ __('Select Media Option') }}</label><br>
                                            <!-- <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="header_option" id="video_url_option" value="video_url">
                                                <label class="form-check-label" for="video_url_option">{{ __('Video URL') }}</label>
                                            </div> -->
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="header_option" id="video_upload_option" value="video_upload">
                                                <label class="form-check-label" for="video_upload_option">{{ __('Video Upload') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="header_option" id="image_option" value="image">
                                                <label class="form-check-label" for="image_option">{{ __('Image') }}</label>
                                            </div>
                                        </div>

                                        <!-- Additional input fields for video or image -->
                                        <div id="video_url_input" style="display: none;">
                                            <label for="video_url">{{ __('Video URL') }}</label>
                                            <input type="text" name="video_url" id="video_url" class="form-control" placeholder="{{ __('Enter Video URL') }}">
                                        </div>
                                        <div id="video_upload_input" style="display: none;">
                                            <label for="video_file">{{ __('Upload Video') }}</label>
                                            <input type="file" name="video_file" id="video_file" class="form-control-file">
                                        </div>
                                        <div id="image_input" style="display: none;">
                                            <label for="image_file">{{ __('Upload Image') }}</label>
                                            <input type="file" name="image_file" id="image_file" class="form-control-file">
                                        </div>

                                        {{ Form::label('header_description', __('Header Description'), ['class' => 'col-form-label text-dark']) }}
                                        {{ Form::textarea('header_description',null, ['class' => 'summernote-simple form-control', 'required' => 'required', 'id'=>'mytextarea']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="askinfo">
                        <div class="row ">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Ask Information/Details') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" id="askinfo-questions">
                                        </div>
                                        <input type="button" value="{{ __('Add Question') }}" id="add-question" class="btn btn-info">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="documents" role="tabpanel" aria-labelledby="pills-comments-tab">
                        <div class="row pt-2">
                            <div class="col-12">
                                <div id="comment">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row align-items-center">
                                                <div class="col-6">
                                                    <h5>{{ __('Documents') }}</h5>
                                                </div>
                                                <div class="col switch-width text-end">
                                                    <div class="form-group mb-0">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class="" name="attachments_status" id="attachments_status" checked>
                                                            <label class="custom-control-label" for="attachments_status"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive" style="overflow: hidden;">
                                                <div class="row">
                                                    <div class="col-sm-12 card-body">
                                                        <div id="fileRows">
                                                            <!-- Initially, there are no rows -->
                                                        </div>
                                                        <button type="button" class="btn btn-warning mt-3" onclick="addFileRow()">Add File</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
</div>
@endsection

@push('script-page')
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
<script>
    // Function to toggle display of input fields based on radio button selection
    document.querySelectorAll('input[type="radio"][name="header_option"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var selectedOption = this.value;
            if (selectedOption === 'video_url') {
                document.getElementById('video_url_input').style.display = 'block';
                document.getElementById('video_upload_input').style.display = 'none';
                document.getElementById('image_input').style.display = 'none';
            } else if (selectedOption === 'video_upload') {
                document.getElementById('video_url_input').style.display = 'none';
                document.getElementById('video_upload_input').style.display = 'block';
                document.getElementById('image_input').style.display = 'none';
            } else if (selectedOption === 'image') {
                document.getElementById('video_url_input').style.display = 'none';
                document.getElementById('video_upload_input').style.display = 'none';
                document.getElementById('image_input').style.display = 'block';
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        let questionCounter = 0;

        // Function to add a new question to the form
        function addQuestion() {
            const questionDiv = $(`<div class="row mb-3">`); // Wrap each question in a row
            const baseName = `questions[${questionCounter}]`;

            const questionHeading = $(`<h4>Question #${questionCounter + 1}</h4>`);
            questionDiv.append(questionHeading);

            questionDiv.append(`
                <div class="col-md-3">
                    <label for="${baseName}[name]">Question:</label>
                    <input type="text" name="${baseName}[name]" class="form-control" required>
                </div>
            `);

            questionDiv.append(`
                <div class="col-md-3">
                    <label for="${baseName}[type]">Type:</label>
                    <select name="${baseName}[type]" class="form-control" required>
                        <option value="text">Text Input</option>
                        <option value="textarea">Text Area</option>
                        <option value="radio">Radio</option>
                        <option value="file">File Upload</option>
                    </select>
                </div>
            `);

            // Conditionally show Word Count field
            questionDiv.append(`
                <div class="col-md-2 wordCountSection" style="display: none;">
                    <label for="${baseName}[word_count]" class="wordCountLabel">Word Count:</label>
                    <input type="number" name="${baseName}[word_count]" class="form-control wordCountInput" min="1">
                </div>
            `);

            // Add options for radio questions
            const optionsDiv = $('<div class="col-md-3 options-section" style="display: none;">');
            optionsDiv.append('<label>Options:</label>');

            optionsDiv.append(`
                <div class="input-group mb-2">
                    <input type="text" name="${baseName}[options][]" class="form-control" placeholder="Option 1">
                    <button type="button" class="btn btn-danger p-2 remove-option"><i class="fa fa-trash"></i></button>
                </div>
            `);

            optionsDiv.append(`
                <div class="input-group mb-2">
                    <input type="text" name="${baseName}[options][]" class="form-control" placeholder="Option 2">
                    <button type="button" class="btn btn-danger p-2 remove-option"><i class="fa fa-trash"></i></button>
                </div>
            `);

            optionsDiv.append('<div class="mb-2 add-option-section"><button type="button" class="btn btn-success p-2 add-option">Add Option</button></div>');
            questionDiv.append(optionsDiv);

            // Add remove question button
            if (questionCounter > 0) {
                questionDiv.append(`
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger p-2 remove-question" style="margin-top: 18px;">Remove</button>
                    </div>
                `);
            }

            $('#askinfo-questions').append(questionDiv);
            questionCounter++;
        }

        // Add question when the page loads
        addQuestion();

        // Add a new question when the button is clicked
        $('#add-question').click(function() {
            addQuestion();
        });

        // Toggle Word Count and Options fields based on question type
        $('#askinfo-questions').on('change', '[name$="[type]"]', function() {
            const wordCountSection = $(this).closest('.mb-3').find('.wordCountSection');
            const optionsSection = $(this).closest('.row').find('.options-section');
            console.log(this);

            if ($(this).val() === 'textarea') {
                wordCountSection.show();
                optionsSection.hide();
            } else if ($(this).val() === 'radio') {
                wordCountSection.hide();
                optionsSection.show();
            } else {
                wordCountSection.hide();
                optionsSection.hide();
            }
        });

        // Add option
        $('#askinfo-questions').on('click', '.add-option', function() {
            const optionsDiv = $(this).closest('.options-section');
            const baseName = optionsDiv.closest('.row').find('select[name$="[type]"]').attr('name');
            const questionIndex = baseName.match(/\d+/)[0]; // Extract the question index
            optionsDiv.find('.add-option-section').before(`
        <div class="input-group mb-2">
            <input type="text" name="questions[${questionIndex}][options][]" class="form-control" placeholder="New Option">
            <button type="button" class="btn btn-danger p-2 remove-option"><i class="fa fa-trash"></i></button>
        </div>
    `);
        });

        // Remove option
        $('#askinfo-questions').on('click', '.remove-option', function() {
            $(this).closest('.input-group').remove();
        });

        // Remove question
        $('#askinfo-questions').on('click', '.remove-question', function() {
            $(this).closest('.row').remove();
            questionCounter--;
            updateQuestionNumbers();
        });

        function updateQuestionNumbers() {
            $('#askinfo-questions .row').each(function(index) {
                const questionHeading = $(this).find('h4');
                questionHeading.text(`Question #${index + 1}`);
            });
        }
    });
</script>
<script>
    let indexFile = 0;

    function addFileRow() {
        var row = document.createElement('div');
        row.classList.add('row', 'mt-3');
        row.innerHTML = `
            <div class="col-md-4">
                <select class="form-select" name="files[${indexFile}][type]">
                    <option value="read_and_approve">Read and Approve</option>
                    <option value="read">Read</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="file" name="files[${indexFile}][file]" class="form-control">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="removeFileRow(this)">Remove</button>
            </div>
        `;
        document.getElementById('fileRows').appendChild(row);
        indexFile++; // Increment indexFile for the next row
    }

    function removeFileRow(button) {
        button.closest('.row').remove();
        indexFile--; // Increment indexFile for the next row
    }
</script>

@endpush