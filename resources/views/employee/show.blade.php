@extends('layouts.admin')

@section('page-title')
    {{ __('Employee') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ __('Manage Employee') }}</li>
@endsection

@section('action-button')
@php
    $employeeId = $employee->id;
@endphp
    <div class="float-end">
        @can('edit employee')
            <a href="{{ route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                data-bs-toggle="tooltip" title="{{ __('Edit') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-pencil"></i>
            </a>
        @endcan
    </div>
    <div class="text-end mb-3">
        <div class="d-flex justify-content-end drp-languages">
            <ul class="list-unstyled mb-0 m-2">
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="drp-text hide-mob text-primary"> {{ __('Joining Letter') }}
                            <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="{{ route('joiningletter.download.pdf', $employee->id) }}" class=" btn-icon dropdown-item"
                            data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                class="ti ti-download ">&nbsp;</i>{{ __('PDF') }}</a>

                        <a href="{{ route('joininglatter.download.doc', $employee->id) }}" class=" btn-icon dropdown-item"
                            data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                class="ti ti-download ">&nbsp;</i>{{ __('DOC') }}</a>
                    </div>
                </li>
            </ul>
            <ul class="list-unstyled mb-0 m-2">
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="drp-text hide-mob text-primary"> {{ __('Experience Certificate') }}
                            <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="{{ route('exp.download.pdf', $employee->id) }}" class=" btn-icon dropdown-item"
                            data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                class="ti ti-download ">&nbsp;</i>{{ __('PDF') }}</a>

                        <a href="{{ route('exp.download.doc', $employee->id) }}" class=" btn-icon dropdown-item"
                            data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                class="ti ti-download ">&nbsp;</i>{{ __('DOC') }}</a>
                    </div>
                </li>
            </ul>
            <ul class="list-unstyled mb-0 m-2">
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="drp-text hide-mob text-primary"> {{ __('NOC') }}
                            <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="{{ route('noc.download.pdf', $employee->id) }}" class=" btn-icon dropdown-item"
                            data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                class="ti ti-download ">&nbsp;</i>{{ __('PDF') }}</a>

                        <a href="{{ route('noc.download.doc', $employee->id) }}" class=" btn-icon dropdown-item"
                            data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                class="ti ti-download ">&nbsp;</i>{{ __('DOC') }}</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
@endsection

@push('script-page')

    <script>
        $(document).ready(function() {
            var b_id = $('#branch_id').val();
            // RENDERING OVERVIEW CHART
            @if(isset($attendanceOverview))
            const data = @json($attendanceOverview);
            renderOverviewChart(data)
            @endif
        });

        $(document).on('change','.start-date ,.end-date', function(){
            const startDate = $('.start-date').val();
            const endDate = $('.end-date').val();
            const employeeId = {{ $employeeId }};
            $.ajax({
                url: '{{ route('employee.attendance_overview') }}',
                type: 'POST',
                data: {
                    "start_date": startDate,
                    "end_date": endDate,
                    "employee_id": employeeId,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if(response.success){
                        renderOverviewChart(response.data, true)
                    }else{
                        show_toastr('error', response.message)
                    }
                }
            });
        });

        function renderOverviewChart(data, reRender=false){
            var options = {
              chart: {
              width: 380,
              type: 'donut',
            },
            colors: ['#00E396', '#FF4560', '#008FFB', '#FEB019'],
            labels: ["Present","Absent","Leaves","Late"],
            series: data,
            plotOptions: {
              pie: {
                startAngle: -90,
                endAngle: 270
              }
            },
            dataLabels: {
              enabled: true
            },
            fill: {
              type: 'gradient',
            },
            legend: {
                formatter: function(val, opts) {
                    return val + " - " + opts.w.globals.series[opts.seriesIndex]
                },
                position: 'bottom',
                horizontalAlign: 'left',
            },
            title: {
              text: ''
            },
            responsive: [{
              breakpoint: 480,
              options: {
                chart: {
                  width: 200
                },
              }
            }]
            };

            var chart = new ApexCharts(document.querySelector("#donut-chart"), options);
            chart.render();

            if(reRender){
                chart.destroy();
                const newData = data
                // Create a new chart with updated data
                var newChart = new ApexCharts(document.querySelector("#donut-chart"), {
                    ...options,
                    series: newData
                });
                newChart.render();
            }
        }
    </script>
@endpush

@section('content')
<style>
    .nav-tabs .active{
        background:orange !important;
        color:white !important;
    }
    .set-card{
        height:70vh !important;
    }
    .late{
        background-color:red;
        border-radius:5px;
        color:white;
        display:flex;
        justify-content:center;
    }
    .early_leaving{
        background-color:orange;
        border-radius:5px;
        color:white;
        display:flex;
        justify-content:center;
    }
</style>
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-12 col-md-6">

                    <div class="card">
                        <div class="card-body employee-detail-body fulls-card">
                            <h5 class="mt-4">{{ __('Personal Detail') }}</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Employee ID') }} : </strong>
                                        <span>{{ $employeesId }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm font-style">
                                        <strong class="font-bold">{{ __('Name') }} :</strong>
                                        <span>{{ $employee->name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm font-style">
                                        <strong class="font-bold">{{ __('Email') }} :</strong>
                                        <span>{{ $employee->email }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Date of Birth') }} :</strong>
                                        <span>{{ \Auth::user()->dateFormat($employee->dob) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Phone') }} :</strong>
                                        <span>{{ $employee->phone }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Address') }} :</strong>
                                        <span>{{ $employee->address }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Salary Type') }} :</strong>
                                        <span>{{ !empty($employee->salaryType) ? $employee->salaryType->name : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Basic Salary') }} :</strong>
                                        <span>{{ $employee->salary }}</span>
                                    </div>
                                </div>
                            </div>
                            <h5 class="mt-5">{{ __('Company Detail') }}</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Branch') }} : </strong>
                                        <span>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm font-style">
                                        <strong class="font-bold">{{ __('Department') }} :</strong>
                                        <span>{{ !empty($employee->department) ? $employee->department->name : '' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Designation') }} :</strong>
                                        <span>{{ !empty($employee->designation) ? $employee->designation->name : '' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Date Of Joining') }} :</strong>
                                        <span>{{ \Auth::user()->dateFormat($employee->company_doj) }}</span>
                                    </div>
                                </div>
                            </div>
                            <h5 class="mt-5">{{ __('Bank Account Detail') }}</h5>
                            <hr>
                            <div class="row  mb-2">
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Account Holder Name') }} : </strong>
                                        <span>{{ $employee->account_holder_name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm font-style">
                                        <strong class="font-bold">{{ __('Account Number') }} :</strong>
                                        <span>{{ $employee->account_number }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Bank Name') }} :</strong>
                                        <span>{{ $employee->bank_name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Bank Identifier Code') }} :</strong>
                                        <span>{{ $employee->bank_identifier_code }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Branch Location') }} :</strong>
                                        <span>{{ $employee->branch_location }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Tax Payer Id') }} :</strong>
                                        <span>{{ $employee->tax_payer_id }}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <h6>{{ __('Specific Date Attendance Overview') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ Form::label('start_date', 'Start Date', ['class' => 'control-label']) }}
                                    {{ Form::date('start_date', isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d'), ['class' => 'form-control month-btn start-date']) }}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('end_date', 'End Date', ['class' => 'control-label']) }}
                                    {{ Form::date('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'), ['class' => 'form-control month-btn end-date']) }}
                                </div>
                            </div>
                            <div id="donut-chart"></div>
                        </div>
                    </div>
                </div>
          
            </div>

    
            @if(isset($attendanceEmployee))
            <div class="row">
                <!-- deduction-->
                <div class="col-md-12">
                    <div class="card set-card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-11">
                                    <h5>{{ __('Attendance And Punctuality Report') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class=" card-body table-border-style" style=" overflow:auto">
                            <div class="table-responsive">
                                <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Clock In') }}</th>
                                        <th>{{ __('Clock Out') }}</th>
                                        <th>{{ __('Late') }}</th>
                                        <th>{{ __('Early Leaving') }}</th>
                                        <th>{{ __('Overtime') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendanceEmployee as $attendance)
                                        <tr>
                                            <td>{{ \Auth::user()->dateFormat($attendance->date) }}</td>
                                            <td>{{ $attendance->status }}</td>
                                            <td>{{ $attendance->clock_in != '00:00:00' ? \Auth::user()->timeFormat($attendance->clock_in) : '00:00' }}
                                            </td>
                                            <td>{{ $attendance->clock_out != '00:00:00' ? \Auth::user()->timeFormat($attendance->clock_out) : '00:00' }}
                                            </td>
                                            <td>
                                                <div class="late">
                                                {{ $attendance->late }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="early_leaving">
                                                {{ $attendance->early_leaving }}
                                                </div>
                                            </td>
                                            <td>{{ $attendance->overtime }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            
                                               
            <div class="row">
                <!-- Education-->
                <div class="col-md-6">
                    <div class="card set-card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">{{ __('Education') }} <span class="badge rounded-circle text-bg-primary">{{ $educations->count()}}</span></h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#educationModal"><i class="ti ti-plus"></i></button>
                                <x-education-modal :employeesId="$employee->id" formAction="{{ route('employee-education.store') }}"></x-education-modal>  
                            </div>
                        </div>
                        <div class="card-body table-border-style" style=" overflow:auto">
                            @foreach ($educations as $education)
                            <x-education-card :education="$education"></x-education-card>

                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Experience-->
                <div class="col-md-6">
                    <div class="card set-card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">{{ __('Experience') }} 
                                    <span class="badge rounded-circle text-bg-primary">{{ $experiences->count() }}</span>
                                </h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#experienceModal">
                                    <i class="ti ti-plus"></i>
                                </button>
                                <x-experience-modal :employeesId="$employee->id" id="experienceModal" title="Add Experience" :formAction="route('employee-experience.store')"></x-modal>
                            </div>
                        </div>
                        <div class="card-body table-border-style" style=" overflow:auto">
                            @foreach ($experiences as $experience)
                                <x-experience-card :experience="$experience" />
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Skills-->
                <div class="col-md-6">
                    <div class="card set-card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">{{ __('Skills') }} 
                                    <span class="badge rounded-circle text-bg-primary">{{ $skills->count() }}</span>
                                </h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#skillsModal">
                                    <i class="ti ti-plus"></i>
                                </button>
                                <x-skills-modal  :employeesId="$employee->id" id="skillsModal" title="Add Skill" :formAction="route('employee-skills.store')"></x-modal>
                            </div>
                        </div>
                        <div class="card-body table-border-style" style="overflow:auto">
                            @foreach ($skills as $skill)
                                <x-skill-card :skill="$skill" />
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
@endpush