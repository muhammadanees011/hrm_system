@extends('layouts.admin')

@section('page-title')
    {{ __('Payroll Setup') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('setsalary') }}">{{ __('Payroll Setup') }}</a></li>
@endsection

@section('content')
<style>
    .nav-tabs .active{
        background:orange !important;
        color:white !important;
    }
    .set-card{
        height:auto !important;
    }
</style>


<div class="col-12 mb-5">
    <div class="row">
        <div class="card set-card">
            <div class="card-header">
                <div class="row">
                    <div class="col-11">
                        <h5>{{ __('Setup') }}</h5>
                    </div>
                    @can('Create Allowance')
                        <!-- <div class="col-1 text-end">
                            <a data-url="{{ route('allowances.create',1) }}" data-ajax-popup="true"
                                data-title="{{ __('Create Allowance') }}" data-bs-toggle="tooltip" title=""
                                class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                                <i class="ti ti-plus"></i>
                            </a>
                        </div> -->
                    @endcan
                </div>
            </div>
            <div class="card-body">

                {{ Form::open(['url' => 'payroll/setup', 'method' => 'post']) }}
                <div class="form-group col-md-6">
                {{ Form::label('pay_frequency', __('Pay Frequency'), ['class' => 'col-form-label']) }}
                <select name="pay_frequency" class="form-control  select2" id="pay_frequency">
                    <option @if ($setup && $setup->pay_frequency=='Monthly') selected @endif value="Monthly">{{ __('Monthly') }}</option>
                    <option @if ($setup && $setup->pay_frequency=='Weekly') selected @endif value="Weekly">{{ __('Weekly') }}</option>
                </select>
                </div>

                <div class="form-group col-md-6">
                {{ Form::label('salary_calculation_method', __('Salary Calculation Method'), ['class' => 'col-form-label']) }}
                <select name="salary_calculation_method" class="form-control  select2" id="salary_calculation_method">
                    <option @if ($setup && $setup->salary_calculation_method=='Hourly') selected @endif value="Hourly">{{ __('Hourly (Pay per Hour)') }}</option>
                    <option @if ($setup && $setup->salary_calculation_method=='Fixed') selected @endif value="Fixed">{{ __('Fixed Salary') }}</option>
                </select>
                </div>

                <div class="form-group col-md-6">
                {{ Form::label('income_tax_percentage', __('Income Tax Deduction Percentage (%)'), ['class' => 'col-form-label']) }}
                {{ Form::text('income_tax_percentage', $setup ? $setup->income_tax_percentage : null, ['class' => 'form-control', 'placeholder' => 'enter %', 'required' => 'required']) }}
                </div>

                <div class="form-group col-md-6">
                {{ Form::label('late_arrival_or_early_departure_threshold', __('Late Arrival/Early Departure Threshold (mins)'), ['class' => 'col-form-label']) }}
                {{ Form::text('late_arrival_or_early_departure_threshold', $setup ? $setup->late_arrival_or_early_departure_threshold : null, ['class' => 'form-control', 'placeholder' => 'enter mins', 'required' => 'required']) }}
                </div>

                <div class="form-group col-md-6">
                {{ Form::label('late_arrival_or_early_departure_amount', __('Late & Early Out Deductions (amount)'), ['class' => 'col-form-label']) }}
                {{ Form::text('late_arrival_or_early_departure_amount', $setup ? $setup->late_arrival_or_early_departure_amount:null, ['class' => 'form-control', 'placeholder' => 'enter amount', 'required' => 'required']) }}
                </div>

                <div class="form-group col-md-6">
                {{ Form::label('provident_funds_deduction_percentage', __('Provident Funds Deductions Percentage (%)'), ['class' => 'col-form-label']) }}
                {{ Form::text('provident_funds_deduction_percentage', $setup ? $setup->provident_funds_deduction_percentage: null, ['class' => 'form-control', 'placeholder' => 'enter %', 'required' => 'required']) }}
                </div>

                <div class="form-group col-md-6">
                    <input type="submit" value="{{ __('Save') }}" class="btn btn-warning">
                </div>
                {{ Form::close() }}

            </div>
        </div>
    </div>
</div>


@endsection

@push('script-page')
    <script type="text/javascript">
        $(document).on('change', '.amount_type', function() {

            var val = $(this).val();
            var label_text = 'Amount';
            if (val == 'percentage') {
                var label_text = 'Percentage';
            }
            $('.amount_label').html(label_text);
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
                    $('#designation_id').empty();
                    $('#designation_id').append(
                        '<option value="">{{ __('Select any Designation') }}</option>');
                    $.each(data, function(key, value) {
                        var select = '';
                        if (key == '{{ 1 }}') {   //employee->designation_id 
                            select = 'selected';
                        }

                        $('#designation_id').append('<option value="' + key + '"  ' + select + '>' +
                            value + '</option>');
                    });
                }
            });
        }
    </script>
@endpush
