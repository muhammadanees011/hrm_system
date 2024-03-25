@extends('layouts.admin')
@section('page-title')
    {{ __('1st Quarter') }}
@endsection
@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('1st Quarter') }}</li>
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
        border-left:3px solid #EE204D;
    }
    .reviewers-line{
        color:black;
        width: -webkit-fill-available;
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
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <img src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}" class="rounded-circle" style="width: 27%" />
                        <div>
                            <h6 class="mt-2 text-black">Muhammad Anees</h6>
                            <small style="color:#8db600;">Product Designer</small>
                            <h6 style="color:#ffa21d;">View goals</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body d-flex justify-content-start">
                    <div class="reviewers-list">
                        <h6 class="mt-2 text-black">REVIEWERS</h6>
                        <hr class="reviewers-line">
                        @for($i=0; $i< 5; $i++)
                        <div class="reviewer">
                            <img src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}" class="rounded-circle ms-1 me-2" style="width: 10%" />
                            <div class="d-flex flex-column align-items-start">
                                <p class="m-0 p-0">Abeer Waseem</p>
                                <small class="m-0 cycle-widget">Manager</small>
                            </div>
                        </div> 
                        @endfor           
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card text-center">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex flex-column justify-content-start align-items-start">
                        <h6 class="mt-2 text-black">David Amore'e Review <span  style="color:#ffa21d;">(Manager)</span></h6>
                        <small><span class="cycle-widget">1st Quarter</span> | submitted on 06/21/2020</small>
                    </div>
                    <div class="card-header-right">
                    </div>
                </div>
                <hr>
                <div class="card-body d-flex justify-content-start">
                    <div class="reviewers-list">
                        <h6 class="mt-2 text-black">ASSESSMENT</h6>
                        <p>To What extant do you agree with each of the following statements ?</p>
                        @for($i=0; $i< 5; $i++)
                        <div class="rewiews">
                            <p class="mt-4">{{$i+1}}. Is the go-to person when teammates seek advide and knowledge ?</p> 
                            <div class="review-options">
                                <div class="single-option-blur">
                                    <p class="emoji">&#128544;</p>
                                    Strongly disagree 
                                </div>
                                <div class="single-option-blur">
                                    <p class="emoji">&#128578;</p>
                                    Disagree
                                </div>
                                <div class="single-option-blur">
                                    <p class="emoji">&#128578;</p>
                                    Agree
                                </div>
                                <div class="single-option-selected">
                                    <p class="emoji">&#128516;</p>
                                    Strongly agree
                                </div>
                            </div>   
                        </div> 
                        @endfor         
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


