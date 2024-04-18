@extends('layouts.admin')
@section('page-title')
        {{ __('Manage Template') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item">{{ __('Manage Template') }}</li>
@endsection

@php
    $profile = asset(Storage::url('uploads/avatar/'));
@endphp
@section('content')

<style>
    .goal-headings{
        align-self: baseline;
        color:#71797E;
    }
    .goal-data{
        font-weight:600;
    }
    .tasks-bg{
        background-color:#E5E4E2;
        align-items:center;
        min-height:4rem;
        border-radius:0.1rem;
    }
    .tasks-line{
        margin-top:0px;
        margin-bottom:1px;
    }
    .goal-status{
        background-color:#8db600;
        color:white;
        border-radius:10px;
        padding:0px 20px;
    }
    .goal-status-offtrack{
        background-color:#EE204D;
        color:white;
        border-radius:10px;
        padding:0px 20px;
    }
</style>

<div class="row">
        <div class="col-md-4">
            <div class="card  text-center">
                <div class="card-header border-0 pb-0">
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-md-10 d-flex">
                            <h4>{{$meeting->title}}</h4>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-start">
                            <div class="d-flex flex-column justify-content-start">
                                <small class="goal-data">{{ \Auth::user()->dateFormat($meeting->date) }}, {{ \Auth::user()->timeFormat($meeting->start_time) }} - {{ \Auth::user()->timeFormat($meeting->end_time) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="height:4rem;">
                        <div class="row">
                            <div class="col-md-6 d-flex">
                                <p class="text-warning ms-2">Organizer</p>
                            </div>
                            <div class="col-md-6 d-flex">
                                <p class="text-warning ms-2">Invitee</p>
                            </div>
                       </div>
                       <div class="row">
                            <div class="col-md-6 d-flex">
                                <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 25px;width:25px;" />
                                <p>{{$meeting->organizer->name}}</p>
                            </div>
                            <div class="col-md-6 d-flex">
                                <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 25px;width:25px;" />
                                <p>{{$meeting->invitee->name}}</p>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card  text-center">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Talking Points</h5>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($meeting->meetingTemplate->points as $point)
                        <div class="row tasks-bg" style="margin-top:1px;">
                            <div class="col-md-11 col-sm-12 d-flex justify-content-between">
                                    <p class="mt-3">
                                        <!-- <input type="checkbox" class="me-2" id="myCheckbox" name="myCheckbox"> -->
                                        {{$point->title}}
                                    </p>  
                            </div>
                        </div>
                    @endforeach
                    <div class="row mt-4">
                        <div class="col-md-6 d-flex justify-content-start">
                            <h5>{{$meeting->organizer ? $meeting->organizer->name:''}}'s notes</h5>
                        </div>
                        <div class="col-md-6 d-flex justify-content-start">
                        <h5>{{$meeting->invitee ? $meeting->invitee->name:''}}'s notes</h5>
                        </div>
                    </div>
                    {{ Form::model($meeting, ['route' => ['meeting.notes', $meeting->id], 'method' => 'PUT']) }}
                    <div class="row mt-4">
                            <div class="form-group d-flex justify-content-between">
                                @if(\Auth::user()->id== $meeting->organizer->id)
                                <div class="form-icon-user col-md-6 me-2">
                                    {{ Form::textarea('organizer_note', null, ['class' => 'form-control', 'required' => 'required']) }}
                                </div>
                                @else
                                <div class="form-icon-user col-md-6 me-2">
                                    {{ Form::textarea('organizer_note', null, ['class' => 'form-control', 'required' => 'required', 'disabled' => 'disabled']) }}
                                </div>
                                @endif
                                @if(\Auth::user()->id== $meeting->invitee->id)
                                <div class="form-icon-user col-md-6">
                                    {{ Form::textarea('invitee_note', null, ['class' => 'form-control', 'required' => 'required']) }}
                                </div>
                                @else
                                <div class="form-icon-user col-md-6">
                                    {{ Form::textarea('invitee_note', null, ['class' => 'form-control', 'required' => 'required', 'disabled' => 'disabled']) }}
                                </div>
                                @endif
                            </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                        <div>
                        <input type="submit" value="{{ __('Update') }}" class="btn btn-warning">
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
</div>



<!-- Design -->
   
@endsection

@push('scripts')
    {{-- Password  --}}
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });

        $(document).on('change', '.user-role', function(){
            const value = $('.user-role option:selected').text();
            if(value.includes('manager')){
                $('.dep_div').removeClass('d-none');
            }else{
                $('.dep_div').addClass('d-none');
                $('.manager-department').val('').trigger('change');
            }
        });
    </script>
@endpush
