<?php echo e(Form::open(['url' => 'employementcheck', 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6 mt-3">
            <?php echo e(Form::label('employementchecktype', __('Employement Check Type'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('employementchecktype', $employementchecktypes, null, ['class' => 'form-control select2', 'required' => 'required'])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('employee_id', __('Employee'), ['class' => 'col-form-label'])); ?>

            <?php echo e(Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'required' => 'required'])); ?>


        </div>
        <div class="form-group col-md-6">
            <input type="file" class="form-control" name="file"
            accept="image/*" >
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH C:\xampp\htdocs\hr_system\resources\views/employementcheck/create.blade.php ENDPATH**/ ?>