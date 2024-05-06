
{{ Form::open(['url' => 'holiday-carryover', 'method' => 'post']) }}
<div class="modal-body">

    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('total_days', __('Total Days'), ['class' => 'col-form-label']) }}
            {{ Form::text('total_days', null, ['class' => 'form-control','placeholder'=>'Enter Total Days']) }}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Request') }}" class="btn btn-primary">
</div>

{{ Form::close() }}

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