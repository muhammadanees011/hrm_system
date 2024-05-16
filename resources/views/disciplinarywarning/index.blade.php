@extends('layouts.admin')
@section('page-title')
        {{ __('Disciplinary Warning') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item">{{ __('Disciplinary Warning') }}</li>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Warnings</h5>
                        <div>
                            <a href="#" class="btn btn-warning" data-ajax-popup="true"
                                data-title="{{ __('Create Warning') }}"
                                data-url="{{route('disciplinarywarning.create',['employee_id'=>$employee_id,'performance_cycle_id'=>$performance_cycle_id])}}">
                                <span class="ms-1 text-white">{{ __('Create') }}</span>
                                <i class="fas fa-plus-square ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($warnings as $warning)
                    <div class="row tasks-bg">
                        <div class="col-md-11 col-sm-12 d-flex justify-content-between mx-auto">
                            <h5 class="mt-2">{{$warning->title}}</h5>
                        </div>
                        <div class="col-1">
                            <div class="dropdown">
                                <i class="fas fa-bars " id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a href="#" class="dropdown-item" data-ajax-popup="true"
                                        data-title="{{ __('Edit Warning') }}"
                                        data-url="{{route('disciplinarywarning.edit',$warning->id)}}">
                                        <span class="ms-1 text-black">{{ __('Edit') }}</span>
                                    </a>
                                </li>
                                <li>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['disciplinarywarning.delete', $warning->id], 'id' => 'delete-form-' . $warning->id]) !!}
                                    <a href="#" class="mx-1 text-danger btn align-items-center bs-pass-para"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                        aria-label="Delete">Delete</a>
                                    </form>
                                </li>
                            </ul>
                            </div>

                            <!-- <i class="fas fa-edit"></i>
                            <i class="fas fa-trash"></i> -->
                        </div>
                    </div>
                    <div class="row  tasks-bg">
                        <div class="col-md-12 col-sm-12 d-flex justify-content-between mx-auto">
                            <p class="mt-1">{{$warning->description}}</p>  
                        </div>  
                    </div>
                    <hr class="tasks-line">
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
