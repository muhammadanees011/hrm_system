@extends('layouts.admin')
@section('page-title')
    {{ __('Performance Cycles') }}
@endsection
@section('action-button')
    @can('Create Goal Tracking')
        <a href="#" data-url="{{ route('performancecycle.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Performance Cycle') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Performance Cycles') }}</li>
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
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Participants') }}</th>
                                <th width="20%">{{ __('Reviews Completion') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($performancecycles as $performancecycle)
                                <tr>
                                    <td style="background-color:#f5f5f5;">
                                        <a href="{{ route('performancecycle.show', $performancecycle->id)}}" data-url="{{ route('performancecycle.show', $performancecycle->id) }}">
                                            {{ $performancecycle->title }}
                                        </a>
                                    </td>
                                    <td>
                                        @foreach($performancecycle->participant_roles as $participant)
                                        <span class="badge rounded p-2 m-1 px-3 bg-warning ">
                                            {{ $participant->name }}
                                        </span>
                                        @endforeach
                                    </td>
                                    </td>                                    
                                    <td>
                                        <div class="progress-wrapper">
                                            <span class="progress-percentage"><small
                                                    class="font-weight-bold"></small>{{ $performancecycle->progress }}/100</span>
                                            <div class="progress progress-xs mt-2 w-100">
                                                <div class="progress-bar bg-{{ Utility::getProgressColor($performancecycle->progress) }}"
                                                    role="progressbar" aria-valuenow="{{ $performancecycle->progress }}"
                                                    aria-valuemin="0" aria-valuemax="100"
                                                    style="width: {{ $performancecycle->progress }}%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($performancecycle->status=='running')
                                        <span class="badge rounded p-2 m-1 px-3 bg-primary ">
                                            {{ $performancecycle->status }}
                                        </span>
                                        @elseif($performancecycle->status=='ended')
                                        <span class="badge rounded p-2 m-1 px-3 bg-danger ">
                                            {{ $performancecycle->status }}
                                        </span>
                                        @else
                                        <span class="badge rounded p-2 m-1 px-3 bg-secondary ">
                                            {{ $performancecycle->status }}
                                        </span>
                                        @endif
                                    </td>
                                    <td class="Action">
                                        @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                            <span>

                                                @can('Edit Goal Tracking')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-size="lg"
                                                            data-url="{{ URL::to('performancecycle/' . $performancecycle->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Performance Cycle') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Goal Tracking')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['performancecycle.destroy', $performancecycle->id],
                                                            'id' => 'delete-form-' . $performancecycle->id,
                                                        ]) !!}
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"><i
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
