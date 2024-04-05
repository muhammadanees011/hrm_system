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
if (isset($setting['color_flag']) && $setting['color_flag'] == 'true') {
$themeColor = 'custom-color';
} else {
$themeColor = $color;
}
@endphp

<!DOCTYPE html>

<html lang="en">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        {{ !empty($companySettings['title_text']) ? $companySettings['title_text']->value : config('app.name', 'HRMGO') }}
        - {{ __('Onboarding') }}
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

    <style>
        :root {
            --color-customColor: <?= $color ?>;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-white">
    {{ Form::open(['route' => ['onboarding.personalized.response.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <input type="hidden" name="template_id" value="{{$template->id}}">
    <input type="hidden" name="job_application_id" value="{{$jobApplicationId}}">
    <div class="onboarding-wrapper">
        <div class="onboarding-content">
            <section class="mt-4">
                <div class="container">
                    <div class="text-center mw-100">
                        <h1 class="mb-3 display-2">
                            {{$template->header_title}}
                        </h1>
                    </div>
                </div>
                <div class="d-flex align-items-center text-center w-100 ps-4 pe-4 my-5">
                    <div class="col-8">
                        @if($template->header_option == 'video_url')
                        <video src="https://www.lampe2020.de/videos/00f/video.mp4" width="1000" controls></video>
                        @elseif($template->header_option == 'video_upload')
                        <video controls class="w-100">
                            <source src="/storage/uploads/employeeOnboardingTemplate/header/{{ $template->video_file_path }}" type="video/mp4">
                            <source src="/storage/uploads/employeeOnboardingTemplate/header/{{ $template->video_file_path }}" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                        @else
                        <img src="/storage/uploads/employeeOnboardingTemplate/header/{{$template->image_file_path}}" alt="" class="w-100">
                        @endif
                    </div>
                    <div class="col-4 fs-5 text-left lh-base">
                        {!! $template->header_description !!}
                    </div>
                </div>
            </section>
            <section class="apply-job-section">
                <div class="container">
                    <div class="apply-job-wrapper bg-light mt-5">
                        <div class="section-title text-center">
                            <h1 class="mb-3 display-6">
                                Let's get to know you better...
                            </h1>
                            <p class="fs-5">Fill in some details about yourself here.</p>
                        </div>

                        <div class="d-flex flex-wrap">
                            @if(count($template->questions))
                            @foreach($template->questions as $question)
                            @if($question->type == 'text')
                            <div class="form-group col-md-4">
                                {!! Form::label($question->name, null, ['class' => 'col-form-label']) !!}
                                {!! Form::text('question[' . $question->id . ']',null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter answer',
                                ]) !!}
                            </div>
                            @elseif($question->type == 'textarea')
                            <div class="form-group col-md-4">
                                {!! Form::label($question->name, null, ['class' => 'col-form-label']) !!}
                                {!! Form::textarea('question[' . $question->id . ']',null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter answer',
                                ]) !!}
                            </div>
                            @elseif($question->type == 'radio')
                            <div class="form-group col-md-2">
                                {!! Form::label($question->name, null, ['class' => 'col-form-label']) !!}
                                <div class="col-md-6 d-flex align-items-center justify-content-between">
                                    @foreach(json_decode($question->options) as $option)
                                    <div class="form-check mx-1">
                                        <input type="radio" id="{{$option}}" name="question[{{ $question->id }}]" value="{{$option}}" class="form-check-input">
                                        <label for="{{$option}}" class="form-check-label">{{$option}}</label>
                                    </div>
                                    @endforeach
                                    <!-- <input type="radio" id="option_1" name="question_1" value="1" class="form-check-input">
                                        <label for="option_1" class="form-check-label">sadddd</label>
                                        <input type="radio" id="option_2" name="question_1" value="2" class="form-check-input">
                                        <label for="option_2" class="form-check-label">22222</label> -->
                                </div>
                            </div>
                            @elseif($question->type == 'file')
                            <div class="form-group col-md-4">
                                {!! Form::label($question->name, null, ['class' => 'col-form-label']) !!}
                                <br>
                                {!! Form::file('question[' . $question->id . ']',null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter answer',
                                ]) !!}
                            </div>
                            @endif
                            @endforeach
                            @endif
                            <!-- <div class="form-group col-md-4">
                                {!! Form::label('title', null, ['class' => 'col-form-label']) !!}
                                {!! Form::text('title',null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter answer',
                                ]) !!}
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::label('title', null, ['class' => 'col-form-label']) !!}
                                {!! Form::textarea('title',null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter answer',
                                ]) !!}
                            </div>
                            <div class="form-group col-md-2">
                                {!! Form::label('title', null, ['class' => 'col-form-label']) !!}
                                <div class="col-md-6 d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="radio" id="option_1" name="question_1" value="1" class="form-check-input">
                                        <label for="option_1" class="form-check-label">sadddd</label>
                                        <input type="radio" id="option_2" name="question_1" value="2" class="form-check-input">
                                        <label for="option_2" class="form-check-label">22222</label>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </section>
            @if($template->attachments_status)
            <section class="apply-job-section">
                <div class="container">
                    <div class="apply-job-wrapper bg-light">
                        <div class="section-title text-center">
                            <h1 class="mb-3 display-6">
                                You're almost done!
                            </h1>
                            <p class="fs-5">Find all your necessary documents for onboarding on this page.</p>
                            <p class="fs-5">You maybe asked to read or approve some of them, or even upload your own.</p>
                        </div>

                        <div class="d-flex flex-column">
                            @foreach($template->files as $file)
                            <div class="d-flex p-4 bg-white border rounded w-50 justify-content-between align-items-center mx-auto my-2">
                                <span class="h6">{{$file->file_path}}</span>
                                <input type="hidden" name="file_approvals[{{$file->id}}]" id="file_approval_{{$file->id}}" value="{{$file->approved ? '1' : '0'}}">
                                @if($file->file_type == 'read_and_approve')
                                <button class="btn btn-primary text-white approve-btn" data-file-id="{{$file->id}}" data-file-path="/storage/uploads/employeeOnboardingTemplate/{{$file->file_path}}" {{$file->approved ? ' disabled' : ''}}>Approve & Read</button>
                                @else
                                <a href="/storage/uploads/employeeOnboardingTemplate/{{$file->file_path}}" target="_blank" class="btn btn-primary text-white">Read</a>
                                @endif
                            </div>
                            @endforeach

                            <!-- <div class="d-flex p-4 bg-white border rounded w-50 justify-content-between align-items-center mx-auto my-2">
                                <span class="h6">fileName.pdf</span>
                                <button class="btn btn-primary text-white">Read</button>
                            </div> -->
                            <!-- <div class="d-flex p-4 bg-white border rounded w-50 justify-content-between align-items-center mx-auto my-2">
                                <span class="h6">fileName.pdf</span>
                                <input type="file" name="" id="" class="w-25">
                            </div> -->
                        </div>
                    </div>
                </div>
            </section>
            @endif
            @if($jobApplicationId !== null)
            <div class="text-center mb-6">
                <input type="submit" class="btn btn-success">
            </div>
            @else
            <div class="text-center mb-6">
                <a href="{{route('personlized-onboarding.index')}}" class="btn btn-info">Go Back</a>
            </div>
            @endif
        </div>
    </div>
    {{ Form::close() }}

    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('js/site.core.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
    <script src="{{ asset('js/demo.js') }} "></script>
    <script>
        $(document).ready(function() {
            $('.approve-btn').click(function() {
                var fileId = $(this).data('file-id');
                var filePath = $(this).data('file-path');
                $('#file_approval_' + fileId).val('1'); // Update approval status
                $(this).prop('disabled', true); // Disable the button after click
                window.open(filePath, '_blank'); // Open the file in a new tab
            });
        });
    </script>

    @stack('custom-scripts')
    @if($enable_cookie['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
    @endif
</body>

</html>