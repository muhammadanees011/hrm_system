@extends('layouts.admin')
@section('page-title')
{{ __('Manage Job Requisition') }}
@endsection

@php
$lang = Auth::user()->lang;
@endphp

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Manage Job') }}</li>
@endsection
@push('script-page')
<script>
    $('.copy_link').click(function(e) {
        e.preventDefault();
        var copyText = $(this).attr('href');

        document.addEventListener('copy', function(e) {
            e.clipboardData.setData('text/plain', copyText);
            e.preventDefault();
        }, true);

        document.execCommand('copy');
        show_toastr('Success', 'Url copied to clipboard', 'success');
    });
</script>
@endpush
@section('action-button')

@can('Create Job')
<a href="{{ route('job-requisition.create') }}" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Job Requisition') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
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

<div class="col-xl-12">
    <div class="card">
        <div class="card-header card-body table-border-style">
            {{-- <h5> </h5> --}}
            <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>{{ __('Requester') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created At') }}</th>
                            @if (Gate::check('Edit Job') || Gate::check('Delete Job') || Gate::check('Show Job'))
                            <th width="200px">{{ __('Action') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                        <tr>
                            <td>{{ $job->createdBy->name }}</td>
                            <td>{{ $job->job_title }}</td>
                            <td>{{ \Auth::user()->dateFormat($job->start_date) }}</td>
                            <td>
                                @if ($job->status == 'Approved')
                                <span class="badge bg-success p-2 px-3 rounded status-badge">{{ $job->status }}</span>
                                @else
                                <span class="badge bg-danger p-2 px-3 rounded status-badge">{{ $job->status }}</span>
                                @endif
                            </td>
                            <td>{{ \Auth::user()->dateFormat($job->created_at) }}</td>
                            <td class="Action">
                                @if (Gate::check('Edit Job') || Gate::check('Delete Job') || Gate::check('Show Job'))
                                <span>

                                <div class="action-btn bg-primary ms-2">
                                    <a href="{{ route('job-requisition.approve', $job->id) }}"
                                    class="bg-primary text-white" data-toggle="tooltip"
                                    data-original-title="{{ __('Approve') }}"><i class="fas fa-exchange-alt"></i></a>
                                </div>

                                    <!-- @can('Show Job')
                                    <div class="action-btn bg-warning ms-2">
                                        <a href="{{ route('job.show', $job->id) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Job Detail') }}">
                                            <i class="ti ti-eye text-white"></i>
                                        </a>
                                    </div>
                                    @endcan


                                    @can('Edit Job')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="{{ route('job.edit', $job->id) }}" class="mx-3 btn btn-sm  align-items-center" data-url="" data-ajax-popup="true" data-title="{{ __('Edit Job') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan -->

                                    @can('Delete Job')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['job-requisition.destroy', $job->id], 'id' => 'delete-form-' . $job->id]) !!}
                                        <a href="#!" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Delete') }}">
                                            <i class="ti ti-trash text-white"></i></a>
                                        {!! Form::close() !!}
                                    </div>
                                    @endcan
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection