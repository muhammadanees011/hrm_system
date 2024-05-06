@extends('layouts.admin')

@section('page-title')
{{ __("Manage Holiday Configuration") }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
<li class="breadcrumb-item">{{ __("Holiday Configuration") }}</li>
@endsection

@if(!$configuration)
@section('action-button')
@can('Create Holiday Configuration')
<a href="#" data-url="{{ route('holiday-configuration.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Holiday Configuration') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>
@endcan
@endsection
@endif

@section('content')
<div class="col-3">
    @include('layouts.hrm_setup')
</div>
<div class="col-9">
    <div class="card">
        <div class="card-body table-border-style">

            <div class="table-responsive">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>{{__('Annual Entitlement')}}</th>
                            <th>{{__('Total Annual Working Days')}}</th>
                            <th>{{__('Annual Renew Date')}}</th>
                            <th width="200px">{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @if($configuration)
                            <td>{{ $configuration->annual_entitlement }}</td>
                            <td>{{ $configuration->total_annual_working_days }}</td>
                            <td>{{ \Auth::user()->dateFormat($configuration->annual_renew_date) }}</td>
                            <td class="Action">
                                <span>
                                    @can('Edit Holiday Configuration')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ URL::to('holiday-configuration/' . $configuration->id . '/edit') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Holiday Configuration') }}" data-bs-original-title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan

                                    <!-- @can('Delete Holiday Configuration')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['holiday-configuration.destroy', $configuration->id], 'id' => 'delete-form-' . $configuration->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            @endcan -->
                                </span>
                            </td>
                            @else
                            <td>No record found!</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection