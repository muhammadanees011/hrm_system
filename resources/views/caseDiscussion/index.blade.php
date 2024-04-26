@extends('layouts.admin')

@section('page-title')
{{ __("Case Discussion") }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
<li class="breadcrumb-item">{{ __("Case Discussion") }}</li>
@endsection

<style>
    .message-container {
        display: flex;
        flex-direction: column;
        max-height: 700px;
        overflow: auto;
        height: 300px;
    }

    .message {
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        max-width: 400px;
    }

    .sender {
        background-color: #007bff;
        color: #fff;
        margin-left: auto;
        /* Align sender's messages to the right */
    }
    .sender a{
        color: #fff !important;
    }

    .receiver {
        background-color: #f2f2f2;
        color: #333;
        margin-right: auto;
        /* Align receiver's messages to the left */
    }
    .receiver a{
        color: #333 !important;
    }

    .input-container {
        position: relative;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    textarea {
        width: calc(100% - 40px);
        height: 100px;
        /* Adjust height as needed */
        resize: none;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
    }

    .attachment-icon {
        /* position: absolute; */
        top: 50%;
        right: 10px;
        /* transform: translateY(-50%); */
        cursor: pointer;
        font-size: 30px;
    }

    .file-pill-container {
        margin-top: 10px;
    }

    .file-pill {
        display: inline-block;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 20px;
        padding: 5px 10px;
        margin-right: 10px;
    }

    .remove-icon {
        margin-left: 5px;
        cursor: pointer;
    }
</style>
<script>
    function handleFileChange() {
        const fileInput = document.getElementById('fileInput');
        const files = fileInput.files;
        const messageInputWrapper = document.getElementById('messageInput');
        const attachmentIcon = document.querySelector('.attachment-icon');

        if (files.length > 0) {
            messageInputWrapper.style.display = 'none';
            attachmentIcon.style.display = 'none';
        } else {
            messageInputWrapper.style.display = 'block';
            attachmentIcon.style.display = 'block';
        }

        const filePillContainer = document.getElementById('filePillContainer');
        filePillContainer.innerHTML = '';

        if (files.length > 0) {
            Array.from(files).forEach(file => {
                const filePill = document.createElement('div');
                filePill.classList.add('file-pill');
                filePill.textContent = file.name;

                const removeIcon = document.createElement('span');
                removeIcon.classList.add('remove-icon');
                removeIcon.innerHTML = '&#10006;';
                removeIcon.onclick = function() {
                    filePill.remove();
                    fileInput.value = ''; // Clear file input
                    messageInputWrapper.style.display = 'block'; // Show message input
                    attachmentIcon.style.display = 'block'; // Show attachment icon
                };

                filePill.appendChild(removeIcon);
                filePillContainer.appendChild(filePill);
            });
        }
    }


    function sendMessage() {
        const message = document.getElementById('messageInput').value;
        const fileInput = document.getElementById('fileInput');
        const files = fileInput.files;

        console.log("Message:", message);
        if (files.length > 0) {
            console.log("Files:", files);
            // Handle file upload
        }

        // Reset input fields
        document.getElementById('messageInput').value = '';
        document.getElementById('fileInput').value = '';
        document.getElementById('filePillContainer').innerHTML = '';
        document.getElementById('messageInputWrapper').style.display = 'block'; // Show message input
    }
</script>
@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body table-border-style">
            <div class="border-bottom border-primary border-2 pb-2 col-10 m-auto">
                <h2>{{$case->title}}</h2>
                <span>{{$case->category->title}}</span>
            </div>

            <div class="row">
                <div class="col-md-10 m-auto mt-5">
                    <div class="message-container">
                        @if(count($case->discussions) > 0)
                        @foreach($case->discussions as $message)
                        <div class="message {{$message->sender == auth()->user()->id ? 'sender' : 'receiver'}}">
                            @if($message->type == 'file')
                            <a href="/storage/uploads/caseDiscussion/{{$message->file}}" target="_blank" rel="message file"><i class="ti ti-download"></i> {{$message->file}}</a>
                            @else
                            <p>{{$message->text}}</p>
                            @endif
                        </div>
                        @endforeach
                        @else
                        <div class="message">
                            <p>No discussion found!</p>
                        </div>
                        @endif
                    </div>

                    {{ Form::open(['url' => 'case-discussion', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="case_code" value="{{ request('id') }}">
                    <input type="hidden" name="sender" value="{{ auth()->user()->id == $case->representative ? $case->representative : $case->created_by }}">
                    <input type="hidden" name="receiver" value="{{ auth()->user()->id == $case->representative ? $case->created_by : $case->representative }}">

                    <div class="input-container border-top border-primary border-2 pt-2">
                        <!-- <div id="messageInputWrapper"> -->
                        <textarea id="messageInput" placeholder="Type your message..." name="text"></textarea>
                        <!-- </div>  -->
                        <label for="fileInput" class="attachment-icon" title="Upload Attachment"><i class="ti ti-upload"></i></label>
                        <input type="file" id="fileInput" class="file-input" onchange="handleFileChange()" name="file" style="display: none;">
                        <div id="filePillContainer" class="file-pill-container"></div>
                    </div>
                    <input type="submit" class="btn btn-primary mt-4" value="Submit" />
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection