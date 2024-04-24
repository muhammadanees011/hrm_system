@extends('layouts.admin')
@section('page-title')
    {{ __('Performance Reviews') }}
@endsection
@section('action-button')
    @can('Create Goal Tracking')
        <a href="#" data-url="{{ route('employeereviews.create') }}" data-ajax-popup="true" data-size="md"
            data-title="{{ __('Create Performance Review') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-warning"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Performance Reviews') }}</li>
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
                                <th>{{ __('Performance Cycle') }}</th>
                                <th width="20%">{{ __('Reviews Completion') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $review)
                                <tr>
                                    <td>
                                        <a href="{{ route('employeereviews.reviewees.list', $review->id)  }}" target="_blank">
                                            {{$review->title}}
                                            <i class="ti ti-arrow-right"></i>
                                        </a>
                                    </td>
                                    <td>
                                        {{$review->performanceCycle->title}}
                                    </td>
                                    </td>                                    
                                    <td>
                                        <div class="progress-wrapper">
                                            <span class="progress-percentage"><small class="font-weight-bold"></small>
                                                {{$review->completed_reviews_count}}/{{$review->reviewees_count}}
                                            </span>
                                            <div class="progress progress-xs mt-2 w-100">
                                                <div class="progress-bar bg-{{ Utility::getProgressColor($review->reviewees_count !=0 ? (($review->completed_reviews_count/$review->reviewees_count)*100):0) }}"
                                                    role="progressbar" aria-valuenow="{{$review->completed_reviews_count}}"
                                                    aria-valuemin="0" aria-valuemax="{{$review->reviewees_count}}"
                                                    style="width: {{$review->reviewees_count !=0 ? (($review->completed_reviews_count/$review->reviewees_count)*100):0}}%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded p-2 m-1 px-3 bg-warning ">
                                        {{$review->status}}
                                        </span>
                                    </td>
                                    <td class="Action">
                                        @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                            <span>

                                            <div class="action-btn bg-primary ms-2">
                                                <a href="{{ route('employeereviews.show',$review->id) }}" target="_blank" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('Review Questions') }}">
                                                    <i class="ti ti-file text-white"></i>
                                                </a>
                                            </div>
                                                @can('Edit Goal Tracking')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-size="md"
                                                            data-url="{{ URL::to('employeereviews/' . $review->id . '/edit')  }}"
                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Performance Review') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Goal Tracking')
                                                    <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['employeereviews.destroy', $review->id], 'id' => 'delete-form-' . $review->id]) !!}
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete">
                                                            <i class="ti ti-trash text-white text-white"></i>
                                                        </a>
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
