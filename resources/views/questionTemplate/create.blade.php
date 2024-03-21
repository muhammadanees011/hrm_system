@extends('layouts.admin')
@section('page-title')
{{ __('Create Question Template') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('question-template.index') }}">{{ __('Manage Question Template') }}</a></li>
<li class="breadcrumb-item">{{ __('Create Question Template') }}</li>
@endsection

@section('content')

{{ Form::open(['url' => 'question-template', 'method' => 'post']) }}
<div class="row mt-3">
    <div class="col-md-12 ">
        <div class="card card-fluid job-card">
            <div class="card-body ">
                <div class="row" id="branching-questions">
                    <div class="form-group col-md-12">
                        {!! Form::label('title', __('Template Name'), ['class' => 'col-form-label']) !!}
                        {!! Form::text('title', old('title'), [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter question template',
                        ]) !!}
                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <input type="button" value="{{ __('Add Question') }}" id="add-question" class="btn btn-secondary">
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
</div>
{{ Form::close() }}

@endsection

@push('script-page')
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
        $('#add-question').click(function() {
            addQuestion();
        });

        // Toggle Word Count and Options fields based on question type
        $('#branching-questions').on('change', '[name$="[type]"]', function() {
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
        $('#branching-questions').on('click', '.add-option', function() {
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
        $('#branching-questions').on('click', '.remove-option', function() {
            $(this).closest('.input-group').remove();
        });

        // Remove question
        $('#branching-questions').on('click', '.remove-question', function() {
            $(this).closest('.row').remove();
            questionCounter--;
            updateQuestionNumbers();
        });

        function updateQuestionNumbers() {
            $('#branching-questions .row').each(function(index) {
                const questionHeading = $(this).find('h4');
                questionHeading.text(`Question #${index + 1}`);
            });
        }

        // Submit form
        $('#questionForm').submit(function(event) {
            event.preventDefault();
            const formData = $(this).serializeArray();
            console.log(formData);
            // Send formData to the server using AJAX or perform any desired action
        });
    });
</script>


<!-- <script>
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
            </select>
        </div>
    `);

            // Add branching options
            // const branchingDiv = $('<div class="col-md-3">');
            // branchingDiv.append(`<label for="${baseName}[branching]">Branching:</label>`);

            // const branchingSelect = $(`<select name="${baseName}[branching]" class="form-control">`);
            // if (questionCounter > 0) {
            //     branchingSelect.append('<option value="">Select Branching</option>');
            //     // Populate options dynamically with previously added questions
            //     for (let i = 0; i < questionCounter; i++) {
            //         branchingSelect.append(`<option value="${i}">Question #${i + 1}</option>`);
            //     }
            // } else {
            //     branchingSelect.append('<option value="">No Branching</option>');
            // }

            // branchingDiv.append(branchingSelect);
            // questionDiv.append(branchingDiv);

            // Conditionally show Word Count field
            questionDiv.append(`
        <div class="col-md-2 wordCountSection" style="display: none;">
            <label for="${baseName}[word_count]" class="wordCountLabel">Word Count:</label>
            <input type="number" name="${baseName}[word_count]" class="form-control wordCountInput" min="1">
        </div>
    `);
            if (questionCounter > 0) {
                questionDiv.append(`
        <div class="col-md-1">
            <button type="button" class="btn btn-danger remove-question" style=" margin-top: 18px;"><i class="fa fa-trash"></i></button>
        </div>
    `);
                // questionDiv.append(removeButton);
            }


            $('#branching-questions').append(questionDiv);
            // if (questionCounter > 0) {
            // updateBranchingOptions();
            // }
            questionCounter++;
        }


        // Add question when the page loads
        addQuestion();

        // Add a new question when the button is clicked
        $('#add-question').click(function() {
            addQuestion();
        });

        // Toggle Word Count field based on question type
        $('#branching-questions').on('change', '[name$="[type]"]', function() {
            const wordCountSection = $(this).closest('.mb-3').find('.wordCountSection'); // Updated class name
            console.log(this);

            if ($(this).val() === 'textarea') {
                wordCountSection.show(); // Updated variable name
            } else {
                wordCountSection.hide(); // Updated variable name
            }
        });

        function updateFunctions() {
            updateQuestionNumbers();
            // updateBranchingOptions();
        }

        function updateQuestionNumbers() {
            $('#branching-questions .row').each(function(index) {
                const questionHeading = $(this).find('h4');
                questionHeading.text(`Question #${index + 1}`);
            });
        }

        function updateBranchingOptions() {
            $('#branching-questions [name$="[branching]"]').each(function() {
                const branchingSelect = $(this);
                const currentQuestionIndex = parseInt(branchingSelect.val());

                // Store the currently selected value
                const currentSelectedValue = branchingSelect.val();

                branchingSelect.empty();
                if (currentQuestionIndex !== undefined && currentQuestionIndex !== null) {
                    branchingSelect.append('<option value="">Select Branching</option>');
                } else {
                    branchingSelect.append('<option value="">No Branching</option>');
                }

                $('#branching-questions .row').each(function(index) {
                    branchingSelect.append(`<option value="${index}">Question #${index + 1}</option>`);
                });

                // Set the preserved selected value back
                branchingSelect.val(currentSelectedValue);
            });
        }

        $('#branching-questions').on('click', '.remove-question', function() {
            $(this).closest('.row').remove();
            questionCounter--;
            updateFunctions();
        });

        // Submit form
        $('#questionForm').submit(function(event) {
            event.preventDefault();
            const formData = $(this).serializeArray();
            console.log(formData);
            // Send formData to the server using AJAX or perform any desired action
        });
    });
</script> -->
@endpush