@extends('layouts.admin')
@php
$profile = \App\Models\Utility::get_file('uploads/avatar/');
$initial = strtoupper(substr($userDetail->name, 0, 2));
@endphp

@push('script-page')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        });
        function handleChange(e){
            const placeholder = window.URL.createObjectURL(e.target.files[0]);
            const imgtag = document.querySelector('.profileimagecontainer #blah');
            const usernamePlaceholder = document.querySelector('.profileimagecontainer .img-placeholder');
            if (imgtag) {
                imgtag.src =placeholder
            }else{
                usernamePlaceholder.classList.add('d-none');
                const img = new Image();
                img.src = placeholder;
                img.id = 'blah';
                img.width = 100;
                img.style = 'border-radius: 50%; aspect-ratio: 1/1;'
                document.querySelector('.profileimagecontainer label').appendChild(img);
            }
        }
    </script>
@endpush
@section('page-title')
    {{ __('Profile') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0"> {{ __('Profile') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Profile') }}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        <a href="#useradd-1"
                            class="list-group-item list-group-item-action border-0">{{ __('Personal Info') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-2"
                            class="list-group-item list-group-item-action border-0">{{ __('Change Password') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                    </div>
                </div>
            </div>


            <div class="col-xl-9">
                <div id="useradd-1">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Personal Information') }}</h5>
                            <small> {{ __('Details about your personal information') }}</small>
                        </div>
                        <div class="card-body">
                            {{ Form::model($userDetail, ['route' => ['update.account'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label text-dark">{{ __('Name') }}</label>
                                        <input class="form-control @error('name') is-invalid @enderror" name="name"
                                            type="text" id="name" placeholder="{{ __('Enter Your Name') }}"
                                            value="{{ $userDetail->name }}" required autocomplete="name">
                                        @error('name')
                                            <span class="invalid-feedback text-danger text-xs"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="email" class="col-form-label text-dark">{{ __('Email') }}</label>
                                        <input class="form-control @error('email') is-invalid @enderror" name="email"
                                            type="text" id="email" placeholder="{{ __('Enter Your Email Address') }}"
                                            value="{{ $userDetail->email }}" required autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback text-danger text-xs"
                                                role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('profile', __('Avatar'), ['class' => 'col-form-label']) }}
                                        <div class="choose-files profileimagecontainer">
                                            <label for="profile">
                                                <div class=" bg-primary profile "> <i
                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                </div>
                                                <input type="file" class="form-control file" name="profile" id="profile" onchange="handleChange(event)">

                                                @if (!empty($userDetail) && $userDetail->avatar != 'avatar.png' && $userDetail->avatar != "")
                                                <img id="blah"  width="100" style="border-radius: 50%" src="{{ !empty($userDetail->avatar) ? $profile . $userDetail->avatar : asset('assets/images/user/avatar-1.jpg') }}" />
                                                @else

                                                    <div class="img-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center fs-5" style="width: 64px; height: 64px;">{{ $initial }}</div>
                                                @endif

                                                
                                            </label>
                                        </div>
                                        <span
                                        class="text-xs text-muted">{{ __('Please upload a valid image file. Size of image should not be more than 2MB.') }}</span>
                                    @error('profile')
                                        <span class="invalid-feedback text-danger text-xs"
                                            role="alert">{{ $message }}</span>
                                    @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 text-end">
                                    <input type="submit" value="{{ __('Save Changes') }}"
                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                </div>
                            </div>
                            </form>

                        </div>

                    </div>
                </div>

                <div id="useradd-2">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Change Password') }}</h5>
                            <small> {{ __('Details about your account password change') }}</small>
                        </div>
                        <div class="card-body">
                            {{ Form::model($userDetail, ['route' => ['update.password', $userDetail->id], 'method' => 'post']) }}

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('current_password', __('Current Password'), ['class' => 'col-form-label text-dark']) }}
                                        {{ Form::password('current_password', ['class' => 'form-control', 'placeholder' => __('Enter Current Password')]) }}
                                        @error('current_password')
                                            <span class="invalid-current_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('new_password', __('New Password'), ['class' => 'col-form-label text-dark']) }}
                                        {{ Form::password('new_password', ['class' => 'form-control', 'placeholder' => __('Enter New Password')]) }}
                                        @error('new_password')
                                            <span class="invalid-new_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('confirm_password', __('Re-type New Password'), ['class' => 'col-form-label text-dark']) }}
                                        {{ Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => __('Enter Re-type New Password')]) }}
                                        @error('confirm_password')
                                            <span class="invalid-confirm_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer pr-0">
                                {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
@endsection
