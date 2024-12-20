@extends('layouts.admin')
@push('css-page')
<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
<script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
@endpush
@section('page-title')
  {{ __("Privacy Policies") }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
    <li class="breadcrumb-item">{{ __("Privacy Policy") }}</li>
@endsection

@section('action-button')
    @can('Create Branch')
        <a href="#" data-url="{{ route('privacy-policy.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Policy') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
        <div class="col-3">
            @include('layouts.hrm_setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-body table-border-style">

                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Status')}}</th>
                                <th width="200px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($privacyPolicy as $pp)
                                    <tr>
                                        <td>{{ $pp->name }}</td>
                                        <td>
                                            @if($pp->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">InActive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('Edit Branch')
                                                <a href="#" data-url="{{ route('privacy-policy.edit', $pp->id) }}" data-ajax-popup="true" data-title="{{ __('Edit Policy') }}" class="edit-icon" data-bs-toggle="tooltip" data-original-title="{{ __('Edit') }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endcan
                                            @can('Delete Branch')
                                                <a href="#" data-url="{{ route('privacy-policy.destroy', $pp->id) }}" data-ajax-popup="true" data-title="{{ __('Delete Policy') }}" class="delete-icon" data-bs-toggle="tooltip" data-original-title="{{ __('Delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endcan
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
