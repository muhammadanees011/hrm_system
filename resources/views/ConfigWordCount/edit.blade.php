
{{ Form::model($jobWordCount, ['route' => ['config-word-count.update', $jobWordCount->id], 'method' => 'PUT']) }}
<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('limit', __('Limit'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('limit', null, ['class' => 'form-control', 'placeholder' => __('Enter Word limit')]) }}
                </div>
                @error('limit')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
