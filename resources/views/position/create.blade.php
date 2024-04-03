{{ Form::open(['url' => 'position', 'method' => 'post']) }}
<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('title', __('Position Title'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Position title')]) }}
                </div>
                @error('title')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select name="status" id="status" class="form-control">
                        <option value="Filled">Filled</option>
                        <option value="Vacant">Vacant</option>
                        <option value="Vacant Soon">Vacant Soon</option>
                        <option value="Open">Open</option>
                    </select>
                </div>
                @error('status')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                {{ Form::label('job_level', __('Job level'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select name="job_level" id="job_level" class="form-control">
                        <option value="Mid-level">Mid-level</option>
                        <option value="Junior">Junior</option>
                        <option value="Senior">Senior</option>
                    </select>
                </div>
                @error('job_level')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                {!! Form::label('branch', __('Branch'), ['class' => 'col-form-label']) !!}
                <div class="form-icon-user">
                    {{ Form::select('branch', $branches, null, ['class' => 'form-control select2', 'required' => 'required']) }}
                </div>
                @error('branch')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                {{ Form::label('department_id', __('Department'), ['class' => 'col-form-label']) }}
                <div class="form-icon-user">
                    <select class="form-control department_id" name="department" id="department_id" placeholder="Select Department">
                    </select>
                </div>
                @error('department')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}



<script>
    $(document).ready(function() {
        var b_id = $('#branch').val();
        getDepartment(b_id);
    });
    $(document).on('change', 'select[name=branch]', function() {
        var branch_id = $(this).val();
        console.log(branch_id);
        getDepartment(branch_id);
    });

    function getDepartment(bid) {
        console.log("CSRF Token: {{ csrf_token() }}");

        $.ajax({
            url: '{{ route('monthly.getdepartment') }}',
            type: 'POST',
            data: {
                "branch_id": bid,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('.department_id').empty();
                var emp_select = `<select class="form-control department_id" name="department" placeholder="Select Department"></select>`;
                $('.department_div').html(emp_select);

                $('.department_id').append('<option value=""> {{ __('Select Department') }} </option>');
                $.each(data, function(key, value) {
                    $('.department_id').append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    }
</script>