@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($trainingEventRequest, ['route' => ['trainingeventrequest.update', $trainingEventRequest->id], 'method' => 'PUT']) }}
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
        <div class="form-group col-md-12">
            {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}
            <select name="status" class="form-control  select2" id="status">
                <option value="Pending"  @if($trainingEventRequest->status=='Pending') selected @endif>{{ __('Pending') }}</option>
                <option value="Approved" @if($trainingEventRequest->status=='Approved') selected @endif>{{ __('Approved') }}</option>
                <option value="Rejected" @if($trainingEventRequest->status=='Rejected') selected @endif>{{ __('Rejected') }}</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-warning">
</div>
{{ Form::close() }}

<script>

</script>
