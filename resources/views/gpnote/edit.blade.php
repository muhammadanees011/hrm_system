@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($gpnote, ['route' => ['gpnote.update', $gpnote->id], 'method' => 'PUT']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['healthassessment']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">

        <div class="col-md-6 form-group">
            {{ Form::label('employee_id', __('Employee Name'), ['class' => 'col-form-label']) }}
            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('assessment_date', __('Assessment Date'),['class'=>'col-form-label']) }}
            {{ Form::date('assessment_date', null, array('class' => 'form-control current_date','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('follow_up_date', __('Follow Up Date'),['class'=>'col-form-label']) }}
            {{ Form::date('follow_up_date', null, array('class' => 'form-control current_date','required'=>'required')) }}
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('presenting_complaint', __('Presenting Complaint'),['class'=>'col-form-label']) }}
                {{ Form::textarea('presenting_complaint', null, ['class' => 'form-control', 'placeholder' => __('Presenting Complaint'),'rows' => '3' ,'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>

</div>

{{ Form::close() }}
