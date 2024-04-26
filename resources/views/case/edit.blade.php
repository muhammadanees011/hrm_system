{{ Form::model($voiceCase, ['route' => ['case.update', $voiceCase->id], 'method' => 'PUT']) }}
<div class="modal-body">

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="form-group">
            {{ Form::label('title', __('Case Title'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Case title')]) }}
            </div>
            @error('title')
            <span class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            {{ Form::label('category_id', __('Category'), ['class' => 'form-label']) }}
            {{ Form::select('category_id', $categories, null, ['class' => 'form-control select2', 'required' => 'required']) }}
            @error('category_id')
            <span class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            {{ Form::label('representative', __('Representative'), ['class' => 'form-label']) }}
            {{ Form::select('representative', $representatives, null, ['class' => 'form-control select2', 'required' => 'required']) }}
            @error('representative')
            <span class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-check custom-checkbox">
            <input type="checkbox" class="form-check-input" name="is_private" value="true" id="check-private" {{$voiceCase->is_private ? 'checked' : ''}}>
            <label class="form-check-label" for="check-private">{{ __('Filed as privately') }} </label>
        </div>
    </div>
</div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
