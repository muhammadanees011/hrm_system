@extends('layouts.admin')
@section('page-title')
{{ __('Create Qustion Template') }}
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
                <div class="row" >
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
        let questionCounter = 1;
        $("#add-question").click(function() {
            const questionDiv = $('<div class="form-group col-md-12">');
            questionDiv.addClass('question');

            const questionLabel = $('<label class="col-form-label">');
            questionLabel.text('Question ' + questionCounter + ': ');
            questionDiv.append(questionLabel);

            const questionInput = $('<input class="form-control">');
            questionInput.attr('type', 'text');
            questionInput.attr('name', 'question' + questionCounter);
            questionDiv.append(questionInput);


            for (let i = 1; i <= 2; i++) { // You can change 4 to the number of options you want

                const optionBranchingLabel = $('<label class="col-form-label">');
                optionBranchingLabel.text('Option ' + i );
                questionDiv.append(optionBranchingLabel);

                const optionInput = $('<input class="form-control">');
                optionInput.attr('type', 'text');
                optionInput.attr('name', 'question' + questionCounter + '_option' + i);
                questionDiv.append(optionInput);

                const optionBranchingDropdown = $('<select class="form-control select">');
                optionBranchingDropdown.attr('name', 'question' + questionCounter + '_option' + i + '_branching');
                optionBranchingDropdown.addClass('branching-dropdown');
                optionBranchingDropdown.append('<option value="">No branching</option>'); // Default option

                @foreach ($questions as $question)
                    optionBranchingDropdown.append('<option value="{{ $question->id }}">{{ $question->question }}</option>');
                @endforeach

                questionDiv.append(optionBranchingDropdown);
            }

            questionDiv.append('<br>');
            const removeButton = $('<input class="btn btn-danger">');
            removeButton.attr('type', 'button');
            removeButton.attr('value', 'Remove');
            removeButton.addClass('remove-question');
            questionDiv.append(removeButton);

            $('#branching-questions').append(questionDiv);
            questionCounter++;
        });
    });
</script>
@endpush
