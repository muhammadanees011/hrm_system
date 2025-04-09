@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($leave, ['route' => ['leave.update', $leave->id], 'method' => 'PUT']) }}
<?php
$startHour = str_pad($leave->start_time, 2, '0', STR_PAD_LEFT) . ':00';
$endHour = str_pad($leave->end_time, 2, '0', STR_PAD_LEFT) . ':00';

?>

<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['leave']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    @if (\Auth::user()->type != 'employee')
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
                    {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'placeholder' => __('Select Employee')]) }}
                </div>
            </div>
        </div>
    @else
        {!! Form::hidden('employee_id', $leave->employee_id, ['id' => 'employee_id']) !!}
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'col-form-label']) }}
                {{-- {{ Form::select('leave_type_id', $leavetypes, null, ['class' => 'form-control select', 'placeholder' => __('Select Leave Type')]) }} --}}
                <select name="leave_type_id" id="leave_type_id" class="form-control select">
                    @foreach ($leavetypes as $leave)
                        <option value="{{ $leave->id }}">{{ $leave->title }} (<p class="float-right pr-5">
                                {{ $leave->days }}</p>)</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('is_paid_leave', __('Paid'), ['class' => 'col-form-label']) }}
                <select name="is_paid_leave" id="leave_duration" class="form-control select">
                    <option value="1"  @if($leave->is_paid_leave=='1') selected @endif>{{ __('Yes') }}</option>
                    <option value="0" @if(!$leave->is_paid_leave=='0') selected @endif>{{ __('No') }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }}
                {{ Form::text('start_date', null, ['class' => 'form-control d_week', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }}
                {{ Form::text('end_date', null, ['class' => 'form-control d_week', 'autocomplete' => 'off']) }}
            </div>
        </div>
    </div>
    <div class="col-md-12">
    <div class="form-group">
        {{ Form::label('leave_duration', __('Leave Duration'), ['class' => 'col-form-label']) }}
        {{ Form::select('leave_duration', ['' => 'Select Duration', 'half_day' => 'Half Day', 'full_day' => 'Full Day', 'full_day' => '1 Hour', 'full_day' => '2 Hours', 'full_day' => '3 Hours'], $leave->leave_duration, ['class' => 'form-control select', 'id' => 'leave_duration']) }}
    </div>
</div>


<div class="row" id="timeDurationSection">
    <div class="form-group col-md-12">
        {{ Form::label('duration_hours', __('No. Of Hours'), ['class' => 'form-label']) }}
        {{ Form::text('duration_hours', $leave->duration_hours,  ['class' => 'form-control ', 'placeholder' => 'Enter No. Of Hours']) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('start_time', __('Start Time'), ['class' => 'form-label']) }}
        {{ Form::select('start_time', array_combine($hours, $hours), $leave->start_time, ['class' => 'form-control select2', 'id' => 'start_time', 'placeholder' => __('Select Start Time')]) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('end_time', __('End Time'), ['class' => 'form-label']) }}
        {{ Form::select('end_time', array_combine($hours, $hours), $leave->end_time, ['class' => 'form-control select2', 'id' => 'end_time', 'placeholder' => __('Select End Time')]) }}
    </div>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('leave_reason', __('Leave Reason'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('leave_reason', null, ['class' => 'form-control', 'placeholder' => __('Leave Reason'), 'rows' => '3']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remark', __('Remark'), ['class' => 'col-form-label']) }}
                @if ($chatgpt == 'on')
                    <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm" data-ajax-popup-over="true"
                        id="grammarCheck" data-url="{{ route('grammar', ['grammar']) }}" data-bs-placement="top"
                        data-title="{{ __('Grammar check with AI') }}">
                        <i class="ti ti-rotate"></i> <span>{{ __('Grammar check with AI') }}</span>
                    </a>
                @endif
                {{ Form::textarea('remark', null, ['class' => 'form-control grammer_textarea', 'placeholder' => __('Leave Remark'), 'rows' => '3']) }}
            </div>
        </div>
    </div>
    @role('Company')
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}
                    <select name="status" id="" class="form-control select2">
                        <option value="">{{ __('Select Status') }}</option>
                        <option value="pending" @if ($leave->status == 'Pending') selected="" @endif>{{ __('Pending') }}
                        </option>
                        <option value="approval" @if ($leave->status == 'Approval') selected="" @endif>{{ __('Approval') }}
                        </option>
                        <option value="reject" @if ($leave->status == 'Reject') selected="" @endif>{{ __('Reject') }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    @endrole
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        $('#timeDurationSection').hide();

        // hide leave duration field on initial load
        const selectedDuration = $('#leave_duration').val();
        if(selectedDuration==="full_day"){
            $('#timeDurationSection').hide();
        }else{
            $('#timeDurationSection').show();
        }

        $(document).on('change','#leave_duration',function() {
            $('#timeDurationSection').hide();
            const selectedOption = $(this).val();
            
            if (selectedOption === 'half_day') {
                $('#timeDurationSection').show();
            }
        });
    });
</script>