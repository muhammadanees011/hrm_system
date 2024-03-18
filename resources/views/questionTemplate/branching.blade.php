@extends('layouts.admin')
@section('page-title')
{{ __('Manage Branching Logic') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('question-template.index') }}">{{ __('Manage Question Template') }}</a></li>
<li class="breadcrumb-item">{{ __('Manage Branching Logic') }}</li>
@endsection

@section('content')

{{ Form::open(['url' => 'question-template/branching/'.$questionTemplate->id, 'method' => 'post']) }}
<div class="row mt-3">
    <div class="col-md-12 ">
        <div class="card card-fluid job-card">
            <div class="card-body ">
                <h3>{{ $questionTemplate->title }}</h3>
                <hr>
                <h4 class="my-2">Questions:</h4>
                @forEach ($questionTemplate->questions as $question)
                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::label('title', $question->name, ['class' => 'col-form-label']) !!}
                        @if($question->type == 'text')
                        {!! Form::text('title',null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter answer',
                        'disabled' => 'disabled'
                        ]) !!}
                        @elseif($question->type == 'textarea')
                        {!! Form::textarea('title',null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter answer',
                        'disabled' => 'disabled'
                        ]) !!}
                        @else
                        @foreach ($question->options as $option)
                        <div class="row d-flex align-items-center form-check mb-1">
                            <div class="col-md-6 d-flex align-items-center gap-2">
                                <strong>Option:</strong>
                                <label class="form-check-label" for="option_{{ $option->id }}">
                                    {{ $option->option_text }}
                                </label>
                                <input type="hidden" name="options[]" value="{{ $option->id }}">
                            </div>
                            <div class="col-md-6 ">
                                <select name="branching[{{ $option->id }}]" class="form-control">
                                    <option value="">Select Branching Logic</option>
                                    @foreach ($questionTemplate->questions as $question)
                                    <option value="{{ $question->id }}" {{ $option->branching_logic == $question->id ? 'selected' : '' }}>
                                        {{ $question->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-12 text-end row">
        <div class="form-group">
            <input type="submit" value="{{ __('Submit') }}" class="btn btn-primary">
        </div>
    </div>
</div>
{{ Form::close() }}

@endsection

@push('script-page')

@endpush