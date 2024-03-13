@extends('layouts.admin')

@section('page-title')
   {{ __('Manage Eclaims') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Eclaim') }}</li>
@endsection

@section('action-button')
   @can('Create Eclaim')
        <a href="#" data-url="{{ route('eclaim.create') }}" data-ajax-popup="true"
            data-title="{{ __('Request A New Eclaimss') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">

                    <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                @if(\Auth::user()->type !="employee")
                                    <th>{{ __('Employee ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                @endif
                                <th>{{ __('Eclaim Type') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Requested Date') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eclaims as $eclaim)
                                <tr>
                                    @if(\Auth::user()->type !="employee")
                                        <td>
                                            <a href="#" class="btn btn-outline-primary">{{ \Auth::user()->employeeIdFormat($eclaim->employee_id) }}</a>
                                        </td>
                                        <th>{{ $eclaim->employee->name }}</th>
                                    @endif
                                    <td>{{ $eclaim->claimType->title }}</td>
                                    <td>{{env('CURRENCY_SYMBOL') ?? '£'}}{{ number_format($eclaim->amount, 2) }}</td>
                                    <td>{{ $eclaim->description }}</td>
                                    <td>
                                        @if($eclaim->status=="pending")
                                            <button class="btn bg-warning btn-sm">{{ucfirst($eclaim->status)}}</button>
                                        @elseif($eclaim->status=="approved by HR")
                                            <button class="btn bg-info btn-sm">{{ucfirst($eclaim->status)}}</button>
                                        @elseif($eclaim->status=="approved")
                                            <button class="btn bg-success btn-sm">{{ucfirst($eclaim->status)}}</button>
                                        @else
                                            <button class="btn bg-danger btn-sm">{{ucfirst($eclaim->status)}}</button>
                                        @endif
                                    </td>
                                    <td>{{\Auth::user()->dateFormat($eclaim->created_at)}}</td>
                                    <td class="Action">
                                        <span>
                                            @can('Edit Eclaim')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('eclaim/' . $eclaim->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Eclaim') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Eclaim')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['eclaim.destroy', $eclaim->id], 'id' => 'delete-form-' . $eclaim->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            @endcan

                                            @can('Manage Eclaim')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm bg-info  align-items-center"
                                                    data-url="{{ URL::to('eclaim/showReceipt/' . $eclaim->id ) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Eclaim Receipt') }}"
                                                        data-bs-original-title="{{ __('View Receipt') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>

                                                @if(\Auth::user()->type=="hr" || \Auth::user()->type=="company")
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm bg-danger  align-items-center"
                                                            data-url="{{ URL::to('eclaim/' . $eclaim->id . '/reject') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Reject Eclaim') }}"
                                                            data-bs-original-title="{{ __('Reject') }}">
                                                            <i class="ti ti-trash-off text-white"></i>
                                                        </a>
                                                    </div>
                                                    <div class="action-btn bg-success ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm bg-success  align-items-center"
                                                            data-url="{{ URL::to('eclaim/' . $eclaim->id . '/approve') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Eclaim Approval') }}"
                                                            data-bs-original-title="{{ __('Approve') }}">
                                                            <i class="ti ti-check text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endcan
                                            @can('Manage Eclaim')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm bg-info  align-items-center"
                                                        data-url="{{ URL::to('eclaim/showHistory/' . $eclaim->id ) }}"
                                                        data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Eclaim History') }}"
                                                        data-bs-original-title="{{ __('View History') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
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

