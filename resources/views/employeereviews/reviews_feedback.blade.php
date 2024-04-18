@extends('layouts.admin')
@section('page-title')
    {{ __('Review Assessment') }}
@endsection
@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Review Assessment') }}</li>
@endsection

@section('content')

<style>
    .reviewers-list{
        display:flex;
        flex-direction:column;
        justify-content:start;
        align-items:start;
    }
    .reviewer{
        display:flex;
        justify-content:start;
        align-items:center;
        width:22.5rem;
        height:3.5rem;
        background-color:#f5f5f5;
        margin-bottom:2px;
        padding-left:5px;
    }
    .reviewer-active{
        border-left:3px solid #EE204D;
    }
    .reviewers-line{
        color:black;
        width: -webkit-fill-available;
    }
    .question{
        width:100%;
    }
    .review-options{
        width:100%;
        display:flex;
        justify-content:space-between;
        cursor:pointer;
    }
    .emoji{
        font-size: 24px;
    }
    .single-option-blur{
        opacity:0.4;
        cursor:pointer;
    }
    .single-option-slected{
        cursor:pointer;
    }
    .cycle-widget{
        background-color:#8db600;
        color:white;
        border-radius:10px;
        padding:0px 20px;
    }
</style>

<div class="col-md-12">
    <div class="row">
        <div class="col-xl-4">
            <div class="card text-center">
                <div class="card-header border-0 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <img src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}" class="rounded-circle" style="width: 27%" />
                        <div>
                            <h6 class="mt-2 text-black">{{$user->name}}</h6>
                            <small style="color:#8db600;">{{ucfirst($user->type)}}</small>
                            <a href="{{ route('goaltracking.goals', $user->employee_id)}}" target="_blank">
                            <h6 style="color:#ffa21d;">View goals</h6>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body d-flex justify-content-start">
                    <div class="reviewers-list">
                        <h6 class=" text-black">REVIEWERS</h6>
                        <hr class="reviewers-line">
                        @foreach($reviewers as $index => $reviewer)
                        <a href="{{ route('employeereviews.feedback',[$reviewer->review_id,$reviewee_id,$reviewer->user_id])}}" class="text-dark" title="">
                        <div class="reviewer {{ $index === 0 && !$reviewer->user_id == $reviewer_id ? 'reviewer-active' : '' }} {{ $reviewer->user_id == $reviewer_id ? 'reviewer-active' : '' }}">
                            <img src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}" class="rounded-circle ms-1 me-2" style="width: 10%" />
                            <div class="d-flex flex-column align-items-start">
                                <p class="m-0 p-0">{{ucfirst($reviewer->user->name)}}</p>
                                <small class="m-0 cycle-widget">{{$reviewer->user->type}}</small>
                            </div>
                        </div> 
                        </a>
                        @endforeach           
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card text-center">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex flex-column justify-content-start align-items-start">
                        <h6 class="mt-2 text-black">{{$user->name}}'s Review <span  style="color:#ffa21d;">({{ucfirst($user->type)}})</span></h6>
                        <small><span class="cycle-widget">{{$performancecycle->title}}</span> | created on {{ \Carbon\Carbon::parse($performancecycle->created_at)->format('m/d/Y') }}</small>
                    </div>
                    <div class="card-header-right">
                    </div>
                </div>
                <hr>
                <div class="card-body d-flex justify-content-start">
                    <div class="reviewers-list">
                        <h6 class="mt-2 text-black">ASSESSMENT</h6>
                        <p>To What extant do you agree with each of the following statements ?</p>
                        @foreach($reviewquestions as $index => $question)
                            <div class="reviews">
                                <p class="mt-4 question">{{ $index+1 }}. {{ $question->question }}</p>
                                <div class="review-options">
                                    <div class="single-option
                                        @php
                                            $isSelected = false;
                                        @endphp
                                        @foreach($question->reviewResult as $result)
                                            @if($result->question_id == $index && $result->selected_option == 'Strongly disagree')
                                                @php
                                                    $isSelected = true;
                                                @endphp
                                                single-option-selected
                                            @endif
                                        @endforeach
                                        @if(!$isSelected)
                                            single-option-blur
                                        @endif 
                                        " 
                                     >
                                        <p class="emoji">&#128544;</p>
                                        <p class="option-name">Strongly disagree</p>
                                    </div>
                                    <div class="single-option
                                        @php
                                            $isSelected = false;
                                        @endphp
                                        @foreach($question->reviewResult as $result)
                                            @if($result->question_id == $index && $result->selected_option == 'Disagree')
                                                @php
                                                    $isSelected = true;
                                                @endphp
                                                single-option-selected
                                            @endif
                                        @endforeach
                                        @if(!$isSelected)
                                            single-option-blur
                                        @endif 
                                    " 
                                     data-index="{{ $index }}" >
                                        <p class="emoji">&#128578;</p>
                                        <p class="option-name">Disagree</p>
                                    </div>
                                    <div class="single-option 
                                        @php
                                            $isSelected = false;
                                        @endphp
                                        @foreach($question->reviewResult as $result)
                                            @if($result->question_id == $index && $result->selected_option == 'Agree')
                                                @php
                                                    $isSelected = true;
                                                @endphp
                                                single-option-selected
                                            @endif
                                        @endforeach
                                        @if(!$isSelected)
                                            single-option-blur
                                        @endif 
                                    " data-index="{{ $index }}">
                                        <p class="emoji">&#128522;</p>
                                        <p class="option-name">Agree</p>
                                    </div>
                                    <div class="single-option 
                                        @php
                                            $isSelected = false;
                                        @endphp
                                        @foreach($question->reviewResult as $result)
                                            @if($result->question_id == $index && $result->selected_option == 'Strongly agree')
                                                @php
                                                    $isSelected = true;
                                                @endphp
                                                single-option-selected
                                            @endif
                                        @endforeach
                                        @if(!$isSelected)
                                            single-option-blur
                                        @endif 

                                    " data-index="{{ $index }}" >
                                        <p class="emoji">&#128516;</p>
                                        <p class="option-name">Strongly agree</p>
                                    </div>
                                </div>   
                            </div> 
                        @endforeach    
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    
</script>