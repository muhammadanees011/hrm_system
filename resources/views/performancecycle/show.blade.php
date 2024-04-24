@extends('layouts.admin')
@section('page-title')
    {{ __('1st Quarter') }}
@endsection
@section('action-button')
    @can('Create Goal Tracking')
        <a href="#" data-url="{{ route('performancecycle.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Performance Cycle') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('1st Quarter') }}</li>
@endsection

@section('content')

<style>
    .nav-item{
        color:black;
        border-top:1px solid black;
        border-right:1px solid black;
        border-radius:1px;
    }
    .nav-item:hover{
        background-color:#EE204D;
    }
    .nav-item .active{
        border-radius:0px;
        background-color:#EE204D !important;
        color:white !important;
    }
    .nav-link{
        border:none;
        color:black;
    }
    .nav-link:hover{
        border:none;
        color:white !important;
    }
    .custom-icon{
        background-color:black;
        color:white;
        border-radius:10px;
    }
</style>

<div class="col-xl-12">
            <div class="card  text-center">
                <div class="card-header border-0 pb-0">
                    <div class="row d-flex justify-content-between align-items-center">

                        <div class="col-md-12">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs" id="myTabs">
                            <li class="nav-item">
                            <a class="nav-link active" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="false">
                                <i class="ti ti-user custom-icon"></i>                                    
                                <i class="ti ti-arrow-right"></i> 
                                Buddy
                            </a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" id="tab2-tab" data-bs-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">
                                <i class="ti ti-user custom-icon"></i>                                    
                                <i class="ti ti-arrow-up"></i> 
                                Management
                            </a>
                            </li>
                        </ul>
                        <div style="height:2rem;"></div>
                        <!-- Tab panes -->
                        <div class="tab-content mt-5 col-md-5" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                                <div class="d-flex justify-content-between">
                                    <p class="me-5">40%</p>
                                    <p class="me-5">15/24 reviews submitted</p>
                                </div>
                                <div class="progress progress-xs mt-2 w-100">
                                    <div class="progress-bar bg-{{ Utility::getProgressColor(40) }}"
                                        role="progressbar" aria-valuenow="40"
                                        aria-valuemin="0" aria-valuemax="100"
                                        style="width: 40%;"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                                <div class="d-flex justify-content-between">
                                    <p class="me-5">40%</p>
                                    <p class="me-5">12/24 reviews submitted</p>
                                </div>
                                <div class="progress progress-xs mt-2 w-100">
                                    <div class="progress-bar bg-{{ Utility::getProgressColor(40) }}"
                                        role="progressbar" aria-valuenow="40"
                                        aria-valuemin="0" aria-valuemax="100"
                                        style="width: 40%;"></div>
                                </div>
                            </div>
                        </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                    </div>
                </div>
        </div>


    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>
                                    <i class="ti ti-users"></i>    
                                    {{ __('Employees') }}
                                </th>
                                <th>
                                    <i class="ti ti-user custom-icon"></i>                                    
                                    <i class="ti ti-arrow-down"></i> 
                                    {{ __('Managers') }}
                                </th>
                                <th >
                                    <i class="ti ti-user custom-icon"></i>                                    
                                    <i class="ti ti-arrow-right"></i> 
                                    {{ __('BUDDY') }}
                                    (Reviews)
                                </th>
                                <th>
                                    <i class="ti ti-user custom-icon"></i>                                    
                                    <i class="ti ti-arrow-up"></i> 
                                    {{ __('MANAGEMENT') }}
                                    (Reviews)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i=0; $i< 5; $i++)
                            <tr>
                                <td style="background-color:#f5f5f5;">
                                    <a href="{{ route('performancecycle.reviews',1)}}" target="_blank" data-url="{{ route('performancecycle.reviews', 1) }}">
                                    <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 12%;width: 12%" />
                                        Muhammad Anees
                                    </a>
                                </td>
                                <td>
                                <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 12%;width: 12%" />
                                    Abeer Waseem
                                </td>
                                </td>                                    
                                <td>
                                    0/3
                                </td>
                                <td>
                                    1/3
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection


