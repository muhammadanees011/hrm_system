@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Job Application') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Job Application') }}</li>
@endsection


@push('css-page')
    <link href="{{ asset('libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
@endpush


@push('script-page')
    {{-- <script src="{{ asset('libs/dragula/dist/dragula.min.js') }}"></script>
    <script src="{{ asset('libs/autosize/dist/autosize.min.js') }}"></script> --}}
    <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>

    <script>
        $(document).on('change', '#jobs', function() {

            var id = $(this).val();

            $.ajax({
                url: "{{ route('get.job.application') }}",
                type: 'POST',
                data: {
                    "id": id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    var job = JSON.parse(data);
                    // console.log(job)
                    var applicant = job.applicant;
                    var visibility = job.visibility;
                    var question = job.custom_question;

                    (applicant.indexOf("gender") != -1) ? $('.gender').removeClass('d-none'): $(
                        '.gender').addClass('d-none');
                    (applicant.indexOf("dob") != -1) ? $('.dob').removeClass('d-none'): $('.dob')
                        .addClass('d-none');
                    (applicant.indexOf("address") != -1) ? $('.address').removeClass('d-none'): $(
                        '.address').addClass('d-none');

                    (visibility.indexOf("profile") != -1) ? $('.profile').removeClass('d-none'): $(
                        '.profile').addClass('d-none');
                    (visibility.indexOf("resume") != -1) ? $('.resume').removeClass('d-none'): $(
                        '.resume').addClass('d-none');
                    (visibility.indexOf("letter") != -1) ? $('.letter').removeClass('d-none'): $(
                        '.letter').addClass('d-none');

                    $('.question').addClass('d-none');

                    if (question.length > 0) {
                        question.forEach(function(id) {
                            $('.question_' + id + '').removeClass('d-none');
                        });
                    }


                }
            });
        });

        @can('Move Job Application')
            ! function(a) {
                "use strict";

                var t = function() {
                    this.$body = a("body")
                };
                t.prototype.init = function() {
                    // console.log(t);
                    a('[data-plugin="dragula"]').each(function() {

                        //   console.log(t);
                        var t = a(this).data("containers"),

                            n = [];
                        if (t)
                            for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]);
                        else n = [a(this)[0]];
                        var r = a(this).data("handleclass");
                        r ? dragula(n, {
                            moves: function(a, t, n) {
                                return n.classList.contains(r)
                            }
                        }) : dragula(n).on('drop', function(el, target, source, sibling) {
                            var order = [];
                            $("#" + target.id + " > div").each(function() {
                                order[$(this).index()] = $(this).attr('data-id');
                            });

                            var id = $(el).attr('data-id');

                            var old_status = $("#" + source.id).data('status');
                            var new_status = $("#" + target.id).data('status');
                            var stage_id = $(target).attr('data-id');


                            $("#" + source.id).parent().find('.count').text($("#" + source.id +
                                " > div").length);
                            $("#" + target.id).parent().find('.count').text($("#" + target.id +
                                " > div").length);
                            $.ajax({
                                url: '{{ route('job.application.order') }}',
                                type: 'POST',
                                data: {
                                    application_id: id,
                                    stage_id: stage_id,
                                    order: order,
                                    new_status: new_status,
                                    old_status: old_status,
                                    "_token": $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(data) {
                                    show_toastr('Success', 'Lead successfully updated',
                                        'success');
                                },
                                error: function(data) {
                                    data = data.responseJSON;
                                    show_toastr('Error', data.error, 'error')
                                }
                            });
                        });
                    })
                }, a.Dragula = new t, a.Dragula.Constructor = t
            }(window.jQuery),
            function(a) {
                "use strict";

                a.Dragula.init()

            }(window.jQuery);
        @endcan
    </script>
@endpush
@section('action-button')
    <!--  <a class="btn btn-sm btn-primary collapsed" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button"
            aria-expanded="false" aria-controls="multiCollapseExample1" data-bs-toggle="tooltip" title="{{ __('Filter') }}">
            <i class="ti ti-filter"></i>
        </a> -->


    @can('Create Job Application')
        <a href="#" data-url="{{ route('job-application.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Job Application') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection
@section('content')



<style>

.employees-actions{
    display:flex;
    justify-content:end;
}

.dropdown {
    position: relative;
    display: inline-block;
  }
  
  .dropbtn {
    background-color: orange;
    color: white;
    padding: 6px;
    padding-left: 1.3rem;
    padding-right: 1.3rem;
    font-size: 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
  }
  
  .dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    right: 1px;
    font-size: 12px;
  }
  
  .dropdown-content a {
    color: black !important;
    padding: 5px 12px;
    text-decoration: none;
    display: block;
  }
  
  .dropdown-content a:hover {
    background-color: orange;
    color:white !important;
}
  .dropdown:hover .dropdown-content {display: block;}
  .dropdown:hover .dropbtn {color: white;}
</style>

<div class="employees-actions">
    <div class="employees-nav me-1 mb-2">
        <div class="nav-titles">
            <div class="dropdown">
            <button class="dropbtn">Recruitment &#9660;</button>
            <div class="dropdown-content">
                <a href="{{ route('job.index') }}">{{ __("Jobs") }} </a>
                <a href="{{ route('job-requisition.index') }}">{{ __("Job Requisition") }} </a>
                <a href="{{ route('job.create') }}">{{ __("Job Create") }} </a>
                <a href="{{ route('job-template.index') }}">{{ __('Job Templates') }}</a>
                <a href="{{ route('job-application.index') }}">{{ __('Job Application') }}</a>
                <a href="{{ route('job.on.board') }}">{{ __('Job On-Boarding') }}</a>
                <a href="{{ route('personlized-onboarding.index') }}">{{ __('Job On-Boarding Templates') }}</a>
                <a href="{{ route('question-template.index') }}">{{ __('Question Templates') }}</a>
                <a href="{{ route('custom-question.index') }}">{{ __('Custom Question') }}</a>
                <a href="{{ route('interview-schedule.index') }}">{{ __('Interview Schedule') }}</a>
                <a href="{{ route('config-word-count.index') }}">{{ __('Config Word Count') }}</a>
                <a href="{{ route('career', [\Auth::user()->creatorId(), \Auth::user()->lang]) }}">{{ __('Job Opening') }}</a>
            </div>
            </div>
        </div>
    </div>
</div>

        <div class="col-sm-12">
            <div class="mt-2" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['job-application.index'], 'method' => 'get', 'id' => 'applicarion_filter']) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box"></div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('start_date', $filter['start_date'], ['class' => 'month-btn form-control current_date']) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : '', ['class' => 'month-btn form-control current_date', 'autocomplete' => 'off']) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('job', __('Job'), ['class' => 'form-label']) }}
                                            {{ Form::select('job', $jobs, $filter['job'], ['class' => 'form-control select ', 'id' => 'job_id']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto mt-4">
                                        <a href="#" class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('applicarion_filter').submit(); return false;"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('job-application.index') }}" class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">
                                            <span class="btn-inner--icon"><i
                                                    class="ti ti-trash-off text-white-off "></i></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

    <div class="card overflow-hidden mt-0">
        <div class="container-kanban">
            @php
                $json = [];
                foreach ($stages as $stage) {
                    $json[] = 'kanban-blacklist-' . $stage->id;
                }
                
            @endphp

            <div class="row kanban-wrapper horizontal-scroll-cards" data-plugin="dragula"
                data-containers='{!! json_encode($json) !!}'>
                @foreach ($stages as $key => $stage)
                    @php $applications = $stage->applications($filter) @endphp

                    <div class="col">
                        <div class="card">

                            <div class="card-header">
                                <div class="float-end">
                                    <span class="btn btn-sm btn-primary btn-icon count">
                                        {{ count($applications) }}
                                    </span>
                                </div>
                                <h4 class="mb-0">{{ $stage->title }}</h4>
                            </div>

                            <div class="card-body kanban-box" id="{{ $json[$key] }}" data-id="{{ $stage->id }}">
                                @foreach ($applications as $application)
                                    <div class="card" data-id="{{ $application->id }}">
                                        <div class="pt-3 ps-3">
                                        </div>
                                        <div class="card-header border-0 pb-0 position-relative">
                                            <h5><a
                                                    href="{{ route('job-application.show', \Crypt::encrypt($application->id)) }}">{{ $application->name }}</a>
                                            </h5>
                                            <!-- <div class="card-header-right">

                                                    <div class="btn-group card-option">
                                                        <button type="button" class="btn dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @can('Show Job Application')
        <a class="dropdown-item"
                                                                        href="{{ route('job-application.show', \Crypt::encrypt($application->id)) }}"
                                                                        class="dropdown-item"> {{ __('Show') }}</a>
    @endcan
                                                            @can('Delete Job Application')
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['job-application.destroy', $application->id],
            'id' => 'delete-form-' . $application->id,
        ]) !!}
                                                                    <a href="#!" class="dropdown-item bs-pass-para">
                                                                        <span> {{ __('Delete') }} </span>
                                                                    </a>
                                                                    {!! Form::close() !!}
    @endcan


                                                        </div>
                                                    </div>

                                                </div> -->


                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @can('Show Job Application')
                                                            <a href="{{ route('job-application.show', \Crypt::encrypt($application->id)) }}"
                                                                class="dropdown-item" data-ajax-popup="true"><i
                                                                    class="ti ti-eye "></i><span
                                                                    class="ms-2">{{ __('Show') }}</span></a>
                                                        @endcan

                                                        @can('Delete Job Application')
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['job-application.destroy', $application->id],
                                                                'id' => 'delete-form-' . $application->id,
                                                            ]) !!}
                                                            <a href="#" class="bs-pass-para dropdown-item"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"><i
                                                                    class="ti ti-trash"></i><span
                                                                    class="ms-2">{{ __('Delete') }}</span></a>
                                                            {!! Form::close() !!}
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <ul class="list-inline mb-0 mt-0">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-12">
                                                            <span class="static-rating static-rating-sm d-block">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $application->rating)
                                                                        <i class="star fas fa-star voted"></i>
                                                                    @else
                                                                        <i class="star fas fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <small
                                                        class="text-md">{{ !empty($application->jobs) ? $application->jobs->title : '' }}</small>


                                                    <li class="list-inline-item d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" title="{{ __('') }}">
                                                        <i class="ti ti-clock me-2" data-ajax-popup="true"
                                                            data-title="{{ __('Applied at') }}"></i>{{ \Auth::user()->dateFormat($application->created_at) }}
                                                    </li>
                                                </ul>
                                                @php
                                                    $logo = \App\Models\Utility::get_file('uploads/avatar/');
                                                    $profile = \App\Models\Utility::get_file('uploads/job/profile/');
                                                @endphp
                                                <div class="avatar-group hover-avatar-ungroup">
                                                    <a href="#" class="user-group">

                                                        <img src="{{ !empty($application->profile) ? $profile . $application->profile : $logo . 'avatar.png' }}"
                                                            class="hweb " style="width: 28px">
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <span class="empty-container" data-placeholder="Empty"></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;
            $('.current_date').val(today);
        });
    </script>
@endpush
