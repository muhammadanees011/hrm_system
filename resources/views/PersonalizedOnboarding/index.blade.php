@extends('layouts.admin')
@section('page-title')
{{ __('Manage Personlized Onboarding Templates') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Manage Personlized Onboarding Templates') }}</li>
@endsection

@section('content')
@foreach ($templates as $template)
<div class="col-xl-3">
    <div class="card  text-center">
        <div class="card-header border-0 pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <div class="badge p-2 px-3 rounded bg-primary">{{$template->branches->name}}{{$template->departments ? ', ' . $template->departments->name : ''}}</div>
                </h6>
            </div>
            <div class="card-header-right">
                <div class="btn-group card-option">
                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="feather icon-more-vertical"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="{{route('onboarding.personalized.show', $template->id)}}" class="dropdown-item" data-url="#" data-ajax-popup="true" data-title="{{ __('Preview Template') }}"><i class="ti ti-eye "></i><span class="ms-2">{{ __('Preview') }}</span></a>

                        <!-- <a href="#" class="dropdown-item" data-url="#" data-ajax-popup="true" data-title="{{ __('Update Template') }}"><i class="ti ti-edit "></i><span class="ms-2">{{ __('Edit') }}</span></a> -->

                        {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['personlized-onboarding.destroy', $template->id],
                        'id' => 'delete-form-' . $template->id,
                        ]) !!}
                        <a href="#" class="bs-pass-para dropdown-item" data-confirm="{{ __('Are You Sure?') }}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="delete-form-1" title="{{ __('Delete') }}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-trash"></i><span class="ms-2">{{ __('Delete') }}</span></a>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <h4 class="mt-2 text-primary">{{$template->name}}</h4>
            <!-- <small>asdasdadasd</small> -->
            <div class=" mb-0 mt-3">
                <div class=" p-3">
                    <div class="row">
                        <div class="col-6 text-start mt-4">
                            <h6 class="mb-0 px-3">{{count($template->questions)}}</h6>
                            <p class="text-muted text-sm mb-0">{{ __('Information Questions') }}</p>
                        </div>
                        <div class="col-6 text-end mt-4">
                            <h6 class="mb-0 px-3">{{count($template->files)}}</h6>
                            <p class="text-muted text-sm mb-0">{{ __('Files') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
<div class="col-xl-3 col-lg-4 col-sm-6">
    <a href="{{ route('personlized-onboarding.create') }}" class="btn-addnew-project " data-bs-toggle="tooltip" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
        <div class="bg-primary proj-add-icon">
            <i class="ti ti-plus"></i>
        </div>
        <h6 class="mt-4 mb-2">{{ __('New Onboading template') }}</h6>
        <p class="text-muted text-center">{{ __('Create New employee onboarding template') }}</p>
    </a>
</div>
@endsection

@push('scripts')
@endpush