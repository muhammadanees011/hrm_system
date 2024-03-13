<?php
    $chatgpt = Utility::getValByName('enable_chatgpt');
?>

<?php echo e(Form::open(['url' => 'gpnote', 'method' => 'post'])); ?>

<div class="modal-body">

    <?php if($chatgpt == 'on'): ?>
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="<?php echo e(route('generate', ['gpnote'])); ?>"
            data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Generate')); ?>"
            data-title="<?php echo e(__('Generate Content With AI')); ?>">
            <i class="fas fa-robot"></i><?php echo e(__(' Generate With AI')); ?>

        </a>
    </div>
    <?php endif; ?>

    <div class="row">
        
        <div class="col-md-6 form-group">
            <?php echo e(Form::label('employee_id', __('Employee Name'),['class'=>'col-form-label'])); ?>

            <?php echo e(Form::select('employee_id', $employees,null, array('class' => 'form-control select2','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('assessment_date', __('Assessment Date'),['class'=>'col-form-label'])); ?>

            <?php echo e(Form::date('assessment_date', null, array('class' => 'form-control current_date','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('follow_up_date', __('Follow Up Date'),['class'=>'col-form-label'])); ?>

            <?php echo e(Form::date('follow_up_date', null, array('class' => 'form-control current_date','required'=>'required'))); ?>

        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('presenting_complaint', __('Presenting Complaint'),['class'=>'col-form-label'])); ?>

                <?php echo e(Form::textarea('presenting_complaint', '', array('class' => 'form-control', 'rows' => '3'))); ?>

            </div>
        </div>
    </div>
</div>


<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
    <button type="submit" class="btn  btn-primary"><?php echo e(__('Create')); ?></button>
   
</div>
<?php echo e(Form::close()); ?>


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
</script><?php /**PATH C:\xampp\htdocs\hr_system\resources\views/gpnote/create.blade.php ENDPATH**/ ?>