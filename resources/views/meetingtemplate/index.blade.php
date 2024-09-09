@extends('layouts.admin')
@section('page-title')
        {{ __('1-on-1s Templates') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item">{{ __('1-on-1s Templates') }}</li>
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
    @foreach($meetingtemplates as $meetingtemplate)
    <div class="col-xl-3">
        <div class="card  text-center">
            <div class="card-header border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0 text-dark">
                        <i class="ti ti-receipt" style="font-size: 18px;"></i>
                        {{$meetingtemplate->points_count}} talking points
                    </p>
                </div>
                <div class="card-header-right">
                    @if(!(\Auth::user()->type=='employee'))
                    <div class="btn-group card-option">
                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="feather icon-more-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item" data-url="{{route('meetingtemplate.edit',$meetingtemplate->id)}}"
                                data-ajax-popup="true" data-title="{{ __('Update User') }}"><i
                                    class="ti ti-edit "></i><span class="ms-2">{{ __('Edit') }}</span></a>

                            <div class="action-btn ms-2">
                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['meetingtemplate.destroy', $meetingtemplate->id],
                                    'id' => 'delete-form-' . $meetingtemplate->id,
                                ]) !!}
                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                    aria-label="Delete">
                                        
                                        <span class="ms-5 text-danger d-flex">
                                        <i class="ms-3 me-2 ti ti-trash text-danger"></i>
                                            {{ __('Delete') }}
                                        </span>
                                    </a>
                                </form>
                            </div>

                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="">
                    <a href="{{ route('meeting.list', $meetingtemplate->id)}}">
                        <h5>{{$meetingtemplate->title}}</h5>
                    </a>
                    <p class="text-dark">{{$meetingtemplate->description}}</p>
                </div>
                <div style="height:3rem;">
                    
                </div>
                @if(!(\Auth::user()->type=='employee'))
                <div class="row mb-1">
                    <div class="col-md-12 d-flex justify-content-end">
                    <div class="me-2">
                        <h6 class="text-warning">
                        <a href="{{route('meetingtemplate.show', $meetingtemplate->id)}}" class="text-warning">
                             Manage
                        </a>
                        </h6>
                    </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    @if($meetingtemplates->isEmpty())
        @if((\Auth::user()->type=='employee'))
            <p class="text-muted text-center">{{ __('Meeting Template Does Note Exist, Please ask HR or Admin to create a template') }}</p>
        @else
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <a href="#" class="btn-addnew-project " data-ajax-popup="true" data-url="{{route('meetingtemplate.create')}}"
                data-title="{{ __('Create New Template') }}" data-bs-toggle="tooltip" title=""
                class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                <div class="bg-warning proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">{{ __('New Template') }}</h6>
                <p class="text-muted text-center">{{ __('Click here to add new template') }}</p>
            </a>
        </div>
        @endif
    @elseif(!(\Auth::user()->type=='employee'))
    <div class="col-xl-3 col-lg-4 col-sm-6">
        <a href="#" class="btn-addnew-project " data-ajax-popup="true" data-url="{{route('meetingtemplate.create')}}"
            data-title="{{ __('Create New Template') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
            <div class="bg-warning proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{ __('New Template') }}</h6>
            <p class="text-muted text-center">{{ __('Click here to add new template') }}</p>
        </a>
    </div>
    @endif
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
