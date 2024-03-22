@extends('layouts.admin')
@section('page-title')
    {{ __('My Documents') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('My Documents') }}</li>
@endsection


@push('css-page')
    <link href="{{ asset('libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush


@push('script-page')
    {{-- <script src="{{ asset('libs/dragula/dist/dragula.min.js') }}"></script>
    <script src="{{ asset('libs/autosize/dist/autosize.min.js') }}"></script> --}}
    <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>

    <script>
    </script>
@endpush
@section('action-button')
    @can('Manage Employee')
        <a href="#" data-url="{{ route('employementcheck.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Employement Check') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection
@section('content')

        <div class="card  mt-0 pt-1 pb-1">
            <div class="container-kanban">
                <div class="row" style=" height:80vh;">
                    <div class="col-md-3 doc-folders ms-2 me-2">
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>DBS Checks</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>ID Checks</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>Right To Work</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>Employement History</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>References</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>Medical History</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>Eclaims</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>Resumes</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>Payslips</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>Contracts</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>Health Assessments</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>GP Notes</p>
                        </div>
                        <div class="folder-name">
                            <img src="{{asset( '/assets/images/folder.png' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                            <p>Self Certifications</p>
                        </div>
                    </div>
                    <div class="col-md-8 doc-folders pt-3">
                    <div class="add-btn mb-3">
                        <div class="folder-open">
                            <p>Health Assessments</p>
                        </div>
                        <div>
                            <input type="text" class="me-2" placeholder="Search..."/>
                            <a href="#" data-url="{{ route('employementcheck.create') }}" data-ajax-popup="true" data-size="lg"
                                data-title="{{ __('Create New Employement Check') }}" data-bs-toggle="tooltip" title=""
                                class="me-2 btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                                <i class="ti ti-search"></i>
                            </a>
                            <a href="#" data-url="{{ route('employementcheck.create') }}" data-ajax-popup="true" data-size="lg"
                                data-title="{{ __('Create New Employement Check') }}" data-bs-toggle="tooltip" title=""
                                class="me-2 btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                                <i class="ti ti-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="checks-file">
                        @for($i=0; $i < 9; $i++ )
                        <div class="files-list-doc">
                            <span class="pt-2">
                            <a class="file-name" href="#" target="_blank">
                                <img src="{{asset( '/assets/images/pdf.svg' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                                dbs-checks-anees
                            </a>
                            </span>
                            <span class="action-btns">
                            @can('Manage Employee')
                            <a href="#" class="btn btn-md file-download"
                                data-bs-toggle="tooltip" title="" data-bs-original-title="Download">
                                <span class="btn-inner--icon text-warning">
                                    <i class="ti ti-download text-danger-off "></i>
                                </span>
                            </a>
                            @endcan
                            @can('Delete Employee')
                            <a href="#" class="btn btn-md file-delete"
                                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete">
                                <span class="btn-inner--icon text-danger">
                                    <i class="ti ti-trash-off text-danger-off "></i>
                                </span>
                            </a>
                            @endcan
                            </span>
                        </div>
                        <hr class="files-hr">
                        @endfor
                        </div>
                    </div>
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
