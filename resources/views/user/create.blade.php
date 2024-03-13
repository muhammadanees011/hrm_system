{!! Form::open(['route' => 'user.store', 'method' => 'post']) !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter Name']) !!}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'Enter Email']) !!}
            </div>
        </div>
        {{-- <div class="form-group">
            {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {!! Form::password('password', ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter password']) !!}
            </div>
        </div> --}}
        @if (\Auth::user()->type != 'super admin')
            <div class="form-group">
                {{ Form::label('roles', __('User Role'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{-- {!! Form::select('role', $roles, null, ['class' => 'form-control select2 ', 'required' => 'required']) !!} --}}
                    {{ Form::select('roles[]', $roles, null, ['class' => 'form-control select2 user-role', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}
                </div>
                @error('role')
                    <span class="invalid-role" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        @endif
        <div class="col-md-5 mb-3">
            <label for="password_switch">{{ __('Login is enable') }}</label>
            <div class="form-check form-switch custom-switch-v1 float-end">
                <input type="checkbox" name="password_switch" class="form-check-input input-primary pointer"
                    value="on" id="password_switch">
                <label class="form-check-label" for="password_switch"></label>
            </div>
        </div>
        <div class="col-md-12 ps_div d-none">
            <div class="form-group">
                {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Password'), 'minlength' => '6']) }}
                @error('password')
                    <small class="invalid-password" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                @enderror
            </div>
        </div>
        <div class="col-md-12 dep_div d-none">
            <div class="form-group">
                {{ Form::label('assigned_departments', __('Manager of Department'), ['class' => 'form-label']) }}
                {{ Form::select('assigned_departments[]', $departments, null, ['class' => 'form-control select2 manager-department', 'id' => 'choices-multiple-1', 'multiple' => '', 'required' => 'required']) }}
                @error('assigned_departments')
                    <small class="invalid-assigned_departments" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                @enderror
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">

</div>
{!! Form::close() !!}

