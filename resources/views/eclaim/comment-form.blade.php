{{ Form::open(['url' => $url, 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('comment', __('Comment'),['class'=>'col-form-label']) }}
                {{ Form::textarea('comment', '', array('class' => 'form-control', 'required' => 'required', 'rows' => '3')) }}
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Save') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
