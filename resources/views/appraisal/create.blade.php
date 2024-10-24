@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::open(['url' => 'appraisal', 'method' => 'post']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['appraisal']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch', __('Branch*'), ['class' => 'col-form-label']) }}

                <select name="brances" id="brances" required class="form-control ">
                    <option selected disabled value="0">Select Category</option>

                    @foreach ($brances as $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-md-6 mt-2">
            <div class="form-group">
                {{ Form::label('employee', __('Employee*'), ['class' => 'form-label']) }}

                <div class="employee_div">

                    <select name="employee" id="employee" class="form-control " required>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('appraisal_date', __('Start Month*'), ['class' => 'col-form-label']) }}
                {{ Form::month('appraisal_date', '', ['class' => 'form-control ', 'autocomplete' => 'off', 'required' => 'required', 'id' => 'current_month']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('completion_date', __('Completion Month*'), ['class' => 'col-form-label']) }}
                {{ Form::month('completion_date', '', ['class' => 'form-control ', 'autocomplete' => 'off', 'required' => 'required', 'id' => 'current_month']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('notes_date', __('Notes Date*'), ['class' => 'col-form-label']) }}
                {{ Form::date('notes_date', '', ['class' => 'form-control ', 'autocomplete' => 'off', 'required' => 'required', 'id' => 'current_day']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remark', __('Target'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('remark', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'Enter remark']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('notes', __('Notes'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'Enter remark']) }}
            </div>
        </div>
    </div>
    <div class="row" id="stares">
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}



<script>
    $('#employee').change(function() {

        var emp_id = $('#employee').val();
        $.ajax({
            url: "{{ route('empByStar') }}",
            type: "post",
            data: {
                "employee": emp_id,
                "_token": "{{ csrf_token() }}",
            },

            cache: false,
            success: function(data) {
                $('#stares').html(data.html);
            }
        })
    });
</script>

<script>
    $('#brances').on('change', function() {
        var branch_id = this.value;

        $.ajax({
            url: "{{ route('getemployee') }}",
            type: "post",
            data: {
                "branch_id": branch_id,
                "_token": "{{ csrf_token() }}",
            },

            cache: false,
            success: function(data) {

                $('#employee').html('<option value="">Select Employee</option>');
                $.each(data.employee, function(key, value) {
                    $("#employee").append('<option value="' + value.id + '">' + value.name +
                        '</option>');
                });

            }
        })
    });
</script>

<script>
    document.getElementById('current_month').valueAsDate = new Date();
</script>
