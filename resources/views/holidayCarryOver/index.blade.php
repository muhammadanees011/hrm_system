@extends('layouts.admin')

@section('page-title')
{{ __('Manage Holiday CarryOvers') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Holiday CarryOvers List') }}</li>
@endsection

@section('action-button')
    @can('Create Holiday CarryOver')
    <a href="#" data-url="{{ route('holiday-carryover.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Holiday CarryOver') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
        <i class="ti ti-plus"></i>
    </a>
    @endcan
@endsection


@section('content')
@if (\Auth::user()->type == 'employee')
<div class="col-sm-4">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$availableEntitlement}} Day(s)</h2>
                <span>Holiday Entitlement</span>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$totalApprovedCarryOverDays}} Day(s)</h2>
                <span>Last years' CarryOver</span>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$totalApprovedCarryOverDaysThisYear}} Day(s)</h2>
                <span>This years' CarryOver</span>
            </div>
        </div>
    </div>
</div>
@else
<div class="col-sm-4">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$carryOversCount}}</h2>
                <span>Total CarryOvers</span>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$totalApproved}}</h2>
                <span>Total Approved CarryOvers</span>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="mt-2" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                <h2>{{$totalRejected}}</h2>
                <span>Total Rejected CarryOvers</span>
            </div>
        </div>
    </div>
</div>
@endif

<div class="col-xl-12">
    <div class="card">
        <div class="card-header card-body table-border-style">
            <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            @if (\Auth::user()->type != 'employee')
                            <th>{{ __('Requested by') }}</th>
                            @endif
                            <th>{{ __('Total Days') }}</th>
                            <th>{{ __('Status') }}</th>
                            @if (Gate::check('Edit Holiday') || Gate::check('Delete Holiday'))
                            <th width="200px">{{ __('Action') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidayCarryOvers as $holiday)
                        <tr>
                            @if (\Auth::user()->type != 'employee')
                            <td>{{ $holiday->user->name }}</td>
                            @endif
                            <td>{{ $holiday->total_days }}</td>
                            <td>{{ $holiday->status }}</td>
                            @if (Gate::check('Edit Holiday') || Gate::check('Delete Holiday'))
                            <td class="Action">
                                <span>
                                    @if($holiday->user_id != auth()->id())
                                    <div class="action-btn bg-primary ms-2">
                                        <a href="{{ route('holiday-carryover.update.status', ['id' => $holiday->id, 'status' => 'Approved']) }}" class="mx-3 btn btn-sm  align-items-center" title="" data-title="{{ __('Approve Holiday CarryOver') }}" data-bs-original-title="{{ __('Approve') }}">
                                            <i class="ti ti-thumb-up text-white"></i>
                                        </a>
                                    </div>
                                    <div class="action-btn bg-danger ms-2">
                                        <a href="{{ route('holiday-carryover.update.status', ['id' => $holiday->id, 'status' => 'Rejected']) }}" class="mx-3 btn btn-sm  align-items-center" title="" data-title="{{ __('Reject Holiday CarryOver') }}" data-bs-original-title="{{ __('Reject') }}">
                                            <i class="ti ti-thumb-down text-white"></i>
                                        </a>
                                    </div>
                                    @endif

                                    @can('Edit Holiday CarryOver')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('holiday-carryover.edit', $holiday->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Holiday CarryOver') }}" data-bs-original-title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan

                                    @can('Delete Holiday CarryOver')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['holiday-carryover.destroy', $holiday->id],
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