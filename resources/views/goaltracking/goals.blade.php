@extends('layouts.admin')
@section('page-title')
        {{ __('Manage Goals') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item">{{ __('Goals') }}</li>
@endsection

@php
    $profile = asset(Storage::url('uploads/avatar/'));
@endphp
@section('content')

<style>
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

    @foreach($goals as $goal)
    <div class="col-xl-3">
        <div class="card  text-center">
            <div class="card-header border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0 text-dark text-sm">
                        <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 13%;width: 13%; margin-left:-50px;" />
                        {{$goal->employee->name}}
                    </p>
                </div>
                <div class="card-header-right">
                    <div class="btn-group card-option">
                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="feather icon-more-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item" data-url="{{route('goaltracking.edit',$goal->id)}}"
                                data-ajax-popup="true" data-title="{{ __('Update User') }}"><i
                                    class="ti ti-edit "></i><span class="ms-2">{{ __('Edit') }}</span></a>
                            <a href="{{route('goaltracking.goals.status', ['id' => $goal->id, 'status' => 'Done'])}}" class="dropdown-item" data-ajax-popup="false"
                                data-title="{{ __('Change Password') }}"
                                data-url="#"><i
                                    class="ti ti-key"></i>
                                <span class="ms-1">{{ __('Mark Done') }}</span>
                            </a>

                            <a href="{{route('goaltracking.goals.status', ['id' => $goal->id, 'status' => 'Off Track'])}}" class="dropdown-item" data-ajax-popup="false"
                                data-title="{{ __('Change Password') }}"
                                data-url="#"><i
                                    class="ti ti-key"></i>
                                <span class="ms-1 text-danger">{{ __('Off Track') }}</span>
                            </a>
                            <a href="{{route('goaltracking.goals.status', ['id' => $goal->id, 'status' => 'On Track'])}}" class="dropdown-item" data-ajax-popup="false"
                                data-title="{{ __('Change Password') }}"
                                data-url="#"><i
                                    class="ti ti-key"></i>
                                <span class="ms-1">{{ __('On Track') }}</span>
                            </a>
                            <a href="{{route('goaltracking.goals.visibility', ['id' => $goal->id, 'visibility' => $goal->visibility === 'Shared' ? 'Private':'Shared'])}}" class="dropdown-item" data-ajax-popup="false"
                                data-title="{{ __('Change Password') }}"
                                data-url="#"><i class="ti ti-key"></i>
                                <span class="ms-1">Make {{  $goal->visibility === "Shared" ? "Private":"Shared" }}</span>
                            </a>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['goaltracking.delete', $goal->id], 'id' => 'delete-form-' . $goal->id]) !!}

                                <a href="#" class="bs-pass-para dropdown-item"
                                data-confirm="{{ __('Are You Sure?') }}"
                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                data-confirm-yes="delete-form" title="{{ __('Delete') }}"
                                data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-trash"></i><span
                                class="ms-2">{{ __('Delete') }}</span></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-md-12 d-flex">
                    <small class="ms-2  text-warning">{{$goal->visibility}}</small>
                    </div>
                </div>
                <div class="">
                    <a href="{{route('goaltracking.goal.details',$goal->id)}}" target="_blank">
                        <h5>{{$goal->title}}</h5>
                    </a>
                </div>
                <div style="height:7rem;">
                    
                </div>
                <div class="row mb-1">
                    <div class="col-md-12 d-flex">
                    <div class="me-2 goal-status {{ $goal->status === 'Pending' ? ' bg-warning' : ($goal->status === 'Off Track' ? ' bg-danger':'')  }}">
                        {{$goal->status}}
                    </div>
                    <div>{{\Carbon\Carbon::parse($goal->created_at)->diffForHumans()}}</div>
                    </div>
                </div>
                <div class="progress-wrapper">
                    <span class="progress-percentage">
                        <small class="me-5">{{$goal->progress}}%</small>
                        <small class="font-weight-bold ms-5">{{$goal->start_date}} - {{$goal->end_date}}</small>
                    </span>
                    <div class="progress progress-xs mt-2 w-100">
                        <div class="progress-bar bg-{{ Utility::getProgressColor($goal->progress) }}"
                            role="progressbar" aria-valuenow="{{$goal->progress}}"
                            aria-valuemin="0" aria-valuemax="100"
                            style="width: {{$goal->progress}}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="col-xl-3 col-lg-4 col-sm-6">
        <a href="#" class="btn-addnew-project " data-ajax-popup="true" data-url="{{ route('goaltracking.create') }}"
            data-title="{{ __('Create New Goal') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
            <div class="bg-warning proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{ __('New Goal') }}</h6>
            <p class="text-muted text-center">{{ __('Click here to add new goal') }}</p>
        </a>
    </div>
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
