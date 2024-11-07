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
@forelse($employees as $employee)
        <div class="col-xl-3">
            <div class="card  text-center">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">

                        </h6>
                    </div>
                    <div class="card-header-right">
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="avatar">
                        <a href="{{ !empty($employee->user->avatar) ? asset(Storage::url('uploads/avatar')) . '/' . $employee->user->avatar : asset(Storage::url('uploads/avatar')) . '/avatar.png' }}"
                            target="_blank">
                            <!-- <img src="{{ !empty($employee->user->avatar) ? asset(Storage::url('uploads/avatar')) . '/' . $employee->user->avatar : asset(Storage::url('uploads/avatar')) . '/avatar.png' }}"
                                class="rounded-circle" style="width: 25%"> -->
                                <img src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}" class="rounded-circle"  style="width: 25%" />

                        </a>

                    </div>
                    <h4 class="mt-2 text-primary">{{ $employee->name }}</h4>
                    <div><small class="">{{ $employee->email ?? '' }}</small></div>
                    <div><small class="">{{ ucfirst($employee->department->name ?? "") }}</small></div>
                    <small
                        class="">{{ ucfirst($employee->designation->name ?? '') }}</small>

                    
                </div>
            </div>
        </div>
    @endforeach
@endsection