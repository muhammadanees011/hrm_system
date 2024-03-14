@extends('layouts.admin')

@section('page-title')
   {{ __('Manage Flexi Time') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('FlexiTime') }}</li>
@endsection

@section('action-button')
   @can('Create FlexiTime')
        <a href="#" data-url="{{ route('flexi-time.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Request A New Time') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Creates') }}">
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
                                    <th>{{ __('Employee') }}</th>
                                @endif
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Start Time') }}</th>
                                <th>{{ __('End Time') }}</th>
                                <th>{{ __('Remark') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('CarryOver Hours') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    @if(\Auth::user()->type !="employee")
                                        <th>{{ $record->employee->name ??  "" }}</th>
                                    @endif
                                    <td>{{ $record->start_date ?? "" }}</td>
                                    <td>{{ $record->start_time ?? ""}}</td>
                                    <td>{{ $record->end_time ?? ""}}</td>
                                    <td>{{ $record->remark ?? ""}}</td>
                                    <td>
                                        @if($record->status=="pending")
                                            <button class="btn bg-warning btn-sm">{{ucfirst($record->status)}}</button>
                                        @elseif($record->status=="approved by HR")
                                            <button class="btn bg-info btn-sm">{{ucfirst($record->status)}}</button>
                                        @elseif($record->status=="approved")
                                            <button class="btn bg-success btn-sm">{{ucfirst($record->status)}}</button>
                                        @else
                                            <button class="btn bg-danger btn-sm">{{ucfirst($record->status)}}</button>
                                        @endif
                                    </td>
                                    <td>{{ $record->start_time ?? ""}} - {{$record->end_time ?? ""}}</td>
                                    
                                    <td class="Action">
                                        <span>
                                            @can('Edit FlexiTime')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-size="lg"
                                                        data-url="{{ URL::to('eclaim/' . $record->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit FlexiTime') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete FlexiTime')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['eclaim.destroy', $record->id], 'id' => 'delete-form-' . $record->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            @endcan

                                            @can('Manage FlexiTime')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm bg-info  align-items-center"
                                                    data-url="{{ URL::to('eclaim/showReceipt/' . $record->id ) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Eclaim Receipt') }}"
                                                        data-bs-original-title="{{ __('View Receipt') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>

                                                @if(\Auth::user()->type=="hr" || \Auth::user()->type=="company")
                                                

                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="flexi-time/{{$record->id}}/reject"  data-ajax-popup="true" data-size="lg"
                                                                data-bs-toggle="tooltip" title="" class="mx-3 btn btn-sm bg-danger  align-items-center"
                                                                data-bs-original-title="{{ __('Reject') }}">
                                                                <i class="ti ti-trash-off text-white"></i>
                                                        </a>
                                                    </div>
                                                    
                                                    <div class="action-btn bg-success ms-2">
                                                        <a href="flexi-time/{{$record->id}}/approve"  data-ajax-popup="true" data-size="lg"
                                                                data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
                                                                data-bs-original-title="{{ __('Approve') }}">
                                                                <i class="ti ti-check text-white"></i>
                                                        </a>
                                                    </div>

                                                        
                                                @endif
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

