@extends('layouts.admin')

@section('page-title')
{{ __('Create Employee') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Employee') }}</a></li>
<li class="breadcrumb-item">{{ __('Create Employee') }}</li>
@endsection


@section('content')
<style>
    .cursor-pointer {
        cursor: pointer;
    }
</style>

<div class="">
    <div class="">
        <div class="row">

        </div>
        {{ Form::open(['route' => ['employee.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-md-6">
                <div class="card em-card">
                    <div class="card-header">
                        <h5>{{ __('Personal Detail') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                {!! Form::text('name', old('name'), [
                                'class' => 'form-control',
                                'required' => 'required',
                                'placeholder' => 'Enter employee name',
                                ]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('phone', __('Phone'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                {!! Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => 'Enter employee Phone']) !!}
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {{ Form::date('dob', null, ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select Date of Birth']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    <div class="d-flex radio-check">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="g_male" value="Male" name="gender" class="form-check-input">
                                            <label class="form-check-label " for="g_male">{{ __('Male') }}</label>
                                        </div>
                                        <div class="custom-control custom-radio ms-1 custom-control-inline">
                                            <input type="radio" id="g_female" value="Female" name="gender" class="form-check-input">
                                            <label class="form-check-label " for="g_female">{{ __('Female') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('email', __('Email'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                {!! Form::email('email', old('email'), [
                                'class' => 'form-control',
                                'required' => 'required',
                                'placeholder' => 'Enter employee Email',
                                ]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('password', __('Password'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                {!! Form::password('password', [
                                'class' => 'form-control',
                                'required' => 'required',
                                'placeholder' => 'Enter employee Password',
                                ]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('address', __('Address'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                            {!! Form::textarea('address', old('address'), [
                            'class' => 'form-control',
                            'rows' => 3,
                            'placeholder' => 'Enter employee Address',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card em-card">
                    <div class="card-header">
                        <h5>{{ __('Company Detail') }}</h5>
                    </div>
                    <div class="card-body employee-detail-create-body">
                        <div class="row">
                            @csrf
                            <div class="form-group">
                                {!! Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']) !!}
                                {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('branch_id', __('Select Branch*'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::select('branch_id', $branches, null, ['class' => 'form-control branch_id', 'required' => 'required', 'placeholder' => 'Select Branch', 'id' => 'branch_id']) }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                {{-- {{ Form::label('department_id', __('Select Department*'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    {{ Form::select('department_id', $departments, null, ['class' => 'form-control ', 'id' => 'department_id', 'required' => 'required', 'placeholder' => 'Select Department']) }}
                                </div> --}}

                                <div class="form-icon-user" id="department_id">
                                    {{ Form::label('department_id', __('Department'), ['class' => 'form-label']) }}
                                    <select class="form-control department_id" name="department_id" id="department_id" placeholder="Select Department">
                                    </select>
                                </div>

                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('designation_id', __('Select Designation'), ['class' => 'form-label']) }}

                                <div class="form-icon-user">
                                    <div class="designation_div">
                                        <select class="form-control  designation_id" name="designation_id" id="choices-multiple" placeholder="Select Designation">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('company_doj', __('Company Date Of Joining'), ['class' => 'form-label']) !!}
                                {{ Form::date('company_doj', null, ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select Company Date Of Joining']) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('employee_type', __('Select Employee Type'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    <select class="form-control  employee_type" name="employee_type" id="employee_type">
                                        <option value="Permanent">Permanent</option>
                                        <option value="Probation">Probation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6" id="probation_days_input" style="display: none;">
                                {!! Form::label('probation_days', __('Enter Probation Days'), ['class' => 'form-label']) !!}
                                {!! Form::number('probation_days', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('manager', __('Select Manager'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                {{ Form::select('manager_id', $managers, null, ['class' => 'form-control ', 'id' => 'manager_id', 'required' => 'required', 'placeholder' => 'Select Manager']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 ">
                <div class="card em-card">
                    <div class="card-header">
                        <h5>{{ __('Document') }}</h6>
                    </div>
                    <div class="card-body employee-detail-create-body">
                        @foreach ($documents as $key => $document)
                        <div class="row">
                            <div class="form-group col-12 d-flex">
                                <div class="float-left col-4">
                                    <label for="document" class="float-left pt-1 form-label">{{ $document->name }}
                                        @if ($document->is_required == 1)
                                        <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                </div>
                                <div class="float-right col-8">
                                    <input type="hidden" name="emp_doc_id[{{ $document->id }}]" id="" value="{{ $document->id }}">
                                    <!--  <div class="choose-file form-group">
                                                            <label for="document[{{ $document->id }}]"
                                                                class="choose-files bg-primary">
                                                                <div>{{ __('Choose File') }}</div>
                                                                <input
                                                                    class="form-control d-none @error('document') is-invalid @enderror border-0"
                                                                    @if ($document->is_required == 1) required @endif
                                                                    name="document[{{ $document->id }}]" type="file"
                                                                    id="document[{{ $document->id }}]"
                                                                    data-filename="{{ $document->id . '_filename' }}">
                                                            </label>
                                                           <a href="#"><p class="{{ $document->id . '_filename' }} "></p></a>

                                                        </div> -->

                                    <div class="choose-files ">
                                        <label for="document[{{ $document->id }}]">
                                            <div class=" bg-primary document cursor-pointer"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                            </div>
                                            <input type="file" class="form-control file @error('document') is-invalid @enderror " @if ($document->is_required == 1) required @endif
                                            name="document[{{ $document->id }}]"
                                            id="document[{{ $document->id }}]"
                                            data-filename="{{ $document->id . '_filename' }}"
                                            onchange="document.getElementById('{{ 'blah' . $key }}').src = window.URL.createObjectURL(this.files[0])">
                                        </label>
                                        <img id="{{ 'blah' . $key }}" src="" width="50%" />
                                        {{-- <a><p class="{{ $document->id . '_filename' }} "></p></a> --}}

                                    </div>

                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6 ">
                <div class="card em-card ">
                    <div class="card-header">
                        <h5>{{ __('Bank Account Detail') }}</h5>
                    </div>
                    <div class="card-body employee-detail-create-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                {!! Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'form-label']) !!}
                                {!! Form::text('account_holder_name', old('account_holder_name'), [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Account Holder Name',
                                ]) !!}

                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('account_number', __('Account Number'), ['class' => 'form-label']) !!}
                                {!! Form::number('account_number', old('account_number'), [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Account Number',
                                ]) !!}

                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) !!}
                                {!! Form::text('bank_name', old('bank_name'), ['class' => 'form-control', 'placeholder' => 'Enter Bank Name']) !!}

                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'form-label']) !!}
                                {!! Form::text('bank_identifier_code', old('bank_identifier_code'), [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Bank Identifier Code',
                                ]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']) !!}
                                {!! Form::text('branch_location', old('branch_location'), [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Branch Location',
                                ]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'form-label']) !!}
                                {!! Form::text('tax_payer_id', old('tax_payer_id'), [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Tax Payer Id',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="float-end">
        <button type="submit" class="btn  btn-primary">{{ 'Create' }}</button>
    </div>
    </form>
</div>
@endsection

@push('script-page')
<script>
    $('input[type="file"]').change(function(e) {
        var file = e.target.files[0].name;
        var file_name = $(this).attr('data-filename');
        $('.' + file_name).append(file);
    });
</script>
<script>
     $(document).ready(function() {
        // Function to toggle visibility of probation days input
        function toggleProbationDaysInput() {
            var selectedType = $('#employee_type').val();
            if (selectedType === 'Probation') {
                $('#probation_days_input').show();
            } else {
                $('#probation_days_input').hide();
            }
        }

        // Initial state
        toggleProbationDaysInput();

        // Event listener for changes in the employee type select
        $('#employee_type').change(function() {
            toggleProbationDaysInput();
        });
    });

    $(document).ready(function() {
        var b_id = $('#branch_id').val();
        // getDepartment(b_id);
    });
    $(document).on('change', 'select[name=branch_id]', function() {
        var branch_id = $(this).val();

        getDepartment(branch_id);
    });

    function getDepartment(bid) {

        $.ajax({
            url: '{{ route('monthly.getdepartment') }}',
            type: 'POST',
            data: {
                "branch_id": bid,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {

                $('.department_id').empty();
                var emp_selct = `<select class="form-control department_id" name="department_id" id="choices-multiple"
                                            placeholder="Select Department" >
                                            </select>`;
                $('.department_div').html(emp_selct);

                $('.department_id').append('<option value=""> {{ __('Select Department') }} </option>');
                $.each(data, function(key, value) {
                    $('.department_id').append('<option value="' + key + '">' + value +
                        '</option>');
                });
                new Choices('#choices-multiple', {
                    removeItemButton: true,
                });
            }
        });
    }

    $(document).ready(function() {
        var d_id = $('.department_id').val();
        getDesignation(d_id);
    });

    $(document).on('change', 'select[name=department_id]', function() {
        var department_id = $(this).val();
        getDesignation(department_id);
    });

    function getDesignation(did) {

        $.ajax({
            url: '{{ route('employee.json') }}',
            type: 'POST',
            data: {
                "department_id": did,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {

                $('.designation_id').empty();
                // var emp_selct = ` <select class="form-control designation_id" name="designation_id" id="choices-multiple"
                //                         placeholder="Select Designation" >
                //                         </select>`;
                var emp_selct = `<select class="form-control designation_id" name="designation_id"
                                                 placeholder="Select Designation" required>
                                            </select>`;
                $('.designation_div').html(emp_selct);

                $('.designation_id').append('<option value=""> {{ __('Select Designation') }} </option>');
                $.each(data, function(key, value) {
                    $('.designation_id').append('<option value="' + key + '">' + value +
                        '</option>');
                });
                new Choices('#choices-multiple', {
                    removeItemButton: true,
                });
            }
        });
    }
</script>

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
@endpush