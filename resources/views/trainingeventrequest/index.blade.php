@extends('layouts.admin')

@section('page-title')
    {{ __('Training Requests') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Training Requests') }}</li>
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
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Training Event') }}</th>
                                <th>{{ __('Event Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('Edit Training') || Gate::check('Delete Training') || Gate::check('Show Training'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trainingEventRequest as $training)
                                <tr>
                                    <td>{{ !empty($training->employees) ? $training->employees->name : '' }} </td>
                                    <td>{{ !empty($training->trainingevent) ? $training->trainingevent->title : '' }}</td>
                                    <td>{{ !empty($training->trainingevent) ? $training->trainingevent->start_date : '' }}
                                        - {{ !empty($training->trainingevent) ? $training->trainingevent->end_date : '' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-warning p-2 px-3 rounded mt-1 status-badge6">{{ __($training->status) }}</span>
                                    </td>
                                    <td class="Action">
                                        @if (Gate::check('Edit Training') || Gate::check('Delete Training') || Gate::check('Show Training'))
                                            <span>
                                                @can('Edit Training')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="md"
                                                            data-url="{{ route('trainingeventrequest.edit', $training->id) }}"
                                                            data-ajax-popup="true" data-size="sm" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Request Status') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Training')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['trainingeventrequest.destroy', $training->id], 'id' => 'delete-form-' . $training->id]) !!}
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
