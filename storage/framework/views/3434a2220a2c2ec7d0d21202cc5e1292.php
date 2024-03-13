

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('GP Notes')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('GP Notes ')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('action-button'); ?>
    <div class="row align-items-center m-1">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Health And Fitness')): ?>
            <a href="#" data-size="lg" data-url="<?php echo e(route('gpnote.create')); ?>" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="<?php echo e(__('Create New GPNote')); ?>" data-title="<?php echo e(__('Create New GP Note')); ?>" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class='col-xl-12'>
        <div class="row">
            <div class="col-xl-3">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20"><?php echo e(__('Total GP Notes')); ?></h6>
                                <h3 class="text-primary"><?php echo e($cnt_gpnote['total']); ?></h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-success text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20"><?php echo e(__('This Month Total GP Notes')); ?></h6>
                                <h3 class="text-info"><?php echo e($cnt_gpnote['this_month']); ?></h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-info text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20"><?php echo e(__('This Week Total GP Notes')); ?></h6>
                                <h3 class="text-warning"><?php echo e($cnt_gpnote['this_week']); ?></h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-warning text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20"><?php echo e(__('Last 30 Days Total GP Notes')); ?></h6>
                                <h3 class="text-danger"><?php echo e($cnt_gpnote['last_30days']); ?></h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake bg-danger text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card table-card">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th width="60px"><?php echo e(__('#')); ?></th>
                                        <th><?php echo e(__('Employee Name')); ?></th>
                                        <th><?php echo e(__('Assessment Date')); ?></th>
                                        <th><?php echo e(__('Presenting Complaint')); ?></th>
                                        <th><?php echo e(__('FollowUp Date')); ?></th>
                                        <th width="150px"><?php echo e(__('Action')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $gpnotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gpnote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="Id">
                                                 <?php echo e($gpnote->id); ?>

                                            </td>
                                            <td><?php echo e($gpnote->employee->name); ?></td>
                                            <td><?php echo e($gpnote->assessment_date); ?></td>
                                            <td><?php echo e(\Illuminate\Support\Str::limit($gpnote->presenting_complaint, 40)); ?></td>
                                            <td><?php echo e($gpnote->follow_up_date); ?></td>
                                            <td class="Action">
                                                <span>
                                                    
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="<?php echo e(route('gpnote.show', $gpnote->id)); ?>"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="<?php echo e(__('View GP Note')); ?>"><i
                                                                class="ti ti-eye text-white"></i></a>
                                                    </div>
                                                    

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Contract')): ?>
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" data-size="lg"
                                                                data-url="<?php echo e(URL::to('gpnote/' . $gpnote->id . '/edit')); ?>"
                                                                data-ajax-popup="true" data-title="<?php echo e(__('Edit GPNote')); ?>"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="<?php echo e(__('Edit GPNote')); ?>"><i
                                                                class="ti ti-pencil text-white"></i></a>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Contract')): ?>
                                                        <div class="action-btn bg-danger ms-2">
                                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['gpnote.destroy', $gpnote->id]]); ?>

                                                            <a href="#!"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="<?php echo e(__('Delete GPNote')); ?>">
                                                                <span class="text-white"> <i class="ti ti-trash"></i></span>
                                                            </a>
                                                            <?php echo Form::close(); ?>

                                                        </div>
                                                    <?php endif; ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hr_system\resources\views/gpnote/index.blade.php ENDPATH**/ ?>