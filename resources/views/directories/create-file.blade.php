{{ Form::open(['url' => 'document_dir_store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
             <input type="hidden" name="dir_id" value="{{ $dir_id }}">
            <input type="file" class="form-control" name="file"
            accept="pdf/*" >
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-warning">
</div>
{{ Form::close() }}
