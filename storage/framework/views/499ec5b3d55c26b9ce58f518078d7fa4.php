<?php
    $chatgpt = Utility::getValByName('enable_chatgpt');
?>



<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('GP Notes')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/summernote/summernote-bs4.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/dropzone.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-page'); ?>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
    <script src="<?php echo e(asset('css/summernote/summernote-bs4.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/dropzone-amd-module.min.js')); ?>"></script>
    
    
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Lead Detail')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0"></h5>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('gpnote.index')); ?>"><?php echo e(__('GP Notes')); ?></a></li>
    <li class="breadcrumb-item active" aria-current="page"></li><?php echo e(__('GPNote Detail')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <div class="col-md-12 text-end d-flex ">

        <?php if($chatgpt == 'on'): ?>
            <div class="text-end pt-2">
                <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
                    data-url="<?php echo e(route('generate', ['contracts'])); ?>" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="<?php echo e(__('Generate')); ?>" data-title="<?php echo e(__('Generate Content With AI')); ?>">
                    <i class="fas fa-robot"></i><?php echo e(__(' Generate With AI')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top" style="top:30px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#general"
                                    class="list-group-item list-group-item-action border-0"><?php echo e(__('General')); ?> <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#attachments"
                                    class="list-group-item list-group-item-action border-0"><?php echo e(__('Attachment')); ?> <div
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
                                                    <div class="col-sm-4 h6 text-sm"><?php echo e(__('Employee Name')); ?></div>
                                                    <div class="col-sm-8 text-sm"> <?php echo e($gpnote->employee->name); ?></div>

                                                    <div class="col-sm-4 h6 text-sm"><?php echo e(__('Presenting Complaint')); ?></div>
                                                    <div class="col-sm-8 text-sm"> <?php echo e($gpnote->presenting_complaint); ?></div>

                                                    <div class="col-sm-4 h6 text-sm"><?php echo e(__('Assessment Date')); ?></div>
                                                    <div class="col-sm-8 text-sm">
                                                        <?php echo e(Auth::user()->dateFormat($gpnote->assessment_date)); ?></div>
                                                </div>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><?php echo e(__('Details ')); ?></h5>
                                </div>
                                <div class="card-body p-3">
                                    <?php echo e(Form::open(['route' => ['gpnote.detail.store', $gpnote->id]])); ?>

                                    <div class="col-md-12">
                                        <div class="form-group mt-3">
                                            <textarea class="summernote-simple" name="detail" id="contract_description" rows="3"><?php echo $gpnote->plan; ?></textarea>
                                        </div>
                                    </div>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Health And Fitness')): ?>
                                        <div class="col-md-12 text-end">
                                            <div class="form-group mt-3 me-3">
                                                <?php echo e(Form::submit(__('Add'), ['class' => 'btn  btn-primary'])); ?>

                                            </div>
                                        </div>
                                        <?php echo e(Form::close()); ?>

                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                        <div id="attachments">
                            <div class="row ">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5><?php echo e(__('Attachments')); ?></h5>
                                        </div>
                                        <div class="card-body">
                                            <?php if(\Auth::user()->type == 'company' || \Auth::user()->type == 'hr'  || \Auth::user()->type == 'employee'): ?>
                                                <div class=" ">
                                                    <div class="col-md-12 dropzone browse-file" id="my-dropzone"></div>
                                                </div>
                                            <?php endif; ?>


                                            <?php $__currentLoopData = $gpnote->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class=" py-3">
                                                    <div class="list-group-item ">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <h6 class="text-sm mb-0">
                                                                    <a href="#!"><?php echo e($file->files); ?></a>
                                                                </h6>

                                                                <p class="card-text small text-muted">
                                                                    <?php echo e(number_format(\File::size(storage_path('gpnote_attachment/' . $file->files)) / 1048576, 2) . ' ' . __('MB')); ?>

                                                                </p>
                                                            </div>
                                                            <?php
                                                                $attachments = \App\Models\Utility::get_file('gpnote_attachment');
                                                            ?>
                                                            <div class="action-btn bg-warning p-0 w-auto    ">
                                                                <a href="<?php echo e($attachments . '/' . $file->files); ?>"
                                                                    class=" btn btn-sm d-inline-flex align-items-center"
                                                                    download="" data-bs-toggle="tooltip"
                                                                    title="Download">
                                                                    <span class="text-white"><i
                                                                            class="ti ti-download"></i></span>
                                                                </a>
                                                            </div>
                                                            <div class="col-auto actions">
                                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Health And Fitness')): ?>
                                                                    <?php if(\Auth::user()->id == $file->user_id || \Auth::user()->type == 'company' || \Auth::user()->type == 'hr' || \Auth::user()->type == 'employee'): ?>
                                                                        <div class="action-btn bg-danger ms-2">

                                                                            <form action=""></form>
                                                                            <?php echo Form::open(['method' => 'GET', 'route' => ['gpnote.file.delete', [$gpnote->id, $file->id]]]); ?>

                                                                            <a href="#!"
                                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                                data-bs-toggle="tooltip"
                                                                                data-bs-placement="top"
                                                                                title="<?php echo e(__('Delete')); ?>">
                                                                                <i class="ti ti-trash text-white"></i>
                                                                            </a>
                                                                            <?php echo Form::close(); ?>


                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    
    
    <script>
        $(document).on('click', '#comment_submit', function(e) {
            var curr = $(this);

            var comment = $('#formComment').val();



            if (comment != '') {
                $.ajax({
                    url: $('#commenturl').val(),
                    data: {
                        "comment": comment,
                        "_token": "<?php echo e(csrf_token()); ?>",
                    },
                    type: 'POST',
                    success: function(data) {

                        show_toastr('<?php echo e(__('Success')); ?>', 'Comment Create Successfully!', 'success');


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
                    "_token": "<?php echo e(csrf_token()); ?>",
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
            url: "<?php echo e(route('gpnote.file.upload', [$gpnote->id])); ?>",
            success: function(file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                    show_toastr('<?php echo e(__('Success')); ?>', 'Attachment Create Successfully!', 'success');
                } else {
                    myDropzone.removeFile(file);
                    show_toastr('<?php echo e(__('Error')); ?>', 'File type must be match with Storage setting.',
                        'error');
                }
                location.reload();

            },
            error: function(file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    show_toastr('<?php echo e(__('Error')); ?>', response.error, 'error');
                } else {
                    show_toastr('<?php echo e(__('Error')); ?>', response.error, 'error');
                }
            }
        });
        myDropzone.on("sending", function(file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("contract_id", <?php echo e($gpnote->id); ?>);

        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "action-btn btn-primary mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "<?php echo e(__('Download')); ?>");
            download.innerHTML = "<i class='fas fa-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "action-btn btn-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "<?php echo e(__('Delete')); ?>");
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
                                show_toastr('<?php echo e(__('Error')); ?>', response.error, 'error');
                            }
                        },
                        error: function(response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                show_toastr('<?php echo e(__('Error')); ?>', response.error, 'error');
                            } else {
                                show_toastr('<?php echo e(__('Error')); ?>', response.error, 'error');
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
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function(data) {
                    show_toastr('<?php echo e(__('Success')); ?>', 'Status Update Successfully!', 'success');
                    location.reload();
                }

            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hr_system\resources\views/gpnote/show.blade.php ENDPATH**/ ?>