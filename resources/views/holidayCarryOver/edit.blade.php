{{ Form::model($holidayCarryOver, ['route' => ['holiday-carryover.update', $holidayCarryOver->id], 'method' => 'PUT']) }}
<div class="modal-body">

    <div class="row">
        <div class="row col-md-12">
            {{ Form::label('total_days', __('Total Days'), ['class' => 'col-form-label']) }}
            {{ Form::number('total_days', null, ['class' => 'form-control ']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}