@extends('layouts.admin')

@section('page-title')
{{ __('Create Benefits Opt-out') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ url('pension-optout') }}">{{ __('Benefits Opt-outs') }}</a></li>
<li class="breadcrumb-item">{{ __('Create Benefits Opt-out') }}</li>
@endsection


@section('content')
<style>
    .cursor-pointer {
        cursor: pointer;
    }
</style>

<div class="">
    <div class="">
        {{ Form::open(['route' => ['pension-optout.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-md-6">
                <div class="card em-card">
                    <div class="card-header">
                        <h5>{{ __('Opt-out Form') }}</h5>
                    </div>
                    <div class="card-body employee-detail-create-body">
                        <div class="row">
                            @csrf
                            <div class="form-group col-md-6">
                                {{ Form::label('employee_id', __('Select Employee*'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    {{-- Form::select('employee_id', $employees, null, ['class' => 'form-control employee_id', 'required' => 'required', 'placeholder' => 'Select Employee', 'id' => 'employee_id']) --}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('date', __('Opt-out Date'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                {{-- Form::date('date', null, ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select Opt-out date']) --}}
                            </div>
                            <div class="form-group">
                                {!! Form::label('reasons', __('Opt-out Reasons'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                {!! Form::textarea('reasons', old('reasons'), [
                                'class' => 'form-control',
                                'rows' => 3,
                                'placeholder' => 'Enter Opt-out reasons',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card em-card" id="emp-detail-block" style="display: none;">
                    <div class="card-header">
                        <h5>{{ __('Employee Details') }}</h5>
                    </div>
                    <div class="card-body employee-detail-create-body">
                        <div class="row">
                            <div id="emp_detail"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="float-end">
        <button type="submit" class="btn  btn-primary">{{ 'Submit' }}</button>
    </div>
    </form>
</div>
@endsection

@push('script-page')
<script>
    $(document).on('change', 'select[name=employee_id]', function() {
        var emp_id = $(this).val();
        // console.log("sadasdasd", emp_id);
        getEmployee(emp_id);
    });

    function getEmployee(eid) {
        $.ajax({
            url: '{{route('pensionOptout.emp')}}',
            type: 'POST',
            data: {
                "emp_id": eid,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#emp-detail-block').show();
                $('#emp_detail').empty();
                $('#emp_detail').html(`
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Name:</b> ${data.employee.name}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Gender:</b> ${data.employee.gender}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Email:</b> ${data.employee.email}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Age:</b> ${calculateAge(data.employee.dob)}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Date of Birth:</b> ${formatDate(data.employee.dob)}</div>
                <hr>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Company DOJ:</b> ${formatDate(data.employee.company_doj)}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Branch Name:</b> ${data.employee.branch.name}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Department Name:</b> ${data.employee.department.name}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Designation:</b> ${data.employee.designation.name}</div>
                <hr>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Benefits Scheme:</b> ${data.employee.pension_optin.pension_scheme.scheme_name}</div>
            `);
            }
        });
    }

    // Function to calculate age from dob
    function calculateAge(dob) {
        const today = new Date();
        const birthDate = new Date(dob);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        return age;
    }

    // Function to format the date
    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }
</script>
@endpush