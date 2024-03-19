@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Company Goal Tracking') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Company Goal Tracking') }}</li>
@endsection

@section('action-button')
    @can('Create Goal Tracking')
        <a href="#" data-url="{{ route('companygoaltracking.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Role') }}"
            data-bs-toggle="tooltip" title="" data-size="lg" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <style>
        .goal-tracking-card{
            display:flex;
            justify-content:center;
            overflow-x:scroll;
            scrollbar-width: none; 
            -ms-overflow-style: none; 
        }
        .goal-tracking-card::-webkit-scrollbar {
            display: none;
        }
        .goal-table {
            width: 100%;
         
        }
        .goal-table th{
            font-weight:600;
        }
        .goal-table tr{
            width: 100%;
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-left:2rem;
            margin-right:2rem;
        }
        .goal-table tr td{
            width: 10rem;
            display:flex;
            justify-content:start;
            align-items:center;
        }
        .goal-table tr th{
            width: 10rem;
            display:flex;
            justify-content:start;
            align-items:center;
        }
        .goal-progress{
            width:10rem;
        }
        .project-logo{
            height:1.8rem;
            width:1.8rem;
            border-radius:2rem;
            background-color:#eedc82;
            display:flex;
            justify-content:center;
            align-items:center;
            margin-right:0.5rem;
            
        }
        .user-img{
            width:3rem;
        }
    </style>
    <div class="col-xl-12">
        <div class="card goal-tracking-card">
            <div class="card-header card-body table-border-style">
            <table class="goal-table" >
                <thead>
                    <tr>
                        <th>{{ __('Project') }}</th>
                        <th>{{ __('Member') }}</th>
                        <th>{{ __('Tasks') }}</th>
                        <th>{{ __('Time Spent') }}</th>
                        <th>{{ __('Percent Usage') }}</th>
                        <th width="200px">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                @for ($i = 0; $i < 4; $i++)
                    <tr>
                        <td class="name-col" style="">
                            <div class="project-logo text-white">
                                D
                            </div>
                            <div class="name" style="">
                                Design Work
                            </div>
                        </td>
                        <td >
                            <div class="user-img">
                                <img class="rounded-circle" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="width: 80%" />
                            </div>
                            <!-- <div class="name" style=""> -->
                                <a href="#" class="">Muhammad Anees</a>
                            <!-- </div> -->
                        </td>
                        <td style="white-space: inherit;">
                            <p>
                                <a href="#" class="">HRMPRO Design</a>
                            </p>
                        </td>
                        <td style="white-space: inherit;">
                            <p>
                                <a href="#" class="">01:06:22</a>
                            </p>
                        </td>
                        <td >
                            <div class="progress-wrapper goal-progress">
                                <span class="progress-percentage"><small
                                        class="font-weight-bold"></small>56%</span>
                                <div class="progress progress-xs mt-2 w-100">
                                    <div class="progress-bar bg-{{ Utility::getProgressColor(56) }}"
                                        role="progressbar" aria-valuenow="{{ 56 }}"
                                        aria-valuemin="0" aria-valuemax="100"
                                        style="width: {{ 56 }}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td class="Action" style="display:flex; align-item:start;">
                            <span>
                                @can('Edit Role')
                                    <div class="action-btn bg-info ms-2">
                                        <a class="mx-3 btn btn-sm  align-items-center"
                                            data-url=""
                                            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                                            title="" data-title="{{ __('Edit Role') }}"
                                            data-bs-original-title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                @endcan

                                @can('Delete Role')
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
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
            </div>
        </div>
    </div>
@endsection
