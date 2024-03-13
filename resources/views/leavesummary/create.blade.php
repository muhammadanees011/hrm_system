@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::open(['url' => 'leavesummary/'.$employee_id, 'method' => 'post']) }}
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
            {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'col-form-label']) }}
            <select name="leave_type_id" id="leave_type_id" class="form-control select">
                <option value="">{{ __('Select Leave Type') }}</option>
                @foreach ($leavetypes as $leave)
                    <option value="{{ $leave->id }}">{{ $leave->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('entitled', __('Entitled'), ['class' => 'col-form-label']) }}
            {{ Form::text('entitled', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('taken', __('Taken'), ['class' => 'col-form-label']) }}
            {{ Form::text('taken', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('pending', __('Pending'), ['class' => 'col-form-label']) }}
            {{ Form::text('pending', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('carried_over', __('Carried Over'), ['class' => 'col-form-label']) }}
            {{ Form::text('carried_over', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('balance', __('Leave Balance'), ['class' => 'col-form-label']) }}
            {{ Form::text('balance', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
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