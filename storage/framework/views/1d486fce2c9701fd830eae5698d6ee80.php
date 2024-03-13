

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Leave CarryOver')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Leave CarryOver')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('action-button'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Retirement')): ?>
        <a href="#" data-url="<?php echo e(route('carryover.create')); ?>" data-ajax-popup="true"
            data-title="<?php echo e(__('Create New Retirement')); ?>" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Create')); ?>">
            <i class="ti ti-plus"></i>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                            <th><?php echo e(__('Employee')); ?></th>
                                <th><?php echo e(__('Leave Type')); ?></th>
                                <th><?php echo e(__('Leave Count')); ?></th>
                                <th><?php echo e(__('status')); ?></th>
                                <?php if(Gate::check('Edit Termination') || Gate::check('Delete Termination')): ?>
                                    <th width="200px"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                          

                            <?php $__currentLoopData = $carryrequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $carryover): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                            <td><?php echo e(!empty($carryover->employees) ? $carryover->employees->name : ''); ?></td>
                            <td><?php echo e(!empty($carryover->leaveType) ? $carryover->leaveType->title : ''); ?></td>
                            <td><?php echo e($carryover->leaves_count); ?> days</td>
                            <td>
                                <?php if($carryover->status == 'pending'): ?>
                                    <div class="badge bg-warning p-2 px-3 rounded"><?php echo e($carryover->status); ?></div>
                                <?php elseif($carryover->status == 'accepted'): ?>
                                    <div class="badge bg-success p-2 px-3 rounded"><?php echo e($carryover->status); ?></div>
                                <?php elseif($carryover->status == "rejected"): ?>
                                    <div class="badge bg-danger p-2 px-3 rounded"><?php echo e($carryover->status); ?></div>
                                <?php endif; ?>
                            </td>
                                <td class="Action">
                                    <?php if(Gate::check('Edit Termination') || Gate::check('Delete Termination')): ?>
                                        <span>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Retirement')): ?> 
                                            <div class="action-btn bg-success ms-2">
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                    data-url="<?php echo e(URL::to('carryover/' . $carryover->id . '/action')); ?>"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                    title="" data-title="<?php echo e(__('Leave Action')); ?>"
                                                    data-bs-original-title="<?php echo e(__('CarryOver Leave')); ?>">
                                                    <i class="ti ti-caret-right text-white"></i>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Retirement')): ?>
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                        data-url="<?php echo e(URL::to('carryover/' . $carryover->id . '/edit')); ?>"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="<?php echo e(__('Edit Leave CarryOver')); ?>"
                                                        data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Retirement')): ?>
                                                <div class="action-btn bg-danger ms-2">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['carryover.destroy', $carryover->id], 'id' => 'delete-form-' . $carryover->id]); ?>

                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hr_system\resources\views/carryover/index.blade.php ENDPATH**/ ?>