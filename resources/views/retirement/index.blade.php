@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Retirement') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Retirement') }}</li>
@endsection


@section('action-button')
    @can('Create Retirement')
        <a href="#" data-url="{{ route('retirement.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Retirement') }}" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                @role('company')
                                    <th>{{ __('Employee Name') }}</th>
                                @endrole
                                <th>{{ __('Retirement Type') }}</th>
                                <th>{{ __('Notice Date') }}</th>
                                <th>{{ __('Retirement Date') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Exit Stage') }}</th>
                                @if (Gate::check('Edit Termination') || Gate::check('Delete Termination'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                          

                            @foreach ($retirements as $retirement)
                            <tr>
                                @role('company')
                                <td>{{ !empty($retirement->employee_id) ? $retirement->employee->name : '' }}</td>
                            @endrole

                            <td>{{ !empty($retirement->retirement_type) ? $retirement->retirementType->name : '' }}
                            </td>
                            <td>{{ \Auth::user()->dateFormat($retirement->notice_date) }}</td>
                            <td>{{ \Auth::user()->dateFormat($retirement->retirement_date) }}</td>
                            <td>
                                <a href="#" class="action-item" data-url="{{ route('retirement.description',$retirement->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Desciption')}}" data-title="{{__('Desciption')}}"><i class="icon_desc fa fa-comment"></i></a>
                            </td>
                            <td>{{$retirement->exitProcedure ? $retirement->exitProcedure->name :'Waiting For Exit Interview'}}</td>
                                <td class="Action">
                                    @if (Gate::check('Edit Termination') || Gate::check('Delete Termination'))
                                        <span>
                                            @can('Edit Retirement')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                        data-url="{{ URL::to('retirement/' . $retirement->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Edit Retirement') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Retirement')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['retirement.destroy', $retirement->id], 'id' => 'delete-form-' . $retirement->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
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
