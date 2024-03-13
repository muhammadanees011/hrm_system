{{ Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'Enter Name']) !!}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter Email']) !!}
            </div>
        </div>

        @if (\Auth::user()->type != 'super admin')
            <div class="form-group ">
                {{ Form::label('role', __('User Role'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{-- {!! Form::select('role', $roles, $user->roles, ['class' => 'form-control select2 ', 'required' => 'required', 'multiple' => 'multiple']) !!} --}}
                    {{ Form::select('roles[]', $roles, $user->roles, ['class' => 'form-control select2', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}
                </div>
                @error('role')
                    <span class="invalid-role" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        @endif
        @if($user->hasRole('manager'))
            <div class="col-md-12 dep_div">
                <div class="form-group">
                    {{ Form::label('assigned_departments', __('Manager of Department'), ['class' => 'form-label']) }}
                    {{ Form::select('assigned_departments[]', $departments, !empty($user->assigned_departments) ? $user->assigned_departments : null, ['class' => 'form-control select2 user-role', 'id' => 'choices-multiple-1', 'multiple' => '', 'required' => 'required']) }}
                    @error('assigned_departments')
                        <small class="invalid-assigned_departments" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">

</div>
{!! Form::close() !!}


