@extends('layouts.admin')

@section('page-title')
    {{ __('Health Assessment') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Health Assessment ') }}</li>
@endsection


@section('action-button')
    <div class="row align-items-center m-1">
        @can('Create Health And Fitness')
            <a href="#" data-size="lg" data-url="{{ route('healthassessment.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create New Health Assessment') }}" data-title="{{ __('Create New Health Assessment') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class='col-xl-12'>
        <div class="row">
            <div class="col-xl-3">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{ __('Total Assessments') }}</h6>
                                <h3 class="text-primary">{{ $cnt_assessment['total'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-success text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{ __('This Month Total Assessments') }}</h6>
                                <h3 class="text-info">{{ $cnt_assessment['this_month'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-info text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{ __('This Week Total Assessments') }}</h6>
                                <h3 class="text-warning">{{ $cnt_assessment['this_week'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-warning text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{ __('Last 30 Days Total Assessments') }}</h6>
                                <h3 class="text-danger">{{ $cnt_assessment['last_30days'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-danger text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card table-card">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th width="60px">{{ __('#') }}</th>
                                        <th>{{ __('Employee Name') }}</th>
                                        <th>{{ __('Assessment Date') }}</th>
                                        <th>{{ __('Assessment Type') }}</th>
                                        <th>{{ __('Details') }}</th>
                                        <th width="150px">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($healthassessments as $healthassessment)
                                        <tr>
                                            <td class="Id">
                                                 {{ $healthassessment->id}}
                                            </td>
                                            <td>{{ $healthassessment->employee->name }}</td>
                                            <td>{{ $healthassessment->assessment_date }}</td>
                                            <td>{{ $healthassessment->assessment_type }}</td>
                                            <td>{!! \Illuminate\Support\Str::limit($healthassessment->assessment_result, 40) !!}</td>
                                            <td class="Action">
                                                <span>
                                                    {{-- @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr' || \Auth::user()->type == 'employee') --}}
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('healthassessment.show', $healthassessment->id) }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('View Health Assessment') }}"><i
                                                                class="ti ti-eye text-white"></i></a>
                                                    </div>
                                                    {{-- @endif --}}

                                                    @can('Edit Contract')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" data-size="lg"
                                                                data-url="{{ URL::to('healthassessment/' . $healthassessment->id . '/edit') }}"
                                                                data-ajax-popup="true" data-title="{{ __('Edit Health Assessment') }}"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Edit Health Assessment') }}"><i
                                                                    class="ti ti-pencil text-white"></i></a>
                                                        </div>
                                                    @endcan

                                                    @can('Delete Contract')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['healthassessment.destroy', $healthassessment->id]]) !!}
                                                            <a href="#!"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Delete Health Assessment') }}">
                                                                <span class="text-white"> <i class="ti ti-trash"></i></span>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endcan

                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
