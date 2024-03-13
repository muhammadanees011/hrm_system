@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

@extends('layouts.admin')

@section('page-title')
    {{ __('GP Notes') }}
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
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('gpnote.index') }}">{{ __('GP Notes') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page"></li>{{ __('GPNote Detail') }}
@endsection

@section('action-button')
    <div class="col-md-12 text-end d-flex ">

        @if ($chatgpt == 'on')
            <div class="text-end pt-2">
                <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
                    data-url="{{ route('generate', ['contracts']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                    <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
                </a>
            </div>
        @endif
    </div>
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
                                <a href="#general"
                                    class="list-group-item list-group-item-action border-0">{{ __('General') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#attachments"
                                    class="list-group-item list-group-item-action border-0">{{ __('Attachment') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9">
                        <div id="general">
                            <div class="row">
                                <div class="col-xxl-12">
                                    <div class="card report_card total_amount_card">
                                        <div class="card-body pt-0 ">
                                            <address class="mb-0 text-sm">
                                                <div class="row mt-3 align-items-center">
                                                    <div class="col-sm-4 h6 text-sm">{{ __('Employee Name') }}</div>
                                                    <div class="col-sm-8 text-sm"> {{ $gpnote->employee->name }}</div>

                                                    <div class="col-sm-4 h6 text-sm">{{ __('Presenting Complaint') }}</div>
                                                    <div class="col-sm-8 text-sm"> {{ $gpnote->presenting_complaint }}</div>

                                                    <div class="col-sm-4 h6 text-sm">{{ __('Assessment Date') }}</div>
                                                    <div class="col-sm-8 text-sm">
                                                        {{ Auth::user()->dateFormat($gpnote->assessment_date) }}</div>
                                                </div>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Details ') }}</h5>
                                </div>
                                <div class="card-body p-3">
                                    {{ Form::open(['route' => ['gpnote.detail.store', $gpnote->id]]) }}
                                    <div class="col-md-12">
                                        <div class="form-group mt-3">
                                            <textarea class="summernote-simple" name="detail" id="contract_description" rows="3">{!! $gpnote->plan !!}</textarea>
                                        </div>
                                    </div>
                                    @can('Create Health And Fitness')
                                        <div class="col-md-12 text-end">
                                            <div class="form-group mt-3 me-3">
                                                {{ Form::submit(__('Add'), ['class' => 'btn  btn-primary']) }}
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    @endcan
                                </div>

                            </div>
                        </div>

                        <div id="attachments">
                            <div class="row ">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Attachments') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr'  || \Auth::user()->type == 'employee')
                                                <div class=" ">
                                                    <div class="col-md-12 dropzone browse-file" id="my-dropzone"></div>
                                                </div>
                                            @endif


                                            @foreach ($gpnote->files as $file)
                                                <div class=" py-3">
                                                    <div class="list-group-item ">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <h6 class="text-sm mb-0">
                                                                    <a href="#!">{{ $file->files }}</a>
                                                                </h6>

                                                                <p class="card-text small text-muted">
                                                                    {{ number_format(\File::size(storage_path('gpnote_attachment/' . $file->files)) / 1048576, 2) . ' ' . __('MB') }}
                                                                </p>
                                                            </div>
                                                            @php
                                                                $attachments = \App\Models\Utility::get_file('gpnote_attachment');
                                                            @endphp
                                                            <div class="action-btn bg-warning p-0 w-auto    ">
                                                                <a href="{{ $attachments . '/' . $file->files }}"
                                                                    class=" btn btn-sm d-inline-flex align-items-center"
                                                                    download="" data-bs-toggle="tooltip"
                                                                    title="Download">
                                                                    <span class="text-white"><i
                                                                            class="ti ti-download"></i></span>
                                                                </a>
                                                            </div>
                                                            <div class="col-auto actions">
                                                                @can('Manage Health And Fitness')
                                                                    @if (\Auth::user()->id == $file->user_id || \Auth::user()->type == 'company' || \Auth::user()->type == 'hr' || \Auth::user()->type == 'employee')
                                                                        <div class="action-btn bg-danger ms-2">

                                                                            <form action=""></form>
                                                                            {!! Form::open(['method' => 'GET', 'route' => ['gpnote.file.delete', [$gpnote->id, $file->id]]]) !!}
                                                                            <a href="#!"
                                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                                data-bs-toggle="tooltip"
                                                                                data-bs-placement="top"
                                                                                title="{{ __('Delete') }}">
                                                                                <i class="ti ti-trash text-white"></i>
                                                                            </a>
                                                                            {!! Form::close() !!}

                                                                        </div>
                                                                    @endif
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
    {{-- url: $("#form-comment").data('action'), --}}
    {{-- location.reload(); --}}
    <script>
        $(document).on('click', '#comment_submit', function(e) {
            var curr = $(this);

            var comment = $('#formComment').val();



            if (comment != '') {
                $.ajax({
                    url: $('#commenturl').val(),
                    data: {
                        "comment": comment,
                        "_token": "{{ csrf_token() }}",
                    },
                    type: 'POST',
                    success: function(data) {

                        show_toastr('{{ __('Success') }}', 'Comment Create Successfully!', 'success');


                        setTimeout(function() {
                            location.reload();
                        }, 500)
                        data = JSON.parse(data);
                        console.log(data);
                        data = JSON.parse(data);
                        console.log(data);
                        var html = "<div class='list-group-item px-0'>" +
                            "                    <div class='row align-items-center'>" +
                            "                        <div class='col-auto'>" +
                            "                            <a href='#' class='avatar avatar-sm rounded-circle ms-2'>" +
                            "                                <img src=" + data.default_img +
                            " alt='' class='avatar-sm rounded-circle'>" +
                            "                            </a>" +
                            "                        </div>" +
                            "                        <div class='col ml-n2'>" +
                            "                            <p class='d-block h6 text-sm font-weight-light mb-0 text-break'>" +
                            data.comment + "</p>" +
                            "                            <small class='d-block'>" + data.current_time +
                            "</small>" +
                            "                        </div>" +
                            "                        <div class='action-btn bg-danger me-4'><div class='col-auto'><a href='#' class='mx-3 btn btn-sm  align-items-center delete-comment' data-url='" +
                            data.deleteUrl +
                            "'><i class='ti ti-trash text-white'></i></a></div></div>" +
                            "                    </div>" +
                            "                </div>";

                        $("#comments").prepend(html);
                        $("#form-comment textarea[name='comment']").val('');
                        load_task(curr.closest('.task-id').attr('id'));
                        show_toastr('success', 'Comment Added Successfully!');
                    },
                    error: function(data) {
                        show_toastr('error', 'Some Thing Is Wrong!');
                    }
                });
            } else {
                show_toastr('error', 'Please write comment!');
            }
        });


        $(document).on("click", ".delete-comment", function() {
            var btn = $(this);

            $.ajax({
                url: $(this).attr('data-url'),
                type: 'DELETE',
                dataType: 'JSON',
                data: {
                    "comment": comment,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    load_task(btn.closest('.task-id').attr('id'));
                    show_toastr('success', 'Comment Deleted Successfully!');
                    btn.closest('.list-group-item').remove();
                },
                error: function(data) {
                    data = data.responseJSON;
                    if (data.message) {
                        show_toastr('error', data.message);
                    } else {
                        show_toastr('error', 'Some Thing Is Wrong!');
                    }
                }
            });
        });
    </script>

    <script>
        Dropzone.autoDiscover = true;
        myDropzone = new Dropzone("#my-dropzone", {
            maxFiles: 20,
            // maxFilesize: 209715200,
            parallelUploads: 1,
            // acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{ route('gpnote.file.upload', [$gpnote->id]) }}",
            success: function(file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                    show_toastr('{{ __('Success') }}', 'Attachment Create Successfully!', 'success');
                } else {
                    myDropzone.removeFile(file);
                    show_toastr('{{ __('Error') }}', 'File type must be match with Storage setting.',
                        'error');
                }
                location.reload();

            },
            error: function(file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    show_toastr('{{ __('Error') }}', response.error, 'error');
                } else {
                    show_toastr('{{ __('Error') }}', response.error, 'error');
                }
            }
        });
        myDropzone.on("sending", function(file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("contract_id", {{ $gpnote->id }});

        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "action-btn btn-primary mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{ __('Download') }}");
            download.innerHTML = "<i class='fas fa-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "action-btn btn-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{ __('Delete') }}");
            del.innerHTML = "<i class='ti ti-trash'></i>";

            del.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        success: function(response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                show_toastr('{{ __('Error') }}', response.error, 'error');
                            }
                        },
                        error: function(response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                show_toastr('{{ __('Error') }}', response.error, 'error');
                            } else {
                                show_toastr('{{ __('Error') }}', response.error, 'error');
                            }
                        }
                    })
                }
            });

            var html = document.createElement('div');
            html.setAttribute('class', "text-center mt-10");
            html.appendChild(download);
            html.appendChild(del);

            file.previewTemplate.appendChild(html);
        }
    </script>

    <script>
        $(document).on("click", ".status", function() {

            var status = $(this).attr('data-id');
            var url = $(this).attr('data-url');
            $.ajax({
                url: url,
                type: 'POST',
                data: {

                    "status": status,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    show_toastr('{{ __('Success') }}', 'Status Update Successfully!', 'success');
                    location.reload();
                }

            });
        });
    </script>
@endpush
