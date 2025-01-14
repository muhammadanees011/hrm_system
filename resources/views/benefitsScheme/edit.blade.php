
{{ Form::model($PensionScheme, ['route' => ['benefits-schemes.update', $PensionScheme->id], 'method' => 'PUT']) }}
<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('scheme_name', __('Name'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                  
                    {{ Form::text('scheme_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Scheme Name')]) }}
                </div>
                @error('scheme_name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                {{ Form::label('contribution_percentage', __('Contribution Percentage'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('contribution_percentage', null, ['class' => 'form-control', 'placeholder' => __('Enter Contribution Percentage')]) }}
                </div>
                @error('contribution_percentage')
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
