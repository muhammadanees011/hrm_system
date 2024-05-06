{{ Form::model($holidayConfiguration, ['route' => ['holiday-configuration.update', $holidayConfiguration->id], 'method' => 'PUT']) }}

<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('annual_entitlement', __('Annual entitlement (in days)'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::number('annual_entitlement', null, ['class' => 'form-control', 'placeholder' => __('E.g. 28')]) }}
                </div>
                @error('annual_entitlement')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                {{ Form::label('total_annual_working_days', __('Total annual working days'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::number('total_annual_working_days', null, ['class' => 'form-control', 'placeholder' => __('E.g. 254')]) }}
                </div>
                @error('total_annual_working_days')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                {{ Form::label('annual_renew_date', __('Annual renew date'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::date('annual_renew_date', null, ['class' => 'form-control']) }}
                </div>
                @error('annual_renew_date')
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