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
                            <a class="nav-link active" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">
                                <i class="ti ti-users"></i>    
                                Staff
                            </a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" id="tab2-tab" data-bs-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">
                                <i class="ti ti-user custom-icon"></i>                                    
                                <i class="ti ti-arrow-down"></i> 
                                Manager
                            </a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" id="tab3-tab" data-bs-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">
                                <i class="ti ti-user custom-icon"></i>                                    
                                <i class="ti ti-arrow-right"></i> 
                                Peer
                            </a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" id="tab3-tab" data-bs-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false">
                                <i class="ti ti-user custom-icon"></i>                                    
                                Buddy
                            </a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" id="tab3-tab" data-bs-toggle="tab" href="#tab5" role="tab" aria-controls="tab5" aria-selected="false">
                                <i class="ti ti-user custom-icon"></i>                                    
                                <i class="ti ti-arrow-up"></i> 
                                Upward
                            </a>
                            </li>
                        </ul>
                        <div style="height:2rem;"></div>
                        <!-- Tab panes -->
                        <div class="tab-content mt-5 col-md-5" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
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
                            <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
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
                            <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
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
                            <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab5-tab">
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
                                    {{ __('Peers') }}
                                    (Anonymous)
                                </th>
                                <th>
                                    <i class="ti ti-user custom-icon"></i>                                    
                                    <i class="ti ti-arrow-up"></i> 
                                    {{ __('Direct Reports') }}
                                    (Anonymous)
                                </th>
                                <th>
                                    <i class="ti ti-user"></i>                                    
                                    {{ __('Buddy') }}
                                </th>
                                @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
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
                                <td>
                                    3/3
                                </td>
                                <td class="Action">
                                    @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                        <span>

                                            @can('Edit Goal Tracking')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-size="lg"
                                                        data-url=""
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Edit Performance') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Goal Tracking')
                                                <div class="action-btn bg-danger ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="Delete" aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            @endcan
                                        </span>
                                    @endif
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


