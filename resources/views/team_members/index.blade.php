@extends('layouts.admin')

@section('page-title')
{{ __('Manage Team Memebers') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('teams.index') }}">{{ __('Teams') }}</a></li>
<li class="breadcrumb-item">{{ __('Members') }}</li>
@endsection

@section('action-button')

@can('Create Team')
@endcan

<a href="#" data-url="{{ route('teams.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Team') }}"
    data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>

@endsection

@section('content')
<div class="col-3">
    @include('layouts.hrm_setup')
</div>
<div class="col-9">
    <div class="row">
    <div class="col-lg-6">
        <div class="card  text-center">
            <div class="card-header border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <div class="badge p-2 px-3 rounded bg-primary">{{ 'User 1' }}</div>
                    </h6>
                </div>
                <div class="card-header-right">
                    <div class="btn-group card-option">
                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="feather icon-more-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item" data-url="{{ '' }}" data-ajax-popup="true"
                                data-title="{{ __('Update User') }}"><i class="ti ti-edit "></i><span
                                    class="ms-2">{{ __('Edit') }}</span></a>
                            <a href="#" class="dropdown-item" data-ajax-popup="true"
                                data-title="{{ __('Change Password') }}" data-url="{{ '' }}"><i class="ti ti-key"></i>
                                <span class="ms-1">{{ __('Reset Password') }}</span>
                            </a>

                            <!-- User Logged In -->
                            <a href="{{ '' }}" class="dropdown-item">
                                <i class="ti ti-road-sign"></i>
                                <span class="text-danger"> {{ __('Login Disable') }}</span>
                            </a>
                            <!-- User Not Logged In -->
                            <a href="#" data-url="{{ '' }}" data-ajax-popup="true" data-size="md"
                                class="dropdown-item login_enable" data-title="{{ __('New Password') }}"
                                class="dropdown-item">
                                <i class="ti ti-road-sign"></i>
                                <span class="text-success"> {{ __('Login Enable') }}</span>
                            </a>
                            <!-- Else User -->
                            <a href="{{ ''}}" class="dropdown-item">
                                <i class="ti ti-road-sign"></i>
                                <span class="text-success"> {{ __('Login Enable') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="avatar">
                    <a href="{{ asset(Storage::url('uploads/avatar/avatar.png')) }}" target="_blank">

                        <img src="{{asset('/assets/images/user/avatar-4.jpg')}}" alt="{{ env('APP_NAME') }}"
                            class="rounded-circle" style="width: 30%" />

                    </a>
                </div>
                <h4 class="mt-2 text-primary">{{ '$user->name' }}</h4>
                <small>{{ '$user->email' }}</small>
                <div class=" mb-0 mt-3">
                    <div class=" p-3">
                        <div class="row">
                            <div class="col-5 text-start">
                                <h6 class="mb-0 px-3 mt-1 text-nowrap">
                                    User Current Plan
                                </h6>
                            </div>
                            <div class="col-7 text-end">
                                <a href="#" data-url="{{ '' }}" class="btn btn-sm btn-primary btn-icon" data-size="lg"
                                    data-ajax-popup="true"
                                    data-title="{{ __('Upgrade Plan') }}">{{ __('Upgrade Plan') }}</a>
                            </div>
                            <div class="col-6 text-start mt-4">
                                <h6 class="mb-0 px-3">{{ '\Auth::user()->countUsers()' }}</h6>
                                <p class="text-muted text-sm mb-0">{{ __('Users') }}</p>
                            </div>
                            <div class="col-6 text-end mt-4">
                                <h6 class="mb-0 px-3">{{ '\Auth::user()->countEmployees() '}}</h6>
                                <p class="text-muted text-sm mb-0">{{ __('Employees') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="mt-2 mb-0">
                    <button class="btn btn-sm btn-neutral mt-3 font-weight-500">
                        <a>{{ __('Plan Expire : ') }}
                            {{  'Unlimited' }}</a>
                    </button>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <a href="#" class="btn-addnew-project " data-ajax-popup="true" data-url="{{ route('user.create') }}"
            data-title="{{ __('Create New User') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <div class="bg-primary proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{ __('New User') }}</h6>
            <p class="text-muted text-center">{{ __('Click here to add new user') }}</p>
        </a>
    </div>
    </div>
</div>
@endsection