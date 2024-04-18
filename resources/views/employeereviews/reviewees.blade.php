@extends('layouts.admin')
@section('page-title')
    {{ __('Reviewees') }}
@endsection
@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reviewees') }}</li>
@endsection

@section('content')

<style>
    .nav-item{
        color:black;
        border-top:1px solid black;
        border-right:1px solid black;
        border-radius:1px;
    }
    .nav-item:hover{
        background-color:#EE204D;
    }
    .nav-item .active{
        border-radius:0px;
        background-color:#EE204D !important;
        color:white !important;
    }
    .nav-link{
        border:none;
        color:black;
    }
    .nav-link:hover{
        border:none;
        color:white !important;
    }
    .custom-icon{
        background-color:black;
        color:white;
        border-radius:10px;
    }
</style>
    
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>
                                    <i class="ti ti-users"></i>    
                                    {{ __('Employees') }}
                                </th>
                                <th>
                                    <i class="ti ti-user custom-icon"></i>                                    
                                    <i class="ti ti-arrow-down"></i> 
                                    {{ __('Managers') }}
                                </th>
                                <th >
                                    <i class="ti ti-user custom-icon"></i>                                    
                                    <i class="ti ti-arrow-right"></i> 
                                    {{ __('BUDDY') }}
                                    (Reviews)
                                </th>
                                <th>
                                    <i class="ti ti-user custom-icon"></i>                                    
                                    <i class="ti ti-arrow-up"></i> 
                                    {{ __('Management') }}
                                    (Reviews)
                                </th>
                                <th>
                                    <i class="ti ti-user custom-icon"></i>                                    
                                    <i class="ti ti-arrow-up"></i> 
                                    {{ __('SUMMARY') }}
                                    (Reviews)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviewees  as $reviewee)
                            <tr>
                                <td style="background-color:#f5f5f5;">
                                    @if($reviewee->user->id== (\Auth::user()->id))
                                    <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 12%;width: 12%" />
                                        {{$reviewee->user->name}}
                                    @else
                                    <a href="{{ route('employeereviews.review.questions',[$reviewee->review_id,$reviewee->user_id])}}" target="_blank">
                                    <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 12%;width: 12%" />
                                        {{$reviewee->user->name}}
                                        <i class="ti ti-arrow-right"></i>
                                    </a>
                                    @endif
                                </td>
                                <td>
                                <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 12%;width: 12%" />
                                    {{ ($reviewee->user ? 
                                        ($reviewee->user->employee ? 
                                        ($reviewee->user->employee->manager ? 
                                        ($reviewee->user->employee->manager->name):'-') : '-'):'-') }}
                                </td>
                                </td>                                    
                                <td>
                                    {{$reviewee->buddy_reviews}}/{{$reviewee->buddy_reviewers}}
                                </td>
                                <td>
                                    {{$reviewee->management_reviews}}/{{$reviewee->management_reviewers}}
                                </td>
                                <td class="">
                                    <div class="action-btn bg-warning ms-2">
                                        <a href="{{ route('employeereviews.feedback',[$reviewee->review_id,$reviewee->user_id])}}" target="_blank" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('Reviews') }}">
                                            <i class="ti ti-eye text-white"></i>
                                        </a>
                                    </div>
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


