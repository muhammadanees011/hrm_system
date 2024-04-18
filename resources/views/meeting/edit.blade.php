@php
    $setting = App\Models\Utility::settings();
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp
{{ Form::model($meeting, ['route' => ['meeting.update', $meeting->id], 'method' => 'PUT']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['meeting']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('title', __('Meeting Name'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('title', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => __('Enter Meeting Title')]) }}
                </div>
            </div>
        </div>

        <div class="form-group col-lg-6">
            {{ Form::label('organizer_id', __('Organizer'), ['class' => 'col-form-label']) }}
            {{ Form::select('organizer_id', $organizers, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>

        <div class="form-group col-lg-6">
            {{ Form::label('invitee_id', __('Invitee'), ['class' => 'col-form-label']) }}
            {{ Form::select('invitee_id', $invitees, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>

        <div class="form-group col-lg-12">
            {{ Form::label('meeting_template_id', __('Meeting Templates'), ['class' => 'col-form-label']) }}
            {{ Form::select('meeting_template_id', $templates, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('date', __('Meeting Date'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('date', null, ['class' => 'form-control d_week current_date', 'required' => 'required', 'id' => 'currentDate']) }}
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('start_time', __('Start Time'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                {{ Form::time('start_time', null, ['class' => 'form-control', 'required' => 'required', 'id' => 'startTime']) }}
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('end_time', __('End Time'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::time('end_time', null, ['class' => 'form-control', 'required' => 'required', 'id' => 'endTime']) }}
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('note', __('Meeting Note'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::textarea('note', null, ['class' => 'form-control', 'rows' => '3']) }}
                </div>
            </div>
        </div>
        @if(isset($setting['is_enabled']) && $setting['is_enabled'] =='on')
        <div class="form-group col-md-6">
            {{ Form::label('synchronize_type', __('Synchroniz in Google Calendar ?'), ['class' => 'form-label']) }}
            <div class=" form-switch">
                <input type="checkbox" class="form-check-input mt-2" name="synchronize_type" id="switch-shadow"
                    value="google_calender">
                <label class="form-check-label" for="switch-shadow"></label>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-warning">
</div>
{{ Form::close() }}

<script>
    // Function which returns a minimum of two digits in case the value is less than 10
const getTwoDigits = (value) => value < 10 ? `0${value}` : value;

const formatDate = (date) => {
  const day = getTwoDigits(date.getDate());
  const month = getTwoDigits(date.getMonth() + 1); // add 1 since getMonth returns 0-11 for the months
  const year = date.getFullYear();

  return `${year}-${month}-${day}`;
}

const formatTime = (date) => {
  const hours = getTwoDigits(date.getHours());
  const mins = getTwoDigits(date.getMinutes());

  return `${hours}:${mins}`;
}

const date = new Date();
document.getElementById('currentDate').value = formatDate(date);
document.getElementById('currentTime').value = formatTime(date);
</script>