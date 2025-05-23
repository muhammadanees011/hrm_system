{{ Form::open(['url' => 'eclaim', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">

    <div class="row">
        @if (\Auth::user()->type != 'employee')
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('employee_id', __('Employee*'), ['class' => 'col-form-label']) }}
                    {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'id' => 'employee_id', 'placeholder' => __('Select Employee')]) }}
                </div>
            </div>
        @else
            {{-- @foreach ($employees as $employee) --}}
            {!! Form::hidden('employee_id', !empty($employees) ? $employees->id : 0, ['id' => 'employee_id']) !!}
            {{-- @endforeach --}}
        @endif
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('type_id', __('Eclaim Type*'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::select('type_id', $eClaimTypes, null, ['class' => 'form-control select2 ', 'required' => 'required', 'placeholder' => __('Select Eclaim Type')]) }}
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('amount', __('Amount*'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::number('amount', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Amount')]) }}
                </div>
                @error('amount')
                    <span class="invalid-amount" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('receipt', __('Receipt (Image only)'), ['class' => 'col-form-label']) }}
                <div class="choose-files ">
                    <label for="receipt">
                        <div class=" bg-primary receipt "> <i
                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                        </div>
                        <input style="margin-top: -50px" type="file" class="form-control file" name="receipt"  onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                        <img id="blah" class="mt-3"  width="100" src="" />
                    </label>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('description*', __('Description'),['class'=>'col-form-label']) }}
                {{ Form::textarea('description', '', array('class' => 'form-control', 'rows' => '3')) }}
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
