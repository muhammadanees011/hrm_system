@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::open(['url' => 'store_notes/travel', 'method' => 'post']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['promotion']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        
        <input type="hidden" name="travel_id" value="{{ $travel->id }}">
        <div class="form-group col-lg-12">
            {{ Form::label('event_details', __('Event Details'), ['class' => 'col-form-label']) }}
            @if(\Auth::user()->type === 'employee')
            {{ Form::textarea('event_details', $travel->event_details, ['class' => 'form-control', 'placeholder' => __('Enter Event Details'),'rows'=>'3' , 'required' => 'required', 'readonly' => 'readonly']) }}
            @else
            {{ Form::textarea('event_details', $travel->event_details, ['class' => 'form-control', 'placeholder' => __('Enter Event Details'),'rows'=>'3' , 'required' => 'required']) }}
            @endif
        </div>

        <div class="form-group col-lg-12">
            {{ Form::label('venue', __('Venue Details'), ['class' => 'col-form-label']) }}
            @if(\Auth::user()->type === 'employee')
                {{ Form::textarea('venue', $travel->venue, ['class' => 'form-control', 'placeholder' => __('Enter Venue Details'), 'rows' => '3', 'required' => 'required', 'readonly' => 'readonly']) }}
            @else
                {{ Form::textarea('venue', $travel->venue, ['class' => 'form-control', 'placeholder' => __('Enter Venue Details'), 'rows' => '3', 'required' => 'required']) }}
            @endif
        </div>

        <div class="form-group col-lg-12">
            {{ Form::label('dress_code', __('Dress Code Details'), ['class' => 'col-form-label']) }}
            @if(\Auth::user()->type === 'employee')
            {{ Form::textarea('dress_code', $travel->dress_code, ['class' => 'form-control', 'placeholder' => __('Enter Dress Code'),'rows'=>'3' , 'required' => 'required', 'readonly' => 'readonly']) }}
            @else
            {{ Form::textarea('dress_code', $travel->dress_code, ['class' => 'form-control', 'placeholder' => __('Enter Dress Code'),'rows'=>'3' , 'required' => 'required']) }}
            @endif
        </div>
    </div>
</div>
@if(\Auth::user()->type != 'employee')
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
@endif

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