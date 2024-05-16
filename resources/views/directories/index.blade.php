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

    @foreach($dirList as $dir)
        <div class="mb-5 mt-5" style="width:9rem;height:4rem; margin-bottom:10rem;"  onclick="redirectToDocumentDirectory('{{ route('documentdirectories.show', $dir->id) }}')">
            <div class="card p-0 dir"  style="width:8rem; height:9rem;">
                <div class="card-header border-0 pb-0">
                    <div class="card-header-right">
                        <div class="btn-group card-option" style="width:2rem !important;">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="feather icon-more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                            {!! Form::open(['method' => 'DELETE', 'route' => ['documentdirectories.destroy', $dir->id], 'id' => 'delete-form-' . $dir->id]) !!}
                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                            aria-label="Delete"><i
                            class="ti ti-trash text-danger"></i>Delete</a>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                        <div class="dir-name">
                        <img src="{{asset( '/assets/images/folder.png' )}}" height="40" width="40" alt="pdf" class="pdf-icon">
                        <p>{{$dir->name}}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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

@endsection

@push('script-page')
    <script>
        function redirectToDocumentDirectory(url) {
            window.location.href = url;
        }
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
