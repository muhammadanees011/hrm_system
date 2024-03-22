@extends('layouts.admin')
@section('page-title')
    {{ __("People's Goals") }}
@endsection
@section('action-button')
    @can('Create Goal Tracking')
        <a href="#" data-url="{{ route('goaltracking.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Goal Tracking') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __("People's Goals") }}</li>
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Employement Type') }}</th>
                                <th>{{ __('Overall Progress') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Visibility') }}</th>
                                <th>{{ __('Last Checkin') }}</th>
                                <th>{{ __('Tags') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($goalTrackings as $goalTracking)
                                <tr>
                                    <td>
                                        <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 15%;width: 15%" />
                                        {{ !empty($goalTracking->employee) ? $goalTracking->employee->name : '' }}
                                    </td>
                                    <td>{{ !empty($goalTracking->employee) ? $goalTracking->employee->department->name : '' }}
                                    <td>{{ !empty($goalTracking->employee) ? $goalTracking->employee->employee_type : '' }}

                                    <td>
                                        <div class="progress-wrapper">
                                            <span class="progress-percentage"><small
                                                    class="font-weight-bold"></small>{{ $goalTracking->progress }}%</span>
                                            <div class="progress progress-xs mt-2 w-100">
                                                <div class="progress-bar bg-{{ Utility::getProgressColor(40) }}"
                                                    role="progressbar" aria-valuenow="40"
                                                    aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 40%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <div class="goals-status text-white d-flex justify-content-center" style="background-color:green; width:23px;height:23px; border-radius:15px;">
                                                5
                                            </div>
                                            <div class="goals-status text-white d-flex justify-content-center" style="background-color:red; width:23px;height:23px; border-radius:15px;">
                                                3
                                            </div>
                                            <div class="goals-status text-white d-flex justify-content-center" style="background-color:orange; width:23px;height:23px; border-radius:15px;">
                                                2
                                            </div>
                                        </div>
                                    </td>
                                    <td>Shared, 1 personal</td>
                                    <td>13/05/2021</td>
                                    <td>Professional goal</td>
                                    
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
