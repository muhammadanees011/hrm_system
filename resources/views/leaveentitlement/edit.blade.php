@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($leaveentitlement, ['route' => ['leaveentitlement.update', $leaveentitlement->id], 'method' => 'PUT']) }}

<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['termination']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('base_allowance', __('Base Allowance'), ['class' => 'col-form-label']) }}
            {{ Form::text('base_allowance', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('carry_over', __('Carry Over'), ['class' => 'col-form-label']) }}
            {{ Form::text('carry_over', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('total_allowance', __('Total Allowance'), ['class' => 'col-form-label']) }}
            {{ Form::text('total_allowance', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('absence_count', __('Absence Count'), ['class' => 'col-form-label']) }}
            {{ Form::text('absence_count', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('remaining_allowance', __('Remaining Allowance'), ['class' => 'col-form-label']) }}
            {{ Form::text('remaining_allowance', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('holidays_taken', __('Holidays Taken'), ['class' => 'col-form-label']) }}
            {{ Form::text('holidays_taken', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('maternity_paternity', __('Maternity/Paternity'), ['class' => 'col-form-label']) }}
            {{ Form::text('maternity_paternity', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('sick_leaves_taken', __('Sick Leaves Taken'), ['class' => 'col-form-label']) }}
            {{ Form::text('sick_leaves_taken', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}

<script>
    $(document).ready(function() {
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });
</script>