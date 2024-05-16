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
    <!-- @can('Manage Employee')
        <a href="#" data-url="{{ route('employementcheck.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Employement Check') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan -->
@endsection
@section('content')

<style>
    .dir-name p{
        font-size:12px;
        font-weight:600;
    }
    .dir-name{
        height:90px;
        width:100px;
    }
    .dir:hover{
        border:2px solid orange;
        border-radius:4px;
        padding:5px;
        cursor:pointer;
    }

</style>

    @for($i=0; $i< 10; $i++)
        <div class="mb-5 mt-5" style="width:9rem;height:4rem; margin-bottom:10rem;">
            <div class="card p-0 dir"  style="width:8rem; height:9rem;">
                <div class="card-header border-0 pb-0">
                    <div class="card-header-right">
                        <div class="btn-group card-option" style="width:2rem !important;">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="feather icon-more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#" class="dropdown-item" data-url=""
                                    data-ajax-popup="true" data-title="{{ __('Update User') }}"><i
                                        class="ti ti-trash "></i><span class="ms-2 me-0">{{ __('Delete') }}</span></a>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                        <div class="dir-name">
                        <img src="{{asset( '/assets/images/folder.png' )}}" height="40" width="40" alt="pdf" class="pdf-icon">
                        <p>Health Assessments</p>
                    </div>
                </div>
            </div>
        </div>
    @endfor

    <div class="col-xl-2 col-lg-4 col-sm-6 mt-5">
        <a href="#" class="btn-addnew-project " data-ajax-popup="true" data-url="{{ route('documentdirectories.create') }}"
            data-title="{{ __('Create New Directory') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
            <div class="bg-warning proj-add-icon" style="width:40px;height:55px;">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{ __('New Directory') }}</h6>
            <p class="text-muted text-center">{{ __('Click here to add new directory') }}</p>
        </a>
    </div>

        <!-- <div class="card  mt-0 pt-1 pb-1" style="background-color: #E5E4E2;">
            <div class="container-kanban">
                <div class="row" style=" height:80vh;">
                    <div class="col-md-3 doc-folders " style="border-right:3px solid white !important;">
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
                    <div class="col-md-9 doc-folders pt-3">
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
                            <span class="pt-2 mx-3 my-2">
                            <a class="file-name" href="#" target="_blank">
                                <img src="{{asset( '/assets/images/pdf.svg' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                                dbs-checks-anees
                            </a>
                            </span>
                            <span class="action-btns">
                            @can('Manage Employee')
                            <div class="dropdown mx-3 my-2">
                                    <i class="fas fa-bars " id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#">Download file</a></li>
                                    <li><a class="dropdown-item" href="#">Delete file</a></li>
                                </ul>
                            </div>
                            @endcan
                            </span>
                        </div>
                        @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
     -->
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
