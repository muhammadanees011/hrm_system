@extends('layouts.admin')
@section('page-title')
    {{ __('Reviewees') }}
@endsection
@section('action-button')
    @can('Create Goal Tracking')
        <!-- <a href="#" data-url="{{ route('performancecycle.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Performance Cycle') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-warning"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a> -->
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
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Comments') }}</th>
                                <th>{{ __('Performance') }}</th>
                                <th>{{ __('Base Salary') }}</th>
                                <th>{{ __('Inc (amount)') }}</th>
                                <th>{{ __('Inc %') }}</th>
                                <th>{{ __('New Base Salary') }}</th>
                                <th>{{ __('Eligible') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($compensationreview->reviewees as $reviewee)
                                <tr style="background-color:#f5f5f5;">
                                    <td>
                                        <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 15%;width: 15%" />
                                        {{$reviewee->user->name}} ({{$reviewee->user->type}})
                                    </td>
                                    <td>
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                            data-size="md"
                                            data-url="{{route('compensationreview.edit-comments',['reviewee_id'=>$reviewee->user->id,'review_id'=>$compensationreview->id])}}"
                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                            title="" data-title="{{ __('Reviewer Comments') }}"
                                            data-bs-original-title="{{ __('Reviewer Comments') }}">
                                            <img class="rounded-circle ms-3 me-1" src="{{asset( '/assets/images/speak.png' )}}" alt="{{ env('APP_NAME') }}"  style="height: 30px;width: 30px" />
                                        </a>
                                    <td>
                                         Very-Good
                                    </td>
                                    <td>
                                        @if($reviewee->user->employee)
                                        {{\Auth::user()->priceFormat($reviewee->user->employee->salary)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($reviewee->user->employee)
                                             {{ \Auth::user()->priceFormat(($reviewee->user->employee->salary * ($reviewee->recomended_increase_percentage ?? $compensationreview->recomended_increase_percentage) / 100)) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $reviewee->recomended_increase_percentage !== null ? $reviewee->recomended_increase_percentage : $compensationreview->recomended_increase_percentage }}%
                                    </td>                                   
                                    <td>
                                    @if($reviewee->user->employee)
                                        {{ \Auth::user()->priceFormat($reviewee->user->employee->salary * (1 + ($reviewee->recomended_increase_percentage ?? $compensationreview->recomended_increase_percentage) / 100)) }}
                                    @endif

                           
                                    </td>
                                    <td>
                                        @if($reviewee->eligible=='yes')
                                        <img class="rounded-circle ms-3 me-1" src="{{asset( '/assets/images/checkmark.png' )}}" alt="{{ env('APP_NAME') }}"  style="height: 20px;width: 20px" />
                                        @elseif($reviewee->eligible=='no')
                                        <img class="rounded-circle ms-3 me-1" src="{{asset( '/assets/images/remove.png' )}}" alt="{{ env('APP_NAME') }}"  style="height: 20px;width: 20px" />
                                        @endif
                                    </td>
                                    <td>
                                        @if($reviewee->status=='Approved')
                                        <span class="badge rounded p-2 m-1 px-3" style="background-color:#8db600;">
                                            {{$reviewee->status}}
                                        </span>
                                        @elseif($reviewee->status=='Pending')
                                        <span class="badge rounded p-2 m-1 px-3 bg-warning ">
                                            {{$reviewee->status}}
                                        </span>
                                        @elseif($reviewee->status=='Rejected')
                                        <span class="badge rounded p-2 m-1 px-3 bg-danger ">
                                            {{$reviewee->status}}
                                        </span>
                                        @endif
                                    </td>
                                    <td class="Action">
                                        @if (Gate::check('Edit Goal Tracking') || Gate::check('Delete Goal Tracking'))
                                            <span>

                                                @can('Edit Goal Tracking')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-size="md"
                                                            data-url="{{route('compensationreview.edit-rewiewee',$reviewee->id)}}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Reviewee') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
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
