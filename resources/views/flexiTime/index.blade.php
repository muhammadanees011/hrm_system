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
        @if(\Auth::user()->type !="employee")
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 " id="multiCollapseExample1" style="">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['flexi-time.index'], 'method' => 'get', 'id' => 'timesheet_filter']) }}
                        <div class="d-flex align-items-center justify-content-end">
                            {{-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
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
                            </div> --}}
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box" id="employee_div">
                                    {{ Form::label('employee', __('Employee'), ['class' => 'form-label']) }}
                                    {{ Form::select('employee', $employees, $employee, ['class' => 'form-control select2 ', 'required' => 'required', 'placeholder' => __('Select Employee')]) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('timesheet_filter').submit(); return false;"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('flexi-time.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
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
        @endif
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
                                <th>{{ __('Time') }}</th>
                                <th>{{ __('No. Of Hours') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Remark') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    @if(\Auth::user()->type !="employee")
                                        <th>{{ $record->employee->name ??  "" }}</th>
                                    @endif
                                    <td>
                                        <strong>Start Date: </strong>{{\Auth::user()->dateFormat($record->start_date)}} <br />
                                        <strong>End Date: </strong>{{\Auth::user()->dateFormat($record->end_date)}}
                                    </td>
                                    <td>
                                        <strong>Start Time: </strong>{{\Auth::user()->timeFormat($record->start_time)}} <br />
                                        <strong>End Time: </strong>{{\Auth::user()->timeFormat($record->end_time)}}
                                    </td>
                                    <td>{{ $record->hours ?? ""}}</td>
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
                                    <td>
                                        {{ $record->remark ?? ""}}
                                        @if(!empty($record->updated_user))
                                            <br />
                                            <strong>HR Comment: </strong>{{$record->updated_user_comment}}
                                            <br />
                                            <strong>Commented User: </strong>{{$record->updatedUser->name}}
                                        @endif
                                    </td>
                                    <td class="Action">
                                        <span>
                                            @can('Edit FlexiTime')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-size="lg"
                                                        data-url="{{ URL::to('flexi-time/' . $record->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit FlexiTime') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete FlexiTime')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['flexi-time.destroy', $record->id], 'id' => 'delete-form-' . $record->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            @endcan
                                            @if((\Auth::user()->type=="hr" || \Auth::user()->type=="company") && $record->status=="pending" && \Auth::user()->can('Approve FlexiTime'))
                                                <div class="action-btn bg-success ms-2">
                                                    <a href="flexi-time/{{$record->id}}/approve" 
                                                        data-ajax-popup="true" data-size="lg"
                                                        data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
                                                        data-bs-original-title="{{ __('Approve') }}"
                                                    >
                                                        <i class="ti ti-check text-white"></i>
                                                    </a>
                                                </div>
                                                <div class="action-btn bg-danger ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm bg-danger  align-items-center"
                                                        data-url="{{ URL::to('flexi-time/' . $record->id . '/reject') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Reject Request') }}"
                                                        data-bs-original-title="{{ __('Reject') }}">
                                                        <i class="ti ti-trash-off text-white"></i>
                                                    </a>
                                                </div>
                                            @endif
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

