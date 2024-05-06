@extends('layouts.admin')

@section('page-title')
{{ __('Manage Holiday') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Holidays List') }}</li>
@endsection

@section('action-button')
<a href="{{ route('holidays.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Export') }}">
    <i class="ti ti-file-export"></i>
</a>

@if (\Auth::user()->type != 'employee')
<a href="#" data-url="{{ route('holidays.file.import') }}" data-ajax-popup="true" data-title="{{ __('Import Holiday CSV file') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Import') }}">
    <i class="ti ti-file-import"></i>
</a>
@endif

<a href="{{ route('holiday.calender') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Calendar View') }}">
    <i class="ti ti-calendar"></i>
</a>

<a href="#" data-url="{{ route('holiday.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Holiday') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>
@endsection


@section('content')
<div class="col-sm-2">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$holidayConfig->annual_entitlement}} Days</h2>
                <span>Annual Entitlement</span>
            </div>
        </div>
    </div>
</div>
@if(\Auth::user()->type == 'employee')
<div class="col-sm-2">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$availableEntitlement}} Day(s)</h2>
                <span>Holiday Entitlement</span>
            </div>
        </div>
    </div>
</div>
@endif
<div class="col-sm-2">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$holidayPerMonth}} Days</h2>
                <span>Holidays Per Month</span>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-2">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$monthsSinceStart}}</h2>
                <span>Since Year Start</span>
                <br>
                <small>Renews at: {{ \Auth::user()->dateFormat($holidayConfig->annual_renew_date) }}</small>
            </div>
        </div>
    </div>
</div>
@if(\Auth::user()->type != 'employee')
<div class="col-sm-2">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$approvedHolidays}}/{{$rejectedHolidays}}</h2>
                <span>Total Approved/Rejected Holidays</span>
            </div>
        </div>
    </div>
</div>
@endif
<div class="col-sm-4">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => ['holiday.index'], 'method' => 'get', 'id' => 'holiday_filter']) }}
                <div class="row align-items-center justify-content-end">
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                    {{ Form::date('start_date', isset($_GET['start_date']) ? $_GET['start_date'] : '', ['class' => 'month-btn form-control current_date', 'autocomplete' => 'off']) }}
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                    {{ Form::date('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : '', ['class' => 'month-btn form-control current_date', 'autocomplete' => 'off']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="row">
                            <div class="col-auto mt-4">
                                <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('holiday_filter').submit(); return false;" data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('holiday.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                </a>
                            </div>
                        </div>
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
            {{-- <h5></h5> --}}
            <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>{{ __('Occasion') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('End Date') }}</th>
                            <th>{{ __('Total Days') }}</th>
                            @if (\Auth::user()->type != 'employee')
                            <th>{{ __('Requested by') }}</th>
                            @endif
                            <th>{{ __('Requested as') }}</th>
                            @if (Gate::check('Edit Holiday') || Gate::check('Delete Holiday'))
                            <th width="200px">{{ __('Action') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidays as $holiday)
                        <tr>
                            <td>{{ $holiday->occasion }}</td>
                            <td>{{ $holiday->status }}</td>
                            <td>{{ \Auth::user()->dateFormat($holiday->start_date) }}</td>
                            <td>{{ \Auth::user()->dateFormat($holiday->end_date) }}</td>
                            <td>{{ $holiday->total_days }}</td>
                            @if (\Auth::user()->type != 'employee')
                            <td>{{ $holiday->user->name }}</td>
                            @endif
                            <td>{{ $holiday->isNextYear ? 'Next year' : 'This year' }}</td>
                            @if (Gate::check('Edit Holiday') || Gate::check('Delete Holiday'))
                            <td class="Action">
                                <span>
                                    @if($holiday->user_id != auth()->id())
                                    <div class="action-btn bg-primary ms-2">
                                        <a href="{{ route('holiday.update.status', ['id' => $holiday->id, 'status' => 'Approved']) }}" class="mx-3 btn btn-sm  align-items-center" title="" data-title="{{ __('Approve Holiday') }}" data-bs-original-title="{{ __('Approve') }}">
                                            <i class="ti ti-thumb-up text-white"></i>
                                        </a>
                                    </div>
                                    <div class="action-btn bg-danger ms-2">
                                        <a href="{{ route('holiday.update.status', ['id' => $holiday->id, 'status' => 'Rejected']) }}" class="mx-3 btn btn-sm  align-items-center" title="" data-title="{{ __('Reject Holiday') }}" data-bs-original-title="{{ __('Reject') }}">
                                            <i class="ti ti-thumb-down text-white"></i>
                                        </a>
                                    </div>
                                    @endif

                                    @can('Edit Holiday')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('holiday.edit', $holiday->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Holiday') }}" data-bs-original-title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan

                                    @can('Delete Holiday')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['holiday.destroy', $holiday->id],
                                        'id' => 'delete-form-' . $holiday->id,
                                        ]) !!}
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                        </form>
                                    </div>
                                    @endcan
                                </span>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    $(document).ready(function() {
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });
</script>
@endpush