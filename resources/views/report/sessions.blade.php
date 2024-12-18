@extends('layouts.admin')
@section('page-title')
    {{ __('Sessions Report') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Sessions Report') }}</li>
@endsection

@section('content')
    <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
        <div class=" mt-2 " id="multiCollapseExample1" style="">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['report.sessions'], 'method' => 'get', 'id' => 'timesheet_filter']) }}
                    <div class="d-flex align-items-center">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                {{ Form::text('start_date', $startDate, ['class' => 'month-btn form-control d_week current_date start_date', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                {{ Form::text('end_date', $endDate, ['class' => 'month-btn form-control d_week current_date end_date', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                            <div class="btn-box" id="team_div">
                                {{ Form::label('team', __('Teams'), ['class' => 'form-label']) }}
                                {{ Form::select('team', $teams->pluck('name', 'id'), $selectedTeamId, [
                                    'class' => 'form-control select2',
                                    'placeholder' => __('Select Team')
                                ]) }}
                            </div>
                        </div>
                        <div class="col-auto float-end ms-2 mt-4 text-end">
                            <a href="#" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('timesheet_filter').submit(); return false;"
                                data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('report.sessions') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                title="" data-bs-original-title="Reset">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                            </a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div> 

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    @if (\Auth::user()->type != 'employee')
                                        <th>{{ __('User Id') }}</th>
                                        <th>{{ __('Name') }}</th>
                                    @endif
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('IP Address') }}</th>
                                    <th>{{ __('Sessions') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($sessions as $session)
                            <tr>
                                <td>{{ $session->user_id ?? 'Guest' }}</td>
                                <td>{{ $session->name ?? 'Unknown' }}</td>
                                <td>{{ $session->email ?? 'N/A' }}</td>
                                <td>{{ $session->ip_address }}</td>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->toDateTimeString() }}</td>
                                <td>
                                    @if ($session->last_activity > now()->subMinutes(5)->timestamp)
                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                    @endif
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
@endsection
