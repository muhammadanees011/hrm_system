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
        <div class="col-md-3">
            <div class="card  text-center">
                <div class="card-header border-0 pb-0">
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-md-12 d-flex">
                            <h4>{{$meetingtemplate->title}}</h4>
                        </div>
                        <div class="col-md-12 d-flex">
                            <p>{{$meetingtemplate->description}}</p>
                        </div>
                    </div>
                    
                    <div class="row" style="height:5rem;">
                        
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-9">
            <div class="card  text-center">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Talking Points</h5>
                        <div>
                            <a href="#" class="btn btn-warning" data-ajax-popup="true"
                                data-title="{{ __('Create Result') }}"
                                data-url="{{route('meetingtemplatepoint.create',['template_id'=>$meetingtemplate->id])}}">
                                <span class="ms-1 text-white">{{ __('Create Point') }}</span>
                                <i class="fas fa-plus-square ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($meetingtemplatepoints as $meetingtemplatepoint)
                        <div class="row tasks-bg" style="margin-top:1px;">
                            <div class="col-md-11 col-sm-12 d-flex justify-content-between">
                                    <p class="mt-3">{{$meetingtemplatepoint->title}}</p>  
                            </div>
                            <div class="col-md-1 col-1 mx-auto">
                                <div class="dropdown">
                                    <i class="fas fa-bars " id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a href="#" class="dropdown-item" data-ajax-popup="true"
                                            data-title="{{ __('Create Result') }}"
                                            data-url="{{route('meetingtemplatepoint.edit',['template_id'=>$meetingtemplate->id,'point_id'=>$meetingtemplatepoint->id])}}">
                                            <span class="ms-1 text-black">{{ __('Edit') }}</span>
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{route('meetingtemplatepoint.delete',['template_id'=>$meetingtemplate->id,'point_id'=>$meetingtemplatepoint->id])}}">Delete</a></li>
                                </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
