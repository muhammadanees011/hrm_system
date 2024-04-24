@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($reviewquestion, ['route' => ['reviewquestions.update', $reviewquestion->id,$review_id], 'method' => 'PUT']) }}

<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['goal tracking']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <style>
    .hide {
        display: none; 
    }
    .show{
        display:block;
    }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('question', __('Question'), ['class' => 'col-form-label']) }}
                {{ Form::text('question', null, ['class' => 'form-control', 'placeholder' => 'enter review question', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-warning">
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
