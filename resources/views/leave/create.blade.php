@php
    $setting = App\Models\Utility::settings();
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp
{{ Form::open(['url' => 'leave', 'method' => 'post']) }}
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
                    {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'id' => 'employee_id', 'placeholder' => __('Select Employee')]) }}
                </div>
            </div>
        </div>
    @else
        {{-- @foreach ($employees as $employee) --}}
        {!! Form::hidden('employee_id', !empty($employees) ? $employees->id : 0, ['id' => 'employee_id']) !!}
        {{-- @endforeach --}}
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'col-form-label']) }}
                <select name="leave_type_id" id="leave_type_id" class="form-control select">
                    <option value="">{{ __('Select Leave Type') }}</option>
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
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }}
                {{ Form::text('start_date', null, ['class' => 'form-control d_week current_date', 'autocomplete' => 'off', 'placeholder' => 'Select start date']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }}
                {{ Form::text('end_date', null, ['class' => 'form-control d_week current_date', 'autocomplete' => 'off', 'placeholder' => 'Select end date']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('leave_type_id', __('Leave Duration'), ['class' => 'col-form-label']) }}
                <select name="leave_duration" id="leave_duration" class="form-control select">
                    <option value="">{{ __('Select Leave Duration') }}</option>
                        <option value="half_day">Half Day </option>
                        <option value="full_day">Full Day </option>
                        <option value="full_day">1 Hour </option>
                        <option value="full_day">2 Hours </option>
                        <option value="full_day">3 Hours </option>

                </select>
            </div>
        </div>
    </div>
    <div class="row" id="timeDurationSection" style="display:none">
        <div class="form-group col-md-12">
            {{ Form::label('hours', __('No. Of Hours'), ['class' => 'form-label']) }}
            {{ Form::text('hours', null, ['class' => 'form-control ',  'placeholder' => 'Enter No. Of Hours']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('start_time', __('Start Time'), ['class' => 'form-label']) }}
            {{ Form::select('start_time', array_combine($hours, $hours), null, ['class' => 'form-control select2', 'id' => 'start_time', 'placeholder' => __('Select Start Time')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('end_time', __('End Time'), ['class' => 'form-label']) }}
            {{ Form::select('end_time', array_combine($hours, $hours), null, ['class' => 'form-control select2', 'id' => 'end_time', 'placeholder' => __('Select End Time')]) }}
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
    @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
        <div class="form-group col-md-6">
            {{ Form::label('synchronize_type', __('Synchroniz in Google Calendar ?'), ['class' => 'form-label']) }}
            <div class=" form-switch">
                <input type="checkbox" class="form-check-input mt-2" name="synchronize_type" id="switch-shadow"
                    value="google_calender">
                <label class="form-check-label" for="switch-shadow"></label>
            </div>
        </div>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
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

<script>
    $(document).ready(function() {
        setTimeout(() => {
            var employee_id = $('#employee_id').val();
            if (employee_id) {
                $('#employee_id').trigger('change');
            }
        }, 100);
    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('change','#leave_duration',function() {
            const selectedOption = $(this).val();
            console.log(selectedOption);
            if (selectedOption === 'half_day') {
                $('#timeDurationSection').show();
            } else if(selectedOption === 'full_day') {
                $('#timeDurationSection').hide();
            } else {
                $('#timeDurationSection').hide();
            }
        });
    });
</script>