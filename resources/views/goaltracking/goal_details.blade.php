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


        <div class="col-xl-12">
            <div class="card  text-center">
                <div class="card-header border-0 pb-0">
                    <div class="row d-flex justify-content-between align-items-center">
                            <div class="col-md-10 d-flex">
                                <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 25px;width:25px;" />
                                <h5>Define and promote company culture and values, also check the performance of support team</h5>
                            </div>
                            <div class="col-md-2 d-flex justify-content-end">
                                <button class="btn btn-warning"><i class="fas fa-check me-2"></i> Close</button>
                            </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 d-flex justify-content-start">
                            <div class="d-flex flex-column justify-content-start">
                                <h5 class="goal-headings">Owner</h5>  
                                <p class="goal-data">
                                    <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 25px;width:25px;" />
                                     Muhammad Anees
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex justify-content-start">
                            <div class="d-flex flex-column justify-content-start">
                                <h5 class="goal-headings">TimeFrame</h5>
                                <p class="goal-data">Apr 2020 - Nov 2020</p>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex justify-content-start">
                            <div class="d-flex flex-column justify-content-start">
                                <h5 class="goal-headings">Visibility</h5>
                                <p class="goal-data">Shared</p>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex justify-content-start">
                            <div class="d-flex flex-column justify-content-start">
                                <h5 class="goal-headings">Aligned to</h5>
                                <p class="goal-data">Innovation and R&D - Accent Prime's next gen, V&V process implementation</p>
                            </div>
                        </div>
                    </div>
                    <div style="height:4rem;">
                       <div class="row">
                          <div class="col-md-3 d-flex mt-1">
                            <div class="me-2 goal-status">On Track</div>
                            <div>7 days ago</div>
                          </div>
                          <div class="col-md-3 d-flex mt-1">
                            <div class="me-2 goal-status-offtrack">Off Track</div>
                            <div>7 days ago</div>
                          </div>
                       </div>
                    <div class="progress-wrapper">
                        <span class="progress-percentage">
                            <small class="me-5">40%</small>
                            <small class="font-weight-bold ms-5">Apr 2020 - Nov 2020</small>
                        </span>
                        <div class="progress progress-xs mt-2 w-100">
                            <div class="progress-bar bg-{{ Utility::getProgressColor(40) }}"
                                role="progressbar" aria-valuenow="40"
                                aria-valuemin="0" aria-valuemax="100"
                                style="width: 40%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-12">
            <div class="card  text-center">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Key Details</h5>
                        <div>
                            <button class="btn btn-warning">Create<i class="fas fa-plus-square ms-2"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                @for($i= 0; $i < 5; $i++)
                    <div class="row tasks-bg">
                    <div class="col-md-8 col-sm-12 d-flex justify-content-between mx-auto">
                            <p class="mt-4">Document clear role division between sales, marketing, design and development</p>  
                    </div>
                    <div class="col-md-2 col-8">
                            <div class="progress-wrapper mb-2">
                                <span class="progress-percentage">
                                    <small class="">40%</small>
                                    <!-- <small class="font-weight-bold ms-5">Apr 2020 - Nov 2020</small> -->
                                </span>
                                <div class="progress progress-xs mt-2 w-100">
                                    <div class="progress-bar bg-{{ Utility::getProgressColor(40) }}"
                                        role="progressbar" aria-valuenow="40"
                                        aria-valuemin="0" aria-valuemax="100"
                                        style="width: 40%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 col-4">
                            <div class="dropdown">
                                <i class="fas fa-bars " id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item" href="#">Delete</a></li>
                                <li><a class="dropdown-item" href="#">Done (100%)</a></li>
                            </ul>
                            </div>

                            <!-- <i class="fas fa-edit"></i>
                            <i class="fas fa-trash"></i> -->
                        </div>
                    </div>
                    <hr class="tasks-line">
                @endfor
                    </div>
                </div>
            </div>
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
