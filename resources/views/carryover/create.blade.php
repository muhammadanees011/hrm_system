@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::open(['url' => 'carryover', 'method' => 'post']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="{{ route('generate', ['healthassessment']) }}"
            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
            data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12 form-group">
        {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
        {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'id' => 'employee_id', 'placeholder' => __('Select Employee')]) }}
        </div>

</div>

        <div class="row">
            <table id="leave-data-table">
                <style>td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }</style>
                    <thead>
            <tr>
                <th>Title</th>
                <th>Total</th>
                <th>Taken</th>
                <th>Remaining</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows will be populated dynamically -->
        </tbody>
    </table>

        </div>
        <div class="row">
            <div class="form-group col-lg-6 col-md-6">
                {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'col-form-label']) }}
                <select name="leave_type_id" id="leave_type_id" class="form-control select">
                    <option value="">{{ __('Select Leave Type') }}</option>
                    @foreach ($leavetypes as $leave)
                        <option value="{{ $leave->id }}">{{ $leave->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('leaves_count', __('No. of leaves'), ['class' => 'col-form-label']) }}
                {{ Form::number('leaves_count', null, ['class' => 'form-control text', 'autocomplete' => 'off' ,'required' => 'required']) }}
            </div>

        </div>
    </div>


<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Create')}}</button>
   
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
<script>
$('#employee_id').change(function() {
    var employeeId = $(this).val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/leave/jsoncount',
        method: 'POST',
        data: { employee_id: employeeId },
        success: function(response) {
            // Target the table body for updating
            var tableBody = $('#leave-data-table tbody');

            // Clear existing table rows in the body
            tableBody.empty();

            // Iterate over the response data and append rows to the table body
            $.each(response, function(index, leaveData) {
                tableBody.append(
                    '<tr>' +
                        '<td>' + leaveData.title + '</td>' +
                        '<td>' + leaveData.days + '</td>' +
                        '<td>' + leaveData.total_leave + '</td>' +
                        '<td>' + (leaveData.days - leaveData.total_leave) + '</td>' + // Corrected calculation
                    '</tr>'
                );
            });
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});
</script>