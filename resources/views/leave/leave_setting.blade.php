@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($setting, ['route' => ['leave.leave_clash'], 'method' => 'POST']) }}

<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['leave']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('book_same_day', __('Is it allowed to book holiday on Same Day?'), ['class' => 'col-form-label']) }}
                <select name="book_same_day" id="book_same_day" class="form-control select">
                    <option value="yes" @if($setting->value =='yes') selected @endif>{{ __('Yes') }}</option>
                    <option value="no" @if($setting->value =='no') selected @endif>{{ __('No') }}</option>
                </select>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        $('#timeDurationSection').hide();

        // hide leave duration field on initial load
        const selectedDuration = $('#leave_duration').val();
        if(selectedDuration==="full_day"){
            $('#timeDurationSection').hide();
        }else{
            $('#timeDurationSection').show();
        }

        $(document).on('change','#leave_duration',function() {
            $('#timeDurationSection').hide();
            const selectedOption = $(this).val();
            
            if (selectedOption === 'half_day') {
                $('#timeDurationSection').show();
            }
        });
    });
</script>