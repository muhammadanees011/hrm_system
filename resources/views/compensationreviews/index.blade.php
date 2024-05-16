@extends('layouts.admin')
@section('page-title')
    {{ __('Compensation Reviews') }}
@endsection
@section('action-button')
    @can('Create Goal Tracking')
        <a href="#" data-url="{{ route('compensationreview.create') }}" data-ajax-popup="true" data-size="md"
            data-title="{{ __('Create New Compensation Review') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-warning"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Compensation Reviews') }}</li>
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
                                <th>{{ __('Recomended increase (amount)') }}</th>
                                <th>{{ __('Recomended increase %') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $review)
                                <tr>
                                    <td style="background-color:#f5f5f5;">
                                        <a href="{{route('compensationreview.show',$review->id)}}">
                                            {{$review->title}}
                                            <i class="ti ti-arrow-right"></i>
                                        </a>
                                    </td>
                                    <td>
                                        {{$review->recomended_increase_amount}}
                                    </td>
                                    </td>                                    
                                    <td>
                                    {{$review->recomended_increase_percentage}}%
                                    </td>
                                    <td>
                                        <span class="badge rounded p-2 m-1 px-3 bg-warning ">
                                        {{$review->status}}
                                        </span>
                                    </td>
                                    <td class="Action">
                                        @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                            <span>

                                                @can('Edit Goal Tracking')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-size="md"
                                                            data-url="{{route('compensationreview.edit',$review->id)}}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Compensation Review') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Goal Tracking')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['compensationreview.destroy', $review->id], 'id' => 'delete-form-' . $review->id]) !!}
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
