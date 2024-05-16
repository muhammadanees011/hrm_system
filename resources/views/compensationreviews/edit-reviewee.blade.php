@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($compensationreviewee, ['route' => ['compensationreview.update-rewiewee', $compensationreviewee->id], 'method' => 'PUT']) }}
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
        <!-- <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('recomended_increase_amount', __('Recomended Increase(amount)'), ['class' => 'col-form-label']) }}
                {{ Form::number('recomended_increase_amount', null, ['class' => 'form-control', 'placeholder' => 'enter amount']) }}
            </div>
        </div> -->
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('recomended_increase_percentage', __('Recomended Increase %'), ['class' => 'col-form-label']) }}
                {{ Form::number('recomended_increase_percentage', null, ['class' => 'form-control', 'placeholder' => 'enter %']) }}
            </div>
        </div>
        <div class="form-group col-md-12">
            <label class="col-form-label">{{ __('Reviewers') }}</label>
            <div class="d-flex justify-content-between">
                <div class="form-check form-check">
                    <input class="form-check-input" type="radio" id="all" value="all"
                        name="who_can_review" @if ($compensationreviewee->who_can_review == 'all') checked @endif>
                    <label class="form-check-label" for="all">
                        {{ __('All') }}
                    </label>
                </div>
                <div class="form-check form-check">
                    <input class="form-check-input" type="radio" id="employee" value="employee"
                        name="who_can_review" @if ($compensationreviewee->who_can_review == 'employee') checked @endif>
                    <label class="form-check-label" for="employee">
                        {{ __('Employee') }}
                    </label>
                </div>
                <div class="form-check form-check">
                    <input class="form-check-input" type="radio" id="manager" value="manager"
                        name="who_can_review" @if ($compensationreviewee->who_can_review == 'manager') checked @endif>
                    <label class="form-check-label" for="manager">
                        {{ __('Manager') }}
                    </label>
                </div>
                <div class="form-check form-check">
                    <input class="form-check-input" type="radio" id="hr" value="hr"
                        name="who_can_review" @if ($compensationreviewee->who_can_review == 'hr') checked @endif>
                    <label class="form-check-label" for="hr">
                        {{ __('HR') }}
                    </label>
                </div>
                <div class="form-check form-check">
                    <input class="form-check-input" type="radio" id="select" value="select"
                        name="who_can_review" @if ($compensationreviewee->who_can_review == 'select') checked @endif>
                    <label class="form-check-label" for="select">
                        {{ __('Select') }}
                    </label>
                </div>
            </div>
            @error('who_to_review')
                <span class="invalid-role" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>


        @if($compensationreviewee->who_can_review != 'select')
        <div class="form-group col-md-12 select-reviewer hide">
            {{ Form::label('reviewer', __('Reviewer'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {{-- {!! Form::select('reviewer', $reviewer, null, ['class' => 'form-control select2 ', 'required' => 'required']) !!} --}}
                {{ Form::select('reviewer[]', $reviewer, null, ['class' => 'form-control select2 user-role', 'id' => 'choices-multiple', 'multiple' => '']) }}
            </div>
            @error('role')
                <span class="invalid-role" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        @else
        <div class="form-group col-12 switch-width select-reviewer hide">
            {{ Form::label('reviewer', __('reviewer'), ['class' => ' form-label']) }}
            <select name="reviewer[]" class="select2" id="reviewer" multiple>
                @foreach ($reviewer as $id => $name)
                    <option @if ($compensationreviewee->reviewer->contains('reviewer_id', $id)) selected @endif value="{{ $id }}">
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="form-group col-md-12">
            {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}
            <select name="status" class="form-control  select2" id="status">
                <option value="Pending">{{ __('Pending') }}</option>
                <option value="Approved">{{ __('Approved') }}</option>
                <option value="Rejected">{{ __('Rejected') }}</option>
            </select>
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('status', __('Eligible'), ['class' => 'col-form-label']) }}
            <select name="eligible" class="form-control  select2" id="status">
                <option value="yes">{{ __('Yes') }}</option>
                <option value="no">{{ __('No') }}</option>
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
    $(document).ready(function() {
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });


        // Function to handle showing or hiding the target element based on the selected radio button
        function handleRadioButtonChange() {
        const targetElement = document.querySelector('.select-reviewer');
        const radioButtons = document.querySelectorAll('.form-check-input:checked'); // Select only checked radio buttons

        radioButtons.forEach(function(radioButton) {
            const radioButtonName = radioButton.getAttribute('value');
            if (radioButtonName === "select") {
                targetElement.classList.add('show');
                targetElement.classList.remove('hide');
            } else {
                targetElement.classList.add('hide');
                targetElement.classList.remove('show');
            }
        });
    }

    // Function to attach event listeners to radio buttons
    function attachEventListeners() {
        const radioButtons = document.querySelectorAll('.form-check-input');
        radioButtons.forEach(function(radioButton) {
            radioButton.addEventListener('change', handleRadioButtonChange);
        });
    }

    window.addEventListener('DOMContentLoaded', callback())

    function callback(){
        attachEventListeners(); // Attach event listeners initially
        handleRadioButtonChange(); // Handle radio button change on page load
    }


</script>
