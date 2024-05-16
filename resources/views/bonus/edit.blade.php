@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($bonus, ['route' => ['bonus.update', $bonus->id],'method' => 'PUT']) }}
{{ Form::hidden('employee_id', $employee->id, []) }}
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
            {{ Form::label('title', __('Bonus Title*'), ['class' => 'col-form-label']) }}
            {{ Form::text('title', null, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Enter Title']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('amount', __('Amount'), ['class' => 'col-form-label']) }}
            {{ Form::number('amount', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01']) }}
        </div>
    </div>
</div>
<div class="modal-footer">

    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-warning">

</div>
{{ Form::close() }}
