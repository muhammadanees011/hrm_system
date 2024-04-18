@extends('layouts.admin')
@section('page-title')
        {{ __('Manage Goals') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item">{{ __('Goals') }}</li>
@endsection

@php
    $profile = asset(Storage::url('uploads/avatar/'));
@endphp
@section('content')

<style>
    .goal-headings{
        align-self: baseline;
        color:#71797E;
    }
    .goal-data{
        font-weight:600;
    }
    .tasks-bg{
        background-color:#E5E4E2;
        align-items:center;
        min-height:4rem;
        border-radius:0.1rem;
    }
    .tasks-line{
        margin-top:0px;
        margin-bottom:1px;
    }
    .goal-status{
        background-color:#8db600;
        color:white;
        border-radius:10px;
        padding:0px 20px;
    }
    .goal-status-offtrack{
        background-color:#EE204D;
        color:white;
        border-radius:10px;
        padding:0px 20px;
    }
    .hide{
        display: none; 
    }
    .show{
        display:block;
    }
</style>



<div class="col-xl-12">
            <div class="card  text-center">
                <div class="card-header border-0 pb-0">
                    <div class="row d-flex justify-content-between align-items-center">
                            <div class="col-md-10 d-flex">
                                <h5 class="me-2">{{$employeereview->title}}</h5>
                                <div class="me-2 goal-status bg-warning  }}">{{$employeereview->status}}</div>
                            </div>
                            <div class="col-md-2 d-flex justify-content-end">
                                @if($employeereview->status != 'Completed')
                                <a href="{{ route('employeereviews.complete',$employeereview->id) }}" class="mx-3 btn btn-md btn-warning  align-items-center"
                                    data-size="md"
                                    data-url="#"
                                    data-ajax-popup="false" data-bs-toggle="tooltip"
                                    title="" data-title="{{ __('Complete') }}"
                                    data-bs-original-title="{{ __('Complete') }}">
                                    <i class="fas fa-check me-2"></i>
                                    Complete
                                </a>
                                @endif
                            </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d-flex flex-column justify-content-start">
                                <h5 class="goal-headings">Reviewrs</h5> 
                                @foreach($employeereview->reviewers as $reviewer)
                                <div class="me-2 mt-1 goal-status ">{{ $reviewer->user ? $reviewer->user->name : '-'}}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{ Form::model($employeereview, ['route' => ['employeereviews.reviewees', $employeereview->id], 'method' => 'PUT']) }}
                    <div class="row mt-4">
                        <div class="form-group col-md-5 d-flex flex-column justify-content-start">
                            <h5 class="goal-headings">Who To Review</h5> 
                                <div class="form-group col-md-12">
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check form-check">
                                            <input class="form-check-input" type="radio" id="all" value="all" name="who_to_review"
                                            @if ($employeereview->who_to_review == 'all') checked @endif>
                                            <label class="form-check-label" for="all">
                                                {{ __('All') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check">
                                            <input class="form-check-input" type="radio" id="employee" value="employee" name="who_to_review"
                                            @if ($employeereview->who_to_review == 'employee') checked @endif>
                                            <label class="form-check-label" for="employee">
                                                {{ __('Employee') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check">
                                            <input class="form-check-input" type="radio" id="manager" value="manager" name="who_to_review"
                                            @if ($employeereview->who_to_review == 'manager') checked @endif>
                                            <label class="form-check-label" for="manager">
                                                {{ __('Manager') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check">
                                            <input class="form-check-input" type="radio" id="hr" value="hr" name="who_to_review"
                                            @if ($employeereview->who_to_review == 'hr') checked @endif>
                                            <label class="form-check-label" for="hr">
                                                {{ __('HR') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check">
                                            <input class="form-check-input" type="radio" id="select" value="select" name="who_to_review"
                                            @if ($employeereview->who_to_review == 'select') checked @endif>
                                            <label class="form-check-label" for="select">
                                                {{ __('Select') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                
                                @if($employeereview->who_to_review != 'select')
                                <div class="form-group col-md-12 select-reviewer hide">
                                    {{ Form::label('reviewees', __('Reviewees'), ['class' => 'form-label goal-headings d-flex justify-content-start']) }}
                                    <div class="form-icon-user">
                                        {{-- {!! Form::select('reviewees', $reviewees, null, ['class' => 'form-control select2 ', 'required' => 'required']) !!} --}}
                                        {{ Form::select('reviewees[]', $reviewees, null, ['class' => 'form-control select2 user-role', 'id' => 'choices-multiple', 'multiple' => '']) }}
                                    </div>
                                    @error('role')
                                        <span class="invalid-role" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                @else
                                <div class="form-group col-12 switch-width select-reviewer hide">
                                    {{ Form::label('reviewees', __('Reviewees'), ['class' => ' form-label goal-headings d-flex justify-content-start']) }}
                                    <select name="reviewees[]" class="select2" id="reviewer" multiple>
                                        @foreach ($reviewees as $id => $name)
                                            <option @if ($employeereview->reviewees->contains('user_id', $id)) selected @endif value="{{ $id }}">
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 d-flex justify-content-start">
                            <input type="submit" value="{{ __('Save') }}" class="btn btn-warning">
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>


        <div class="col-xl-12">
            <div class="card  text-center">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Review Questions</h5>
                        <div>
                            <a href="#" class="btn btn-warning" data-ajax-popup="true"
                                data-title="{{ __('Create Result') }}"
                                data-url="{{ route('reviewquestions.create',$employeereview->id) }}">
                                <span class="ms-1 text-white">{{ __('Create') }}</span>
                                <i class="fas fa-plus-square ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                @foreach($review_questions as $question)
                    <div class="row tasks-bg">
                        <div class="col-md-8 col-sm-12 d-flex justify-content-start">
                                <p class="mt-4">{{$question->question}}</p>  
                        </div>
                        
                        <div class="col-md-4 col-sm-12 d-flex justify-content-end">
                            <div class="dropdown">
                                    <i class="fas fa-bars " id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a href="#" class="dropdown-item" data-ajax-popup="true"
                                            data-title="{{ __('Create Result') }}"
                                            data-url="{{ route('reviewquestions.edit',$question->id) }}">
                                            <span class="ms-1 text-black">{{ __('Edit') }}</span>
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('reviewquestions.delete',['id'=>$question->id,'review_id'=>$employeereview->id]) }}">Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <hr class="tasks-line">
                @endforeach
                    </div>
                </div>
            </div>
        </div>





<!-- Design -->
   
@endsection

@push('scripts')
    {{-- Password  --}}
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });

        $(document).on('change', '.user-role', function(){
            const value = $('.user-role option:selected').text();
            if(value.includes('manager')){
                $('.dep_div').removeClass('d-none');
            }else{
                $('.dep_div').addClass('d-none');
                $('.manager-department').val('').trigger('change');
            }
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
@endpush
