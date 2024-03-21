{{ Form::model($flexiTime, ['route' => ['flexi-time.update', $flexiTime->id], 'method' => 'put', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            @if (\Auth::user()->type != 'employee')
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('employee_id', __('Employeess'), ['class' => 'form-label']) }}
                        {{ Form::select('employee_id', $employees, $flexiTime->employee_id, ['class' => 'form-control select2', 'id' => 'employee_id', 'placeholder' => __('Select Employee')]) }}
                    </div>
                </div>
            @else
                {!! Form::hidden('employee_id', !empty($employees) ? $employees->id : 0, ['id' => 'employee_id']) !!}
            @endif
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('hours', __('No. Of Hours'), ['class' => 'form-label']) }}
            {{ Form::text('hours', $flexiTime->hours, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Enter No. Of Hours']) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
            {{ Form::text('start_date', $flexiTime->start_date, ['class' => 'month-btn form-control d_week current_date start_date', 'autocomplete' => 'off']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
            {{ Form::text('end_date', $flexiTime->end_date, ['class' => 'month-btn form-control d_week current_date end_date', 'autocomplete' => 'off']) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('start_time', __('Start Time'), ['class' => 'form-label']) }}
            {{ Form::select('start_time', $hours, $flexiTime->start_time, ['class' => 'form-control select2', 'id' => 'start_time', 'placeholder' => __('Select Start Time')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('end_time', __('End Time'), ['class' => 'form-label']) }}
            {{ Form::select('end_time', $hours, $flexiTime->end_time, ['class' => 'form-control select2', 'id' => 'end_time', 'placeholder' => __('Select End Time')]) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('remark', __('Remark'), ['class' => 'form-label']) }}
            {{ Form::textarea('remark', $flexiTime->remark, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Enter Remark', 'rows' => 3, 'id'=>'remark']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
