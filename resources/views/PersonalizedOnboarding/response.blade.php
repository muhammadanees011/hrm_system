@extends('layouts.admin')

@section('page-title')
{{ __('Employee') }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="#">{{ __('Employee Onboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Employee onboarding response') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-sm-12 col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body employee-detail-body fulls-card">
                        <h5>{{ __('Employee onboarding Ask/Information Details') }}</h5>
                        <hr>
                        <div class="row">
                            @if(count($askDetails) > 0)
                            @foreach($askDetails as $answer)
                            <div class="col-md-12">
                                <div class="info text-md">
                                    <strong class="font-bold">{{ $answer->onboardingQuestion->name }} : </strong>
                                    <br>
                                    @if($answer->onboardingQuestion->type == 'file')
                                    <a href="/storage/uploads/employeeOnboardingTemplate/responseFiles/{{$answer->answer}}" target="_blank">{{ $answer->answer }}</a>
                                    @else
                                    <span>{{ $answer->answer }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="col-md-12">
                                <div class="info text-md">
                                    <span>No Records found!</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body employee-detail-body fulls-card">
                        <h5>{{ __('Employee onboarding Documents Approved Status') }}</h5>
                        <hr>
                        <div class="row">
                            @if(count($fileApprovals) > 0)
                            @foreach($fileApprovals as $file)
                            <div class="col-md-12">
                                <div class="info text-sm">
                                    <a href="/storage/uploads/employeeOnboardingTemplate/{{$file->onboardingFile->file_path}}" target="_blank"><strong class="font-bold">{{ $file->onboardingFile->file_path }} : </strong></a>
                                    <span>{{$file->approve_status ? 'Approved' : 'Not Approved'}}</span>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="col-md-12">
                                <div class="info text-md">
                                    <span>No Records found!</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection