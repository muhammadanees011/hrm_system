@extends('layouts.admin')

@section('page-title')
    {{ __('Benefits Requests') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Benefits Requests') }}</li>
@endsection

@section('action-button')
@if (\Auth::user()->type == 'employee')
<a href="{{ route('benefitsRequest.create') }}" data-title="{{ __('Create Benefit Request') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>
@endif


@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Benefit') }}</th>
                                <th>{{ __('Reason') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('Approve Benefits') || Gate::check('Reject Benefits'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td>{{ $request->employee->name }}</td>
                                    <td>{{ $request->benefit->scheme_name }}</td>
                                    <td>{{ $request->reason }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $request->status == 'Pending' ? 'bg-warning' : ($request->status == 'Approved' ? 'bg-success' : 'bg-secondary') }} 
                                            p-2 px-3 rounded mt-1">
                                            {{ __($request->status) }}
                                        </span>
                                    </td>
                                    <td class="Action">
                                        @if (Gate::check('Approve Benefits') || Gate::check('Reject Benefits'))
                                            <span>
                                                @can('Approve Benefits')
                                                    <div class="action-btn bg-success ms-2">
                                                        <a href="{{ route('benefitsRequest.approve', $request->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{ __('Approve') }}">
                                                            <i class="ti ti-check text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('Reject Benefits')
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="{{ route('benefitsRequest.reject', $request->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{ __('Reject') }}">
                                                            <i class="ti ti-close text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                            </span>
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
@endsection