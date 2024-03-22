@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($performancecycle, ['route' => ['performancecycle.update', $performancecycle->id], 'method' => 'PUT']) }}
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

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'enter title', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('progress', __('Progress'), ['class' => 'col-form-label']) }}
                {{ Form::number('progress', null, ['class' => 'form-control', 'placeholder' => 'enter progress']) }}
            </div>
        </div>

        <div class="form-group col-6 switch-width">
            {{ Form::label('roles', __('Participants'), ['class' => ' form-label']) }}
            <select name="roles[]" class="select2" id="roles" multiple>
                @foreach ($roles as $roleId => $roleName)
                    <option @if (isset(json_decode($performancecycle->getSelectedParticipants(), true)[$roleId])) selected @endif value="{{ $roleId }}">
                        {{ $roleName }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}
            <select name="status" class="form-control " id="status">
                <option value="running" @if ($performancecycle->status == 'running') selected @endif>{{ __('Running') }}</option>
                <option value="ended" @if ($performancecycle->status == 'ended') selected @endif>{{ __('Ended') }}</option>
                <option value="draft" @if ($performancecycle->status == 'draft') selected @endif>{{ __('Draft') }}</option>
            </select>
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
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });
</script>
