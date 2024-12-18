@extends('layouts.admin')

@section('page-title')
{{ __('New Employees Checklist') }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('New Employees Checklist') }}</li>
@endsection
@section('content')


<style>
    .employees-actions {
        display: flex;
        justify-content: end;
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
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
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
        color: white !important;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        color: white;
    }
</style>

<div class="employees-actions">
    <div class="employees-nav me-1 mb-2">
        <div class="nav-titles">
            <div class="dropdown">
                <button class="dropbtn">Recruitment &#9660;</button>
                <div class="dropdown-content">
                    <a href="{{ route('job.index') }}">{{ __("Jobs") }} </a>
                    <a href="{{ route('job.create') }}">{{ __("Job Create") }} </a>
                    <a href="{{ route('job-template.index') }}">{{ __('Job Templates') }}</a>
                    <a href="{{ route('job-application.index') }}">{{ __('Job Application') }}</a>
                    <a href="{{ route('job.on.board') }}">{{ __('Job On-Boarding') }}</a>
                    <a href="{{ route('onboarding-checklist.index') }}">{{ __('On-Boarding Checklist') }}</a>
                    <a href="{{ route('personlized-onboarding.index') }}">{{ __('Job On-Boarding Templates') }}</a>
                    <a href="{{ route('question-template.index') }}">{{ __('Question Templates') }}</a>
                    <a href="{{ route('custom-question.index') }}">{{ __('Custom Question') }}</a>
                    <a href="{{ route('interview-schedule.index') }}">{{ __('Interview Schedule') }}</a>
                    <a href="{{ route('config-word-count.index') }}">{{ __('Config Word Count') }}</a>
                    <a
                        href="{{ route('career', [\Auth::user()->creatorId(), \Auth::user()->lang]) }}">{{ __('Job Opening') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-xl-12">
    <div class="card">
        <div class="card-header card-body table-border-style">
            {{-- <h5></h5> --}}
            <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>{{ __('Employee Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Joining Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th width="200px">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>M Rafay</td>
                            <td>mrafay@gmail.com</td>
                            <td>11th Nov 2024</td>
                            <td><span class="badge rounded-pill text-bg-danger">In Active</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop">
                                    <i class="ti ti-checkbox"></i>
                                    <span>Check</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Checklist</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">

                        <h5 class="card-title mb-1">Muhammad Rafay</h5>
                        <h6 class="card-subtitle mb-2 text-secondary">Here is the checklist of the new employee
                            Muhammad Rafay</h6>
                    </div>
                    <div class="card-body">
                        <div class="card p-3 mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <p class="mb-0 ps-2">1. Upload your CV</p>
                                <label for=""><input type="checkbox" class="form-check-input me-1" checked> Mark as Complete</label>
                            </div>
                            <input type="text" class="form-control" placeholder="Add Remarks" />
                            <p class="text-secondary mb-0 mt-2" style="font-size: 14px"><i class="ti ti-check"></i> Complete on 14th Nov 2024</p>
                        </div>
                        <div class="card p-3 mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <p class="mb-0 ps-2">1. Elaborate your experience</p>
                                <label for=""><input type="checkbox" class="form-check-input me-1" checked> Mark as Complete</label>
                            </div>
                            <input type="text" class="form-control" placeholder="Add Remarks" />
                            <p class="text-secondary mb-0 mt-2" style="font-size: 14px"><i class="ti ti-check"></i> Complete on 14th Nov 2024</p>
                        </div>
                        <div class="card p-3 mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <p class="mb-0 ps-2">1. Write a cover letter</p>
                                <label for=""><input type="checkbox" class="form-check-input me-1" checked> Mark as Complete</label>
                            </div>
                            <input type="text" class="form-control" placeholder="Add Remarks" />
                            <p class="text-secondary mb-0 mt-2" style="font-size: 14px"><i class="ti ti-check"></i> Complete on 14th Nov 2024</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script-page')
    <script>
        $(document).ready(function () {
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

                $('#branching-questions').append(questionDiv);
                questionCounter++;
            }

            // Add question when the page loads
            addQuestion();

            // Add a new question when the button is clicked
            $('#add-question').click(function () {
                addQuestion();
            });

            // Toggle Word Count and Options fields based on question type
            $('#branching-questions').on('change', '[name$="[type]"]', function () {
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
            $('#branching-questions').on('click', '.add-option', function () {
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
            $('#branching-questions').on('click', '.remove-option', function () {
                $(this).closest('.input-group').remove();
            });

            // Remove question
            $('#branching-questions').on('click', '.remove-question', function () {
                $(this).closest('.row').remove();
                questionCounter--;
                updateQuestionNumbers();
            });

            function updateQuestionNumbers() {
                $('#branching-questions .row').each(function (index) {
                    const questionHeading = $(this).find('h4');
                    questionHeading.text(`Question #${index + 1}`);
                });
            }

            // Submit form
            $('#questionForm').submit(function (event) {
                event.preventDefault();
                const formData = $(this).serializeArray();
                console.log(formData);
                // Send formData to the server using AJAX or perform any desired action
            });
        });
    </script>

@endpush