@extends('layouts.admin')

@section('page-title')
{{ __('Edit Question Template') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('question-template.index') }}">{{ __('Manage Question Template') }}</a></li>
<li class="breadcrumb-item">{{ __('Edit Question Template') }}</li>
@endsection

@section('content')
{{ Form::model($questionTemplate, ['route' => ['question-template.update', $questionTemplate->id], 'method' => 'put']) }}
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card card-fluid job-card">
            <div class="card-body">
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
            <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
        </div>
    </div>
</div>
{{ Form::close() }}
@endsection

@push('script-page')
<script>
    $(document).ready(function() {
                let questionCounter = 0;
                let questionEditCounter = {{
                        isset($questionTemplate) ? $questionTemplate->questions->count() : 0}};

                // Function to add a new question to the form
                function addQuestion() {
                    const questionDiv = $(`<div class="row mb-3" data-question-counter="${questionCounter}">`); // Add data attribute
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
                        <button type="button" class="btn btn-danger remove-option"><i class="fa fa-trash"></i></button>
                    </div>
                `);

                    optionsDiv.append(`
                    <div class="input-group mb-2">
                        <input type="text" name="${baseName}[options][]" class="form-control" placeholder="Option 2">
                        <button type="button" class="btn btn-danger remove-option"><i class="fa fa-trash"></i></button>
                    </div>
                `);

                    optionsDiv.append('<div class="mb-2 add-option-section"><button type="button" class="btn btn-success add-option">Add Option</button></div>');
                    questionDiv.append(optionsDiv);

                    // Add remove question button
                    if (questionCounter > 0) {
                        questionDiv.append(`
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger p-2 remove-question" style="margin-top: 18px;">Remove</button>
                        </div>
                    `);
                    }

                    // If it's an edit view, populate values for existing questions
                    if (questionCounter < questionEditCounter) {
                        const existingQuestion = @json($questionTemplate->questions[questionCounter]);

                        questionDiv.find('input[name$="[name]"]').val(existingQuestion.name);
                        questionDiv.find('select[name$="[type]"]').val(existingQuestion.type).change();

                        // Conditionally show Word Count field
                        if (existingQuestion.type === 'textarea') {
                            questionDiv.find('.wordCountSection').show();
                        }

                        // If it's a radio type question, populate options
                        if (existingQuestion.type === 'radio') {
                            existingQuestion.options.forEach((option, index) => {
                                if (index > 1) {
                                    // If more than 2 options, add new option fields
                                    const baseName = `questions[${questionDiv.data('question-counter')}][options][]`; // Use data attribute
                                    optionsDiv.find('.add-option-section').before(`
                                    <div class="input-group mb-2">
                                        <input type="text" name="${baseName}" class="form-control" placeholder="New Option" value="${option}">
                                        <button type="button" class="btn btn-danger p-2 remove-option"><i class="fa fa-trash"></i></button>
                                    </div>
                                `);
                                } else {
                                    // Populate values for the first 2 options
                                    optionsDiv.find(`input[name$="[options][]"]`).eq(index).val(option);
                                }
                            });
                        }
                    }

                    $('#branching-questions').append(questionDiv);
                    questionCounter++;
                }

                // Add questions when the page loads for edit view
                for (let i = 0; i < questionEditCounter; i++) {
                    addQuestion();
                }

                // Add a new question when the button is clicked
                $('#add-question').click(function() {
                    addQuestion();
                });

                // Toggle Word Count and Options fields based on question type
                $('#branching-questions').on('change', '[name$="[type]"]', function() {
                    const wordCountSection = $(this).closest('.mb-3').find('.wordCountSection');
                    const optionsSection = $(this).closest('.row').find('.options-section');

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
                    optionsDiv.find('.add-option-section').before(`
                    <div class="input-group mb-2">
                        <input type="text" name="${baseName}[options][]" class="form-control" placeholder="New Option">
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
            }
</script>
@endpush