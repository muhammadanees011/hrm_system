@extends('layouts.admin')

@section('page-title')
{{ __("Manage Pension Opt-ins") }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
<li class="breadcrumb-item">{{ __("Opt-ins") }}</li>
@endsection

@section('action-button')
@can('Create Pension OptIn')
<a href="{{ route('pension-opt-ins.create') }}" data-title="{{ __('Create Opt-in') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>
@endcan
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>{{__('Employee')}}</th>
                            <th>{{__('Pension Scheme')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Status')}}</th>
                            <th width="200px">{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($optIns as $optIn)
                        <tr>
                            <td>{{ $optIn->employee->name }}</td>
                            <td>{{ $optIn->pensionScheme->scheme_name }}</td>
                            <td>{{ \Auth::user()->dateFormat($optIn->date) }}</td>
                            <td>{{ $optIn->status }}</td>
                            <td class="Action">
                                <span>
                                    @can('Edit Pension OptIn')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="{{ route('pension-opt-ins.edit', $optIn->id) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan

                                    @can('Delete Pension OptIn')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['pension-opt-ins.destroy', $optIn->id], 'id' => 'delete-form-' . $optIn->id]) !!}
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                        </form>
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
@endsection