@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($carryover, ['route' => ['carryover.update', $carryover->id], 'method' => 'PUT']) }}

<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['healthassessment']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        
        <div class="col-md-6 form-group">
            {{ Form::label('employee_id', __('Employee Name'),['class'=>'col-form-label']) }}
            {{ Form::select('employee_id', $employees,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
             {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'col-form-label']) }}
                {{-- {{ Form::select('leave_type_id', $leavetypes, null, ['class' => 'form-control select', 'placeholder' => __('Select Leave Type')]) }} --}}
            <select name="leave_type_id" id="leave_type_id" class="form-control select">
                @foreach ($leavetypes as $leave)
                    <option value="{{ $leave->id }}">{{ $leave->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('leaves_count', __('No. of leaves'), ['class' => 'col-form-label']) }}
            {{ Form::text('leaves_count', null, ['class' => 'form-control text' ,'required' => 'required']) }}
        </div>
    </div>
</div>


<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Create')}}</button>
   
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