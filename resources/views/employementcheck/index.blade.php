@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Employement Checks') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Employement Checks') }}</li>
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


    @can('Manage Employee')
        <a href="#" data-url="{{ route('employementcheck.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Employement Check') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection
@section('content')
        <!-- <div class="col-sm-12">
            <div class="mt-2" id="multiCollapseExample1">
                <div class="col">
                    <div class="row mb-2">
                        <div class="col-xl-11 col-lg-11 col-md-11 col-sm-12 col-12">
                            <div class="btn-box">
                                {{ Form::label('employee', __('Search'), ['class' => 'form-label']) }}
                                {{ Form::text('employee', null, ['class' => 'form-control ', 'placeholder' => 'Search...']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="row mt-2">
                                <div class="col-auto mt-4">
                                    <a href="#" class="btn btn-sm btn-primary"
                                        onclick="document.getElementById('applicarion_filter').submit(); return false;"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

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
                    <div class="col-md-8 doc-folders">

                    </div>
                </div>
            </div>
        </div>

    <div class="card  mt-0 pt-5">
        <div class="container-kanban">
            <div class="row pt- kanban-wrapper horizontal-scroll-cards" style=" height:90vh;">
                    <!-- Dynamic Column -->
                    @foreach($employementchecktypes as $employementchecktype)
                    <div class="col">
                        <div class="">

                            <div class="card-header ps-3">
                                <div class="float-end">
                                    <!-- <span class="btn btn-sm btn-primary btn-icon count">
                                        20
                                    </span> -->
                                </div>
                                <div style="background-color:#F6F7F8; height:50px; border-radius:5px; display:flex; justify-content:center;align-items:center;">
                                    <h4 class="mb-0" >{{$employementchecktype->title}}</h4>
                                </div>
                            </div>
                            <div class="employement-checks-list" style="border-left:0.1rem solid rgba(0, 0, 0, 0.1); height:85vh;">
                            @foreach($employementchecks as $employementcheck)
                            @if($employementchecktype->title == $employementcheck->employementcheckType->title)
                                <div class="checks-file">
                                    <span>
                                    <a class="file-name" href="{{ route('employementcheck.view.file', ['filename' => $employementcheck->files]) }}" target="_blank">
                                        <img src="{{asset( '/assets/images/pdf.svg' )}}" height="20" width="20" alt="pdf" class="pdf-icon">
                                        {{Str::limit($employementcheck->files, 30)}}
                                    </a>
                                    </span>
                                    <span>
                                    @can('Manage Employee')
                                    <a href="{{ route('employementcheck.download.file', ['filename' => $employementcheck->files]) }}" class="btn btn-md file-download"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Download">
                                        <span class="btn-inner--icon text-warning">
                                            <i class="ti ti-download text-danger-off "></i>
                                        </span>
                                    </a>
                                    @endcan
                                    @can('Delete Employee')
                                    <a href="{{ route('employementcheck.delete', $employementcheck->id) }}" class="btn btn-md file-delete"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete">
                                        <span class="btn-inner--icon text-danger">
                                            <i class="ti ti-trash-off text-danger-off "></i>
                                        </span>
                                    </a>
                                    @endcan
                                    </span>
                                </div>
                                <hr>
                            @endif
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
