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

<div class="card  mt-0 pt-1 pb-1" style="background-color: #E5E4E2;">
    <div class="container-kanban">
        <div class="row" style=" height:80vh;">
            <div class="col-md-12 doc-folders pt-3">
            <div class="add-btn mb-3">
                <div class="folder-open">
                    <p>{{$directory_name}}</p>
                </div>
                <div>
                    <a href="#" data-url="{{ route('create.document',$dir_id) }}" data-ajax-popup="true" data-size="lg"
                        data-title="{{ __('Add New Document') }}" data-bs-toggle="tooltip" title=""
                        class="me-2 btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
            </div>
            <div class="checks-file">
                @foreach($documents as $document )
                <div class="files-list-doc">
                    <span class="pt-2 mx-3 my-2">
                    <a class="file-name" href="{{route('view.document_dir', $document->id)}}" target="_blank">
                        <img src="{{asset( '/assets/images/pdf.svg' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                        {{$document->file}}
                    </a>
                    </span>
                    <span class="action-btns">
                    @can('Manage Employee')
                    <div class="dropdown mx-3 my-2">
                            <i class="fas fa-bars " id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"></i>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="{{route('download.document_dir', ['file_id'=>$document->id,'dir_id'=>$dir_id])}}">Download file</a></li>
                            <li><a class="dropdown-item" href="{{route('delete.document_dir', ['file_id'=>$document->id,'dir_id'=>$dir_id])}}">Delete file</a></li>
                        </ul>
                    </div>
                    @endcan
                    </span>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>    

@endsection