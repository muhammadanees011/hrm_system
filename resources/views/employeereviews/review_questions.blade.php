@extends('layouts.admin')
@section('page-title')
    {{ __('Review Assessment') }}
@endsection
@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Review Assessment') }}</li>
@endsection

@section('content')

<style>
    .reviewers-list{
        display:flex;
        flex-direction:column;
        justify-content:start;
        align-items:start;
    }
    .reviewer{
        display:flex;
        justify-content:start;
        align-items:center;
        width:22.5rem;
        height:3.5rem;
        background-color:#f5f5f5;
        margin-bottom:2px;
        padding-left:5px;
        border-left:3px solid #EE204D;
    }
    .reviewers-line{
        color:black;
        width: -webkit-fill-available;
    }
    .question{
        width:100%;
    }
    .review-options{
        width:100%;
        display:flex;
        justify-content:space-between;
        cursor:pointer;
    }
    .emoji{
        font-size: 24px;
    }
    .single-option-blur{
        opacity:0.4;
        cursor:pointer;
    }
    .single-option-slected{
        cursor:pointer;
    }
    .cycle-widget{
        background-color:#8db600;
        color:white;
        border-radius:10px;
        padding:0px 20px;
    }
</style>

<div class="col-md-12">
    <div class="row">
        <div class="col-xl-4">
            <div class="card text-center">
                <div class="card-header border-0 pb-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <img src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}" class="rounded-circle" style="width: 27%" />
                        <div>
                            <h6 class="mt-2 text-black">{{$user->name}}</h6>
                            <small style="color:#8db600;">{{ucfirst($user->type)}}</small>
                            <a href="{{ route('goaltracking.goals', $user->employee_id)}}" target="_blank">
                            <h6 style="color:#ffa21d;">View goals</h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card text-center">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex flex-column justify-content-start align-items-start">
                        <h6 class="mt-2 text-black">{{$user->name}}'s Review <span  style="color:#ffa21d;">({{ucfirst($user->type)}})</span></h6>
                        <small><span class="cycle-widget">{{$performancecycle->title}}</span> | created on {{ \Carbon\Carbon::parse($performancecycle->created_at)->format('m/d/Y') }}</small>
                    </div>
                    <div class="card-header-right">
                    </div>
                </div>
                <hr>
                <div class="card-body d-flex justify-content-start">
                    <div class="reviewers-list">
                        <h6 class="mt-2 text-black">ASSESSMENT</h6>
                        <p>To What extant do you agree with each of the following statements ?</p>
                        @foreach($reviewquestions as $index => $question)
                            <div class="reviews">
                                <p class="mt-4 question">{{ $index+1 }}. {{ $question->question }}</p>
                                <div class="review-options">
                                    <div class="single-option
                                        @php
                                            $isSelected = false;
                                        @endphp
                                        @foreach($question->reviewResult as $result)
                                            @if($result->question_id == $index && $result->selected_option == 'Strongly disagree')
                                                @php
                                                    $isSelected = true;
                                                @endphp
                                                single-option-selected
                                            @endif
                                        @endforeach
                                        @if(!$isSelected)
                                            single-option-blur
                                        @endif 
                                        " 
                                     onclick="toggleEmoji(this, {{ $index }})">
                                        <p class="emoji">&#128544;</p>
                                        <p class="option-name">Strongly disagree</p>
                                    </div>
                                    <div class="single-option
                                        @php
                                            $isSelected = false;
                                        @endphp
                                        @foreach($question->reviewResult as $result)
                                            @if($result->question_id == $index && $result->selected_option == 'Disagree')
                                                @php
                                                    $isSelected = true;
                                                @endphp
                                                single-option-selected
                                            @endif
                                        @endforeach
                                        @if(!$isSelected)
                                            single-option-blur
                                        @endif 
                                    " 
                                     data-index="{{ $index }}" onclick="toggleEmoji(this, {{ $index }})">
                                        <p class="emoji">&#128578;</p>
                                        <p class="option-name">Disagree</p>
                                    </div>
                                    <div class="single-option 
                                        @php
                                            $isSelected = false;
                                        @endphp
                                        @foreach($question->reviewResult as $result)
                                            @if($result->question_id == $index && $result->selected_option == 'Agree')
                                                @php
                                                    $isSelected = true;
                                                @endphp
                                                single-option-selected
                                            @endif
                                        @endforeach
                                        @if(!$isSelected)
                                            single-option-blur
                                        @endif 
                                    " data-index="{{ $index }}" onclick="toggleEmoji(this, {{ $index }})">
                                        <p class="emoji">&#128522;</p>
                                        <p class="option-name">Agree</p>
                                    </div>
                                    <div class="single-option 
                                        @php
                                            $isSelected = false;
                                        @endphp
                                        @foreach($question->reviewResult as $result)
                                            @if($result->question_id == $index && $result->selected_option == 'Strongly agree')
                                                @php
                                                    $isSelected = true;
                                                @endphp
                                                single-option-selected
                                            @endif
                                        @endforeach
                                        @if(!$isSelected)
                                            single-option-blur
                                        @endif 

                                    " data-index="{{ $index }}" onclick="toggleEmoji(this, {{ $index }})">
                                        <p class="emoji">&#128516;</p>
                                        <p class="option-name">Strongly agree</p>
                                    </div>
                                </div>   
                            </div> 
                        @endforeach    
                        <hr>
                        <button onclick="submitReviewResult()" class="btn btn-warning px-5">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    let selectedOptions = [];

    // Function to toggle emoji selection
    function toggleEmoji(element, index) {
        // Toggle class 'selected'
        let option = element.querySelector('.option-name').innerHTML;

        let selectedIndex = selectedOptions.findIndex(item => item.index === index);
        console.log("selectedIndex",selectedIndex)

        if (selectedIndex !== -1) {
            // If the index already exists, update the selected option
            if (selectedOptions[selectedIndex].option === option) {
                // If the option is already selected, remove it
                selectedOptions.splice(selectedIndex, 1);
                element.classList.remove('single-option-selected');
            } else {
                // Update the selected option
                selectedOptions[selectedIndex].option = option;
            }
        } else {
            // If the index doesn't exist, add a new object to selectedOptions array
            selectedOptions.push({ index: index, option: option });
        }

        // Get all options within the same parent container
        let parentContainer = element.closest('.reviews');
        let allOptions = parentContainer.querySelectorAll('.single-option');

        // // Update class for all options
        allOptions.forEach(optionElement => {
            let optionText = optionElement.querySelector('.option-name').innerHTML;
            let isSelected = selectedOptions.some(item => item.index === index && item.option === optionText);
            if (isSelected) {
                // alert("Hello")
                optionElement.classList.add('single-option-selected');
                optionElement.classList.remove('single-option-blur');
            } else {
                // alert("yellow")
                optionElement.classList.remove('single-option-selected');
                optionElement.classList.add('single-option-blur');
            }
        });
        console.log(selectedOptions); // Output selected options array (for testing)
    }

    function getCSRFToken() {
        return $('meta[name="csrf-token"]').attr('content');
    }

    function submitReviewResult() {
        let review_id = "{{ $review_id }}";
        let reviewee_id = "{{ $reviewee_id }}";
        // Perform AJAX request
        $.ajax({
            type: "POST",
            url: "{{ route('submit_review_result') }}",
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            },
            data: { 
                review_id: review_id,
                reviewee_id: reviewee_id,
                selectedOptions: selectedOptions 
            },
            success: function(response) {
                // Handle success response
                show_toastr('success', 'Review Successfully submitted!');
            },
            error: function(xhr, status, error) {
                // Handle error response
                show_toastr('error', 'Something went wrong!');
            }
        });
    }

    window.addEventListener('DOMContentLoaded', callback())

    function callback()
    {
        let review_results={!! $review_results !!};
        selectedOptions = []
        review_results.forEach(item => {
            selectedOptions.push({
                index: item.question_id,
                option: item.selected_option
            });
        });
    }

    
</script>