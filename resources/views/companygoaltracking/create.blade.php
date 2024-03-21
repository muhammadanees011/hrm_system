@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::open(['url' => 'companygoaltracking', 'method' => 'post']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['healthassessment']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
         <div class="form-group col-md-6">
            {{ Form::label('leaves_count', __('Project'), ['class' => 'col-form-label']) }}
            {{ Form::text('leaves_count', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
        </div>
        <!-- <div class="col-md-6 form-group">
            {{ Form::label('employee_id', __('Member'),['class'=>'col-form-label']) }}
            {{ Form::select('employee_id', $employees,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div> -->
        <div class="form-group col-6 switch-width mt-2">
            {{ Form::label('local_storage_validation', __('Members'), ['class' => ' form-label']) }}
            <select name="local_storage_validation[]" class="select2"
                id="local_storage_validation" multiple>
                @foreach ($employees as $f)
                    <option>
                        {{ $f }}</option>
                @endforeach
            </select>
        </div>

    </div>
</div>


<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Create')}}</button>
   
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