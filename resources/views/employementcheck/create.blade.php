{{ Form::open(['url' => 'employementcheck', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6 mt-3">
            {{ Form::label('employementchecktype', __('Employement Check Type'), ['class' => 'form-label']) }}
            {{ Form::select('employementchecktype', $employementchecktypes, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'required' => 'required']) }}

        </div>
        <div class="form-group col-md-6">
            <input type="file" class="form-control" name="file"
            accept="pdf/*" >
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
