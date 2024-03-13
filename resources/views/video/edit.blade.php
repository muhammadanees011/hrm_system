{{ Form::model($video, ['route' => ['video.update', $video->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('title', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Title')]) }}
                </div>
                @error('title')
                    <span class="invalid-title" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('source_type', __('Source'), ['class' => 'form-label']) }}
                {{ Form::select('source_type', ['' => 'Select Source', 'link' => 'Via Link', 'file' => 'Via File'], $video->source_type, ['class' => 'form-control select', 'id' => 'source_type']) }}
                
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" id="sourceFileSection" @if($video->source_type == 'file') style="display: block;" @endif>
            <div class="form-group">
                {{ Form::label('video_file', __('Source File'), ['class' => 'col-form-label']) }}
                <div class="choose-files">
                    <label for="video_file">
                        <div class="bg-primary receipt"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                        {{ Form::file('video_file', ['class' => 'form-control file', 'id' => 'video_file', 'onchange' => 'playSelectedVideo(this)']) }}
                        
                            <video id="video_player" controls autoplay width="400" src="{{ asset('videos/'.$video->video_file) }}" style="display: @if(empty($video->video_file)) none; @endif"></video>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" id="sourceLink" @if($video->source_type == 'link') style="display: block;" @endif>
            <div class="form-group">
                {{ Form::label('video_link', __('Link'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('video_link', $video->video_link, ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
$(document).ready(function() {
    // Function to toggle visibility based on source type
    function toggleVisibility(selectedOption) {
        if (selectedOption === 'file') {
            $('#sourceFileSection').show();
            $('#sourceLink').hide();
        } else if (selectedOption === 'link') {
            $('#sourceFileSection').hide();
            $('#sourceLink').show();
        } else {
            $('#sourceFileSection').hide();
            $('#sourceLink').hide();
        }
    }

    // Call toggleVisibility on page load
    var selectedOption = $('#source_type').val();
    toggleVisibility(selectedOption);

    // Call toggleVisibility on dropdown change
    $(document).on('change', '#source_type', function() {
        var selectedOption = $(this).val();
        toggleVisibility(selectedOption);
    });
});

</script>
