{{ Form::open(['url' => 'video', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
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
                {{ Form::select('source_type', ['' => 'Select Source', 'link' => 'Via Link', 'file' => 'Via File'], isset($_GET['source_type']) ? $_GET['source_type'] : '', ['class' => 'form-control select', 'id' => 'source_type']) }}
            </div>
        </div>
        
<div class="col-lg-12 col-md-12 col-sm-12" id="sourceFileSection" style="display: none;">
    <div class="form-group">
        {{ Form::label('video_file', __('Source File'), ['class' => 'col-form-label']) }}
        <div class="choose-files">
            <label for="video_file">
                <div class="bg-primary receipt"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                {{ Form::file('video_file', ['class' => 'form-control file', 'id' => 'video_file', 'onchange' => 'playSelectedVideo(this)']) }}
                <video id="video_player" controls width="400" style="display: none;"></video>
            </label>
        </div>
    </div>
</div>
        <div class="col-lg-12 col-md-12 col-sm-12" id="sourceLink" style="display: none;">
            <div class="form-group">
                {{ Form::label('video_link', __('Link'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('video_link', null, ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}