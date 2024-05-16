@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::open(['route' => ['compensationreview.update-comments'], 'method' => 'post']) }}
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
                @foreach($comments as $comment)
                <div>
                    <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 5%;width: 5%" />
                    {{$comment->commenter->name}}
                    <br>
                    <p>{{$comment->comment}}</p>
                    <hr>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::hidden('reviewee_id', $reviewee_id) }}
                {{ Form::hidden('review_id', $review_id) }}
            </div>
        </div>

        <div class="col-md-12">
             <div class="form-group">
                {{ Form::label('comment', __('Comment'),['class'=>'col-form-label']) }}
                {{ Form::textarea('comment',null, array('class' => 'form-control','rows'=>3,'required'=>'required')) }}
            </div>
        </div>
        

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Save') }}" class="btn btn-warning">
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
