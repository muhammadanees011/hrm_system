@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($warning, ['route' => ['warning.update', $warning->id], 'method' => 'PUT']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['warning']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <!-- @if (\Auth::user()->type != 'employee')
            <div class="form-group col-md-6 col-lg-6">
                {{ Form::label('warning_by', __('Warning By'), ['class' => 'col-form-label']) }}
                {{ Form::select('warning_by', $employees, null, ['class' => 'form-control select2', 'required' => 'required']) }}
            </div>
        @endif -->
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('warning_to', __('Warning To'), ['class' => 'col-form-label']) }}
            {{ Form::select('warning_to', $employees, null, ['class' => 'form-control select2' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label']) }}
            {{ Form::text('subject', null, ['class' => 'form-control' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('warning_date', __('Warning Date'), ['class' => 'col-form-label']) }}
            {{ Form::text('warning_date', null, ['class' => 'form-control d_week', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-12">
            {{ Form::label('description', __('Note'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Note') ,'rows' => '3' ,'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
