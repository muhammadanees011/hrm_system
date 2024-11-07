@php
$chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

@extends('layouts.admin')

@section('page-title')
{{ __('Manage Personal Files') }}
@endsection

@push('css-page')
<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
@endpush

@push('script-page')
<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>
<script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script> --}}
{{-- <script>
        if ($(".pc-tinymce-2").length) {
            tinymce.init({
                selector: '.pc-tinymce-2',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }'
            });
        }
    </script> --}}
@endpush

@section('page-title')
{{ __('Lead Detail') }}
@endsection

@section('title')
<div class="d-inline-block">
    <h5 class="h4 d-inline-block font-weight-400 mb-0"></h5>
</div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('employee.index') }}">{{ __('Employees') }}</a></li>
<li class="breadcrumb-item active" aria-current="page"></li>{{ __('Personal File') }}
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#general" class="list-group-item list-group-item-action border-0">{{ __('General') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#uploaddocument" class="list-group-item list-group-item-action border-0">{{ __('Upload Document') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#documents" class="list-group-item list-group-item-action border-0">{{ __('Documents') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    <div id="general">
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="row">
                                    <div class="col-lg-12 col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-files"></i>
                                                </div>
                                                <h6 class="mb-3 mt-4">{{ __('Documents') }}</h6>
                                                <h3 class="mb-0">{{ count($personalFiles) }}</h3>
                                                <h3 class="mb-0"></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-4">
                                <div class="card report_card total_amount_card">
                                    <div class="card-body pt-0 ">
                                        <address class="mb-0 text-sm">
                                            <div class="row mt-3 align-items-center">
                                                <h6 class="mb-2">{{ __('Employee Personal Detail') }}</h6>
                                                <div class="row pb-2">
                                                    <div class="col-sm-5 font-semibold text-md">{{ __('Name:') }}</div>
                                                    <div class="col-sm-7 text-md"> {{ $employee->name }}</div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-sm-5 font-semibold text-md">{{ __('Email:') }}</div>
                                                    <div class="col-sm-7 text-md"> {{ $employee->email }}</div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-sm-5 font-semibold text-md">{{ __('DOB:') }}</div>
                                                    <div class="col-sm-7 text-md"> {{ \Auth::user()->dateFormat($employee->dob) }}</div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-sm-5 font-semibold text-md">{{ __('Gender:') }}</div>
                                                    <div class="col-sm-7 text-md"> {{ $employee->gender }}</div>
                                                </div>
                                            </div>
                                        </address>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-4">
                                <div class="card report_card total_amount_card">
                                    <div class="card-body pt-0 ">
                                        <address class="mb-0 text-sm">
                                            <div class="row mt-3 align-items-center">
                                                <h6 class="mb-2">{{ __('Employee Company Detail') }}</h6>
                                                <div class="row pb-2">
                                                    <div class="col-sm-5 font-semibold text-md">{{ __('Branch:') }}</div>
                                                    <div class="col-sm-7 text-md"> {{ $employee->branch->name }}</div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-sm-5 font-semibold text-md">{{ __('Department:') }}</div>
                                                    <div class="col-sm-7 text-md"> {{ $employee->department->name }}</div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-sm-5 font-semibold text-md">{{ __('Designation:') }}</div>
                                                    <div class="col-sm-7 text-md"> {{ $employee->designation->name }}</div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-sm-5 font-semibold text-md">{{ __('Date of joining:') }}</div>
                                                    <div class="col-sm-7 text-md"> {{ \Auth::user()->dateFormat($employee->company_doj) }}</div>
                                                </div>
                                            </div>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="uploaddocument">
                        <div class="row ">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Upload Document') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        {{ Form::open(['route' => ['employee.storePersonalFile'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                        <div class="row">
                                            {!! Form::hidden('employee_id', $employee->id, ['id' => 'employee_id']) !!}
                                            <div class="col-5">
                                                <div class="form-group">
                                                    {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
                                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Document name')]) }}
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="form-group">
                                                    {{ Form::label('file', __('Document'), ['class' => 'col-form-label']) }}
                                                    <div class="choose-file form-group">
                                                        <label for="file" class="form-label">
                                                            <input type="file" name="file" id="file" class="form-control {{ $errors->has('file') ? ' is-invalid' : '' }}" data-filename="file_selection">
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('file') }}
                                                            </div>
                                                        </label>
                                                        <p class="file_selection"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2 m-auto">
                                                <input type="submit" value="{{ __('Upload') }}" class="btn btn-primary mt-2">
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="documents" role="tabpanel" aria-labelledby="pills-comments-tab">
                        <div class="row pt-2">
                            <div class="col-12">
                                <div id="comment">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Documents') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive" style="overflow: hidden;">
                                                <div class="row">
                                                    <div class="col-sm-12 card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped dataTable">
                                                                <thead class="">
                                                                    <tr>
                                                                        <th scope="col">{{ __('name') }}
                                                                        </th>
                                                                        <th scope="col">{{ __('file') }}
                                                                        </th>
                                                                        <th scope="col">{{ __('Action') }}
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if(count($personalFiles) > 0)
                                                                    @foreach ($personalFiles as $personalFile)
                                                                    <tr role="row">
                                                                        <td>{{ $personalFile->name }}</td>
                                                                        <td>{{ $personalFile->file }}</td>
                                                                        <td>
                                                                            <a href="{{ asset('storage/uploads/personalFile/' . $personalFile->file) }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Download')}}" target="_blank">
                                                                                <i class="ti ti-download text-white"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                    @else
                                                                    <tr role="row">
                                                                        <td colspan="3" class="text-center">No document found</td>
                                                                    </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('script-page')
@endpush