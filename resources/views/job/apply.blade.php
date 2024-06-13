@php
$logo = \App\Models\Utility::get_file('uploads/logo/');
$setting = App\Models\Utility::colorset();
$color = !empty($setting['theme_color']) ? $setting['theme_color'] : 'theme-3';
$SITE_RTL = \App\Models\Utility::getValByName('SITE_RTL');
$company_logo_light = Utility::getValByName('company_logo_light');
$company_favicon = Utility::getValByName('company_favicon');

$getseo = App\Models\Utility::getSeoSetting();
$metatitle = isset($getseo['meta_title']) ? $getseo['meta_title'] : '';
$metadesc = isset($getseo['meta_description']) ? $getseo['meta_description'] : '';
$meta_image = \App\Models\Utility::get_file('uploads/meta/');
$meta_logo = isset($getseo['meta_image']) ? $getseo['meta_image'] : '';
$enable_cookie = \App\Models\Utility::getCookieSetting('enable_cookie');

@endphp

<!DOCTYPE html>

<html lang="en">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        {{ !empty($companySettings['title_text']) ? $companySettings['title_text']->value : config('app.name', 'HRMGO') }}
        - {{ __('Career') }}
    </title>

    <!-- SEO META -->
    <meta name="title" content="{{ $metatitle }}">
    <meta name="description" content="{{ $metadesc }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ $metatitle }}">
    <meta property="og:description" content="{{ $metadesc }}">
    <meta property="og:image" content="{{ isset($meta_logo) && !empty(asset('storage/uploads/meta/' . $meta_logo)) ? asset('storage/uploads/meta/' . $meta_logo) : 'hrmgo.png' }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ $metatitle }}">
    <meta property="twitter:description" content="{{ $metadesc }}">
    <meta property="twitter:image" content="{{ isset($meta_logo) && !empty(asset('storage/uploads/meta/' . $meta_logo)) ? asset('storage/uploads/meta/' . $meta_logo) : 'hrmgo.png' }}">


    <link rel="icon" href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon .'?'.time() : 'favicon.png' .'?'.time()) }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}" id="stylesheet">
    @if (isset($setting['dark_mode']) && $setting['dark_mode'] == 'on')
    <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @if (isset($setting['dark_mode']) && $setting['dark_mode'] == 'on')
    <link rel="stylesheet" href="{{ asset('assets/css/custom-dark.css') }}">
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="{{ $color }}">
    <div class="job-wrapper">
        <div class="job-content">
            <nav class="navbar">
                <div class="container">
                    <a class="navbar-brand" href="#">
                        <img src="{{ $logo . '/' . (isset($company_logo_light) && !empty($company_logo_light) ? $company_logo_light .'?'.time() : 'logo-light.png' .'?'.time()) }}" alt="logo" style="width: 90px">

                    </a>
                </div>
            </nav>
            <section class="job-banner">
                <div class="job-banner-bg">
                    <img src="{{ asset('/storage/uploads/job/banner.png') }}" alt="">
                </div>
                <div class="container">
                    <div class="job-banner-content text-center text-white">
                        <h1 class="text-white mb-3">
                            {{ __(' We help') }} <br> {{ __('businesses grow') }}
                        </h1>
                        <p>{{ __('Work there. Find the dream job you’ve always wanted..') }}</p>
                        </p>
                    </div>
                </div>
            </section>
            <section class="apply-job-section">
                <div class="container">
                    <div class="apply-job-wrapper bg-light">
                        <div class="section-title text-center">
                            <h2 class="h1 mb-3"> {{ $job->title }}</h2>
                            <div class="d-flex flex-wrap justify-content-center gap-1 mb-4">
                                @foreach (explode(',', $job->skill) as $skill)
                                <span class="badge rounded p-2 bg-primary">{{ $skill }}</span>
                                @endforeach
                            </div>
                            @if (!empty($job->branches) ? $job->branches->name : '')
                            <p> <i class="ti ti-map-pin ms-1"></i>
                                {{ !empty($job->branches) ? $job->branches->name : '' }}
                            </p>
                            @endif

                        </div>
                        <div class="apply-job-form">
                            <h2 class="mb-4">{{ __('Apply for this job') }}</h2>
                            {{ Form::open(['route' => ['job.apply.data', $job->code], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                                        {{ Form::text('name', null, ['class' => 'form-control name', 'required' => 'required']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                                        {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('phone', __('Phone'), ['class' => 'form-label']) }}
                                        {{ Form::text('phone', null, ['class' => 'form-control', 'required' => 'required']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if (!empty($job->applicant) && in_array('dob', explode(',', $job->applicant)))
                                    <div class="form-group">
                                        {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}
                                        {!! Form::date('dob', old('dob'), ['class' => 'form-control datepicker w-100', 'required' => 'required']) !!}
                                    </div>
                                    @endif
                                </div>
                                @if (!empty($job->applicant) && in_array('gender', explode(',', $job->applicant)))
                                <div class="form-group col-md-6 ">
                                    {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}
                                    <div class="d-flex radio-check">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="g_male" value="Male" name="gender" class="custom-control-input">
                                            <label class="custom-control-label" for="g_male">{{ __('Male') }}</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="g_female" value="Female" name="gender" class="custom-control-input">
                                            <label class="custom-control-label" for="g_female">{{ __('Female') }}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if (!empty($job->applicant) && in_array('country', explode(',', $job->applicant)))
                                <div class="form-group col-md-6 ">
                                    {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                                    {{ Form::text('country', null, ['class' => 'form-control', 'required' => 'required']) }}
                                </div>
                                <div class="form-group col-md-6 country">
                                    {{ Form::label('state', __('State'), ['class' => 'form-label']) }}
                                    {{ Form::text('state', null, ['class' => 'form-control', 'required' => 'required']) }}
                                </div>
                                <div class="form-group col-md-6 country">
                                    {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
                                    {{ Form::text('city', null, ['class' => 'form-control', 'required' => 'required']) }}
                                </div>
                                @endif

                                @if (!empty($job->visibility) && in_array('profile', explode(',', $job->visibility)))
                                <div class="form-group col-md-6 ">
                                    {{ Form::label('profile', __('Profile'), ['class' => 'col-form-label']) }}
                                    <input type="file" class="form-control" name="profile" id="profile" data-filename="profile_create" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                    <img id="blah" src="" class="mt-3" width="25%" />
                                    <p class="profile_create"></p>
                                </div>
                                @endif

                                @if (!empty($job->visibility) && in_array('resume', explode(',', $job->visibility)))
                                <div class="form-group col-md-6 ">
                                    {{ Form::label('resume', __('CV / Resume'), ['class' => 'col-form-label']) }}
                                    <input type="file" class="form-control" name="resume" id="resume" data-filename="resume_create" onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])" required>
                                    <img id="blah1" class="mt-3" src="" width="25%" />
                                    <p class="resume_create"></p>

                                </div>
                                @endif

                                @if (!empty($job->visibility) && in_array('letter', explode(',', $job->visibility)))
                                <div class="form-group col-md-6">
                                    {{ Form::label('cover_letter', __('Cover Letter'), ['class' => 'form-label']) }}
                                    {{ Form::textarea('cover_letter', null, ['class' => 'form-control', 'rows' => '3', 'id'=> 'coverLetter']) }}
                                    <span id="wordCount"></span>
                                </div>
                                @endif

                                @if (!empty($questionTemplate))
                                <div id="question-data" data-questions="{{ json_encode($questionTemplate->questions) }}" style="display: none;"></div>
                                <div class="col-md-6">
                                    <h5>Questions:</h5>
                                    <div class="form-group" id="questions-container">
                                        <!-- Questions will be dynamically appended here -->
                                    </div>
                                    <button id="next-question" class="btn btn-info">Next Question</button>
                                </div>
                                @endif


                                @foreach ($questions as $question)
                                <div class="form-group col-md-6  question question_{{ $question->id }}">
                                    {{ Form::label($question->question, $question->question, ['class' => 'form-label']) }}
                                    <input type="text" class="form-control" name="question[{{ $question->question }}]" {{ $question->is_required == 'yes' ? 'required' : '' }}>
                                </div>
                                @endforeach
                                <div class="col-12">
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary" @if (!empty($questionTemplate)) style="display: none;" @endif id="application-submit-btn">{{ __('Submit your application') }}</button>
                                    </div>
                                </div>

                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
        <div id="liveToast" class="toast text-white  fade" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"> </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('js/site.core.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
    <script src="{{ asset('js/demo.js') }} "></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionsContainer = document.getElementById('questions-container');
            const nextButton = document.getElementById('next-question');
            const questionData = JSON.parse(document.getElementById('question-data').dataset.questions);

            let currentQuestionIndex = 0; // Initialize currentQuestionIndex
            let currentQuestionId = null; // Initialize currentQuestionId

            // Function to append a question to the container
            // Function to append a question to the container
            function appendQuestion(index, isBranching, selectedOptionId) {
                const question = questionData[index];
                const questionsContainer = document.getElementById('questions-container');

                const questionDiv = document.createElement('div');
                questionDiv.classList.add('question');
                questionDiv.dataset.type = question.type;
                questionDiv.dataset.questionId = question.id;

                let html = `<h4>${question.name}</h4>`;

                if (isBranching) {
                    questionDiv.classList.add('branching-question-' + selectedOptionId); // Add a separate class for branching questions
                }

                // Append different types of input fields based on the question type
                switch (question.type) {
                    case 'text':
                        html += `<input type="text" name="question_${question.id}" class="form-control" required>`;
                        break;
                    case 'textarea':
                        html += `
                <textarea name="question_${question.id}" class="form-control" required data-word-count="${question.word_count}"></textarea>
                <div class="word-count" id="wordCount_${question.id}">Word Count: 0/${question.word_count}</div>
            `;
                        break;
                    case 'radio':
                        question.options.forEach((option, idx) => {
                            html += `
                    <div class="form-check">
                        <input type="radio" id="option_${option.id}" name="question_${question.id}" value="${option.id}" class="form-check-input">
                        <label for="option_${option.id}" class="form-check-label">${option.option_text}</label>
                    </div>
                `;
                        });
                        break;
                        // Add cases for other question types if needed
                }

                questionDiv.innerHTML = html;
                questionsContainer.appendChild(questionDiv);

                currentQuestionId = question.id;

                // If the question is a textarea, add word count functionality
                if (question.type === 'textarea') {
                    const textarea = questionDiv.querySelector(`textarea[name="question_${question.id}"]`);
                    const wordCountDisplay = questionDiv.querySelector(`#wordCount_${question.id}`);
                    const maxWords = parseInt(textarea.dataset.wordCount, 10);

                    textarea.addEventListener('input', function() {
                        const words = this.value.trim().split(/\s+/);
                        const wordCount = words.length;
                        wordCountDisplay.textContent = `Word Count: ${wordCount}/${maxWords}`;

                        if (wordCount > maxWords) {
                            const truncatedText = words.slice(0, maxWords).join(' ');
                            this.value = truncatedText;
                        }
                    });
                }

                // Add event listener to radio buttons
                const radioButtons = questionDiv.querySelectorAll('input[type="radio"]');
                radioButtons.forEach(radioButton => {
                    radioButton.addEventListener('change', function() {
                        const selectedOptionId = this.value;
                        const selectedOption = question.options.find(option => option.id == selectedOptionId);
                        const nextQuestionId = selectedOption.branching_logic;

                        // Remove previously appended questions with the 'branching-question' class
                        const appendedQuestions = questionsContainer.querySelectorAll('.branching-question-' + selectedOption.question_id);
                        appendedQuestions.forEach(appendedQuestion => appendedQuestion.remove());

                        // Find the next question based on branching logic
                        const nextQuestionIndex = questionData.findIndex(question => question.id === nextQuestionId);
                        if (nextQuestionIndex !== -1) {
                            appendQuestion(nextQuestionIndex, true, selectedOption.question_id);
                        } else {
                            console.error('Next question not found!');
                        }
                    });
                });
            }

            // function appendQuestion(index, isBranching, selectedOptionId) {
            //     const question = questionData[index];
            //     const questionsContainer = document.getElementById('questions-container');

            //     const questionDiv = document.createElement('div');
            //     questionDiv.classList.add('question');
            //     questionDiv.dataset.type = question.type;
            //     questionDiv.dataset.questionId = question.id;

            //     let html = `<h4>${question.name}</h4>`;

            //     if (isBranching) {
            //         questionDiv.classList.add('branching-question-' + selectedOptionId); // Add a separate class for branching questions
            //     }


            //     // Append different types of input fields based on the question type
            //     switch (question.type) {
            //         case 'text':
            //             html += `<input type="text" name="question_${question.id}" class="form-control" required>`;
            //             break;
            //         case 'textarea':
            //             html += `<textarea name="question_${question.id}" class="form-control" required></textarea>`;
            //             break;
            //         case 'radio':
            //             question.options.forEach((option, idx) => {
            //                 html += `
            //         <div class="form-check">
            //             <input type="radio" id="option_${option.id}" name="question_${question.id}" value="${option.id}" class="form-check-input">
            //             <label for="option_${option.id}" class="form-check-label">${option.option_text}</label>
            //         </div>
            //     `;
            //             });
            //             break;
            //             // Add cases for other question types if needed
            //     }

            //     questionDiv.innerHTML = html;
            //     questionsContainer.appendChild(questionDiv);

            //     currentQuestionId = question.id;

            //     // Add event listener to radio buttons
            //     const radioButtons = questionDiv.querySelectorAll('input[type="radio"]');
            //     radioButtons.forEach(radioButton => {
            //         radioButton.addEventListener('change', function() {
            //             const selectedOptionId = this.value;
            //             const selectedOption = question.options.find(option => option.id == selectedOptionId);
            //             const nextQuestionId = selectedOption.branching_logic;

            //             // Remove previously appended questions with the 'branching-question' class
            //             const appendedQuestions = questionsContainer.querySelectorAll('.branching-question-' + selectedOption.question_id);
            //             appendedQuestions.forEach(appendedQuestion => appendedQuestion.remove());

            //             // Find the next question based on branching logic
            //             const nextQuestionIndex = questionData.findIndex(question => question.id === nextQuestionId);
            //             if (nextQuestionIndex !== -1) {
            //                 appendQuestion(nextQuestionIndex, true, selectedOption.question_id);
            //             } else {
            //                 console.error('Next question not found!');
            //             }
            //         });
            //     });

            // }


            // Initial check if there are questions available
            if (questionData.length > 0) {
                appendQuestion(currentQuestionIndex, false, 0);
            }

            // Event listener for the "Next" button
            nextButton.addEventListener('click', function(event) {
                event.preventDefault();
                // Check if the user has answered the current question
                const currentQuestionInput = questionsContainer.querySelector(`input[type="text"][name="question_${currentQuestionId}"]`);
                const currentQuestionTextarea = questionsContainer.querySelector(`textarea[name="question_${currentQuestionId}"]`);
                const currentQuestionRadio = questionsContainer.querySelector(`input[type="radio"][name="question_${currentQuestionId}"]:checked`);

                if (currentQuestionInput && currentQuestionInput.value.trim() !== '') {
                    // If the current question is a text input and it has a value
                    appendNextQuestion();
                } else if (currentQuestionTextarea && currentQuestionTextarea.value.trim() !== '') {
                    // If the current question is a textarea and it has a value
                    appendNextQuestion();
                } else if (currentQuestionRadio !== null) {
                    // If the current question is a radio button and an option is selected
                    appendNextQuestion();
                } else {
                    // If the user hasn't answered the current question
                    alert('Please answer the current question before proceeding.');
                }
            });

            function appendNextQuestion() {
                currentQuestionIndex++;

                while (currentQuestionIndex < questionData.length) {
                    let nextQuestion = questionData[currentQuestionIndex];
                    let foundMatch = false;

                    // Check if any option's branching_logic matches the next question's id
                    for (const question of questionData) {
                        for (const option of question.options) {
                            if (option.branching_logic === nextQuestion.id) {
                                foundMatch = true;
                                break;
                            }
                        }
                        if (foundMatch) break; // Exit loop if a match is found
                    }

                    if (foundMatch) {
                        // Skip appending this question and move to the next one
                        currentQuestionIndex++;
                    } else {
                        // Append the next question and exit the loop
                        appendQuestion(currentQuestionIndex, false, 0);
                        return;
                    }
                }
                // Handle the end of questions (e.g., submit the form)
                // alert('All questions completed!');
                document.getElementById('application-submit-btn').style.display = 'inline';
                document.getElementById('next-question').style.display = 'none';
            }




            // function appendNextQuestion() {
            //     // Proceed to append the next question
            //     currentQuestionIndex++;
            //     if (currentQuestionIndex < questionData.length) {
            //         appendQuestion(currentQuestionIndex, false, 0);
            //     } else {
            //         // Handle the end of questions (e.g., submit the form)
            //         alert('All questions completed!');
            //         document.getElementById('application-submit-btn').style.display = 'inline';
            //         document.getElementById('next-question').style.display = 'none';
            //     }
            // }
        });
    </script>


    <script>
        $(document).ready(function() {
            // Set the maximum word limit
            var maxWords = @json($wordCounts[0]['limit']);
            // Add input event listener to the textarea
            $('#coverLetter').on('input', function() {
                var words = $(this).val().match(/\S+/g) || [];
                var wordCount = words.length;

                // Display word count
                $('#wordCount').text('Word Count: ' + wordCount + '/' + maxWords);

                // Disable textarea if it exceeds the word limit
                if (wordCount > maxWords) {
                    var truncatedWords = words.slice(0, maxWords);
                    $(this).val(truncatedWords.join(' '));
                    // $('#wordCount').text('Word Count: ' + maxWords);
                }
            });
        });
    </script>

    @if ($message = Session::get('success'))
    <script>
        show_toastr('{{ '
            success ' }}', '{!! $message !!}');
    </script>
    @endif
    @if ($message = Session::get('error'))
    <script>
        show_toastr('{{ '
            error ' }}', '{!! $message !!}');
    </script>
    @endif

    @stack('custom-scripts')
    @if($enable_cookie['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
    @endif

</body>

</html>