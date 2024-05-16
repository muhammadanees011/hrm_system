@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::open(['url' => 'overtimerequest', 'method' => 'post']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['overtime']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
         <div class="form-group col-md-12">
            {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('title', __('Overtime Title*'), ['class' => 'col-form-label']) }}
            {{ Form::text('title', null, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Enter Title']) }}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('number_of_days', __('Number of days'), ['class' => 'col-form-label']) }}
            {{ Form::number('number_of_days', null, ['class' => 'form-control ','required' => 'required','step' => '0.01']) }}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('hours', __('Hours'), ['class' => 'col-form-label']) }}
            {{ Form::number('hours', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01']) }}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('rate', __('Rate'), ['class' => 'col-form-label']) }}
            {{ Form::number('rate', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}
            <select name="status" class="form-control  select2" id="status">
                <option value="Pending">{{ __('Pending') }}</option>
                <option value="Approved">{{ __('Approved') }}</option>
                <option value="Rejected">{{ __('Rejected') }}</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">

    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">

</div>
{{ Form::close() }}
