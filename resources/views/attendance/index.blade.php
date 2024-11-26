@extends('layouts.admin')
@section('page-title')
{{ __('Manage Attendance Lists') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Attendance List') }}</li>
@endsection

@push('script-page')
    <script>
        $('input[name="type"]:radio').on('change', function (e) {
            var type = $(this).val();

            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.date').addClass('d-none');
                $('.date').removeClass('d-block');
            } else {
                $('.date').addClass('d-block');
                $('.date').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
            }
        });

        $('input[name="type"]:radio:checked').trigger('change');
    </script>

    <script>
        $(document).ready(function () {
            var b_id = $('#branch_id').val();
            // getDepartment(b_id);

            // RENDERING OVERVIEW CHART
            const data = @json($attendanceOverview);
            renderOverviewChart(data)
        });
        $(document).on('change', 'select[name=branch]', function () {
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
                success: function (data) {

                    $('.department_id').empty();
                    var emp_selct = `<select class="form-control department_id" name="department_id" id="choices-multiple"
                                                                placeholder="Select Department" >
                                                                </select>`;
                    $('.department_div').html(emp_selct);

                    $('.department_id').append('<option value="0"> {{ __('All') }} </option>');
                    $.each(data, function (key, value) {
                        $('.department_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });
                }
            });
        }

        $(document).on('change', '.overview-date', function () {
            const selectedDate = $(this).val();
            $.ajax({
                url: '{{ route('attendanceemployee.getoverview') }}',
                type: 'POST',
                data: {
                    "date": selectedDate,
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.success) {
                        renderOverviewChart(response.data, true)
                    } else {
                        show_toastr('error', response.message)
                    }
                }
            });
        });

        function renderOverviewChart(data, reRender = false) {
            var options = {
                chart: {
                    width: 380,
                    type: 'donut',
                },
                colors: ['#00E396', '#FF4560', '#008FFB', '#FEB019', '#775DD0'],
                labels: ["Present", "Absent", "Leaves", "Late", "FlexiTime"],
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
                    formatter: function (val, opts) {
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

            if (reRender) {
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

@if (session('status'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {!! session('status') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="col-sm-12">
    <div class=" mt-2 " id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => ['attendanceemployee.index'], 'method' => 'get', 'id' => 'attendanceemployee_filter']) }}
                <div class="row align-items-center justify-content-end">
                    <div class="col-xl-10">
                        <div class="row">

                            <div class="col-3">
                                <label class="form-label">{{ __('Type') }}</label> <br>

                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="monthly" value="monthly" name="type"
                                        class="form-check-input" {{ isset($_GET['type']) && $_GET['type'] == 'monthly' ? 'checked' : 'checked' }}>
                                    <label class="form-check-label" for="monthly">{{ __('Monthly') }}</label>
                                </div>
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="daily" value="daily" name="type" class="form-check-input" {{ isset($_GET['type']) && $_GET['type'] == 'daily' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="daily">{{ __('Daily') }}</label>
                                </div>

                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 month">
                                <div class="btn-box change-monthly-attendance-view">
                                    {{ Form::label('month', __('Month'), ['class' => 'form-label']) }}
                                    {{ Form::month('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'month-btn form-control month-btn']) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date ">
                                <div class="btn-box" id="change-monthly-attendance-view">
                                    {{ Form::label('date', __('Date'), ['class' => 'form-label ']) }}
                                    {{ Form::date('date', isset($_GET['date']) ? $_GET['date'] : '', ['class' => 'form-control month-btn']) }}
                                </div>
                            </div>
                            @if (\Auth::user()->type != 'employee')
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                                        {{ Form::select('branch', $branch, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => 'form-control select', 'id' => 'branch_id']) }}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    {{-- <div class="btn-box">
                                        {{ Form::label('department', __('Department'), ['class' => 'form-label']) }}
                                        {{ Form::select('department', $department, isset($_GET['department']) ?
                                        $_GET['department'] : '', ['class' => 'form-control select']) }}
                                    </div> --}}

                                    <div class="form-icon-user" id="department_div">
                                        {{ Form::label('department', __('Department'), ['class' => 'form-label']) }}
                                        <select class="form-control select department_id" name="department_id"
                                            id="department_id" placeholder="Select Department">
                                        </select>
                                    </div>

                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-auto mt-4">
                        <div class="row">
                            <div class="col-auto">

                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('attendanceemployee_filter').submit(); return false;"
                                    data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                    data-original-title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>

                                <a href="{{ route('attendanceemployee.index') }}" class="btn btn-sm btn-danger "
                                    data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                    data-original-title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                </a>

                                <a href="#" data-url="{{ route('attendance.file.import') }}" data-ajax-popup="true"
                                    data-title="{{ __('Import  Attendance CSV File') }}" data-bs-toggle="tooltip"
                                    title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Import') }}">
                                    <i class="ti ti-file"></i>
                                </a>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

{{-- Graphs --}}
<div class="col-xl-12">
    <div class="row">
        <div class="col-xl-7">
            <div class="card" id="attendanceoverviewcard">
                <div class="card-header">
                    <div class="card-title">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <h6>{{ __('Attendance Overview') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="line-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-5" id="specific-attandance">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <h6>{{ __('Specific Date Attendance Overview') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group col-md-12 specific-date-attendance">
                        {{ Form::date('overview_date', isset($_GET['overview_date']) ? $_GET['overview_date'] : date('Y-m-d'), ['class' => 'form-control month-btn overview-date']) }}
                    </div>
                    <div id="donut-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="col-xl-12">
    <div class="card">
        <div class="card-header card-body table-border-style">
            @if (!$employee_id)

                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                @if (\Auth::user()->type != 'employee')
                                    <th>{{ __('Employee') }}</th>
                                @endif
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Clock In') }}</th>
                                <th>{{ __('Clock Out') }}</th>
                                <th>{{ __('Late') }}</th>
                                <th>{{ __('Early Leaving') }}</th>
                                <th>{{ __('Overtime') }}</th>
                                <th>{{ __('FlexiTime') }}</th>
                                @if (Gate::check('Edit Attendance') || Gate::check('Delete Attendance'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($attendanceEmployee as $attendance)
                                        <tr>
                                            <td>
                                                <a href="{{ URL::to('attendanceemployee/user-timesheets/' . $attendance->employee_id) }}"
                                                    data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __($attendance->employee->name . ' Attendance Overview') }}"
                                                    data-bs-original-title="{{ __($attendance->employee->name . ' Attendance Overview') }}">
                                                    {{ !empty($attendance->employee) ? $attendance->employee->name : '' }}
                                                </a>
                                            </td>
                                            <td>{{ \Auth::user()->dateFormat($attendance->date) }}</td>
                                            <td>{{ $attendance->status }}</td>
                                            <td>{{ $attendance->clock_in != '00:00:00' ? \Auth::user()->timeFormat($attendance->clock_in) : '00:00' }}
                                            </td>
                                            <td>{{ $attendance->clock_out != '00:00:00' ? \Auth::user()->timeFormat($attendance->clock_out) : '00:00' }}
                                            </td>
                                            <td>{{ $attendance->late }}</td>
                                            <td>{{ $attendance->early_leaving }}</td>
                                            <td>{{ $attendance->overtime }}</td>
                                            <td>{{$attendance->requested_time ?? ""}}</td>
                                            <td class="Action">
                                                @if (Gate::check('Edit Attendance') || Gate::check('Delete Attendance'))
                                                                    <span>
                                                                        @can('Edit Attendance')
                                                                            <div class="action-btn bg-info ms-2">
                                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                                                    data-url="{{ URL::to('attendanceemployee/' . $attendance->id . '/edit') }}"
                                                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                                                    data-title="{{ __('Edit Attendance') }}"
                                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                                    <i class="ti ti-pencil text-white"></i>
                                                                                </a>
                                                                            </div>
                                                                        @endcan

                                                                        @can('Delete Attendance')
                                                                                                <div class="action-btn bg-danger ms-2">
                                                                                                    {!! Form::open([
                                                                                'method' => 'DELETE',
                                                                                'route' => ['attendanceemployee.destroy', $attendance->id],
                                                                                'id' => 'delete-form-' . $attendance->id,
                                                                            ]) !!}
                                                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                                                        aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                                                                                    </form>
                                                                                                </div>
                                                                        @endcan
                                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div id="user-chart" class="chart-container">
                        {{-- This is where the chart will be rendered --}}
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12" id="changetimesheetrange">
                        <label for="attandance_range">Filter By Range</label>
                        {{ Form::select('attandance_range', ["Day", "Week", "Month"], "Day", ['class' => 'form-control select2', 'id' => 'attandance_range']) }}
                    </div>
                </div>
                <div class="row mt-4" id="timesheets">
                    <div class="col-12">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="graph-view-tab" data-bs-toggle="tab"
                                    data-bs-target="#graph-view" type="button" role="tab" aria-controls="graph-view"
                                    aria-selected="true">Graph Report</button>
                                <button class="nav-link" id="list-view-tab" data-bs-toggle="tab" data-bs-target="#list-view"
                                    type="button" role="tab" aria-controls="list-view" aria-selected="false">Time
                                    Sheets</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="graph-view" role="tabpanel"
                                aria-labelledby="graph-view-tab">

                                <div class="row mt-4">
                                    <div class="col-lg-3 col-md-4">
                                        <div class="card card-sm shadow-sm  p-0">
                                            <div class="card-header p-2">
                                                <h6 style="color: #584ED2 !important" class="mb-0"><span
                                                        class="ti ti-point"></span> Worked</h6>
                                            </div>
                                            <div class="card-body p-2 px-3" id="workedTime">
                                                0 hours 0 minutes
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        <div class="card card-sm shadow-sm  p-0">
                                            <div class="card-header p-2">
                                                <h6 style="color: #FF4560 !important" class="mb-0"><span
                                                        class="ti ti-point"></span> Late</h6>
                                            </div>
                                            <div class="card-body p-2 px-3" id="lateTime">
                                                0 hours 0 minutes
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        <div class="card card-sm shadow-sm  p-0">
                                            <div class="card-header p-2">
                                                <h6 style="color: #008FFB !important" class="mb-0"><span
                                                        class="ti ti-point"></span> Over time</h6>
                                            </div>
                                            <div class="card-body p-2 px-3" id="overTime">
                                                0 hours 0 minutes
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        <div class="card card-sm shadow-sm  p-0">
                                            <div class="card-header p-2">
                                                <h6 style="color: #FEB019 !important" class="mb-0"><span
                                                        class="ti ti-point"></span> Early Leave</h6>
                                            </div>
                                            <div class="card-body p-2 px-3" id="earlyLeave">
                                                0 hours 0 minutes
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 d-none">
                                        <div class="card card-sm shadow-sm  p-0">
                                            <div class="card-header p-2">
                                                <h6 style="color: #00E396 !important" class="mb-0"><span
                                                        class="ti ti-point"></span> Flexi Time</h6>
                                            </div>
                                            <div class="card-body p-2 px-3" id="flexiTime">
                                                0 hours 0 minutes
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div id="line-charts" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="list-view" role="tabpanel" aria-labelledby="list-view-tab">


                                <div class="card mt-3 table-card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-11">
                                                <h5>{{ __('Attendance And Punctuality Report') }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table" id="attendance_table">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="worked">
                                                        <th>
                                                            Worked Time
                                                        </th>
                                                    </tr>
                                                    <tr class="late">
                                                        <th>
                                                            Late Time
                                                        </th>
                                                    </tr>
                                                    <tr class="over">
                                                        <th>
                                                            Over Time
                                                        </th>
                                                    </tr>
                                                    <tr class="early">
                                                        <th>
                                                            Early Leave
                                                        </th>
                                                    </tr>
                                                    <tr class="flexi">
                                                        <th>
                                                            Flexi Leave
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
            @endsection


            @push('script-page')
                {{-- Include necessary JavaScript files --}}
                <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
                <script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>

                <script>
                    $(document).ready(function () {
                        // Chart data and options for attendance chart
                        var attendanceData = {!! json_encode($attendanceData) !!};
                        var labels = {!! json_encode($labels) !!};
                        var attendanceOptions = {
                            chart: {
                                // height: 400,
                                type: 'line',
                                width: '100%',
                                toolbar: {
                                    show: false,
                                },
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '25%',
                                    endingShape: 'rounded'
                                },
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                width: 2,
                                curve: 'smooth'
                            },
                            series: attendanceData,
                            xaxis: {
                                categories: labels,
                            },
                            colors: ['#3ec9d6', '#FF3A6E', '#FFD700', '#32CD32'],
                            fill: {
                                type: 'solid',
                            },
                            grid: {
                                strokeDashArray: 4,
                            },
                            legend: {
                                show: true,
                                position: 'top',
                                horizontalAlign: 'left',
                            },
                            markers: {
                                size: 6, // Show four markers
                                colors: ['#3ec9d6', '#FF3A6E'],
                                opacity: 0.9,
                                strokeWidth: 2,
                                hover: {
                                    size: 7,
                                }
                            }

                        };


                        var attendanceChart = new ApexCharts(document.querySelector("#line-chart"), attendanceOptions);
                        attendanceChart.render();

                    });
                </script>

                <script>
                    const url = window.location.href;

                    // Split the URL by slashes
                    const parts = url.split('/');

                    // Get the last part, which is the parameter you want
                    const param = parts[parts.length - 1];
                    const employeeId = {{ $employee_id }}; // Replace 'employee_id' with the actual parameter name if different

                    function getDaysOfWeek(dateStrings) {
                        return dateStrings.map(dateStr => {
                            const date = new Date(dateStr);
                            return date.toLocaleString('en-US', { weekday: 'short' });
                        });
                    };
                    function formatTime(value) {
                        if (value === 0) return '0:00';

                        const hours = Math.floor(value);
                        const minutes = Math.round((value - hours) * 60);
                        return `${hours}h ${minutes < 10 ? '0' : ''}${minutes}m`;
                    }
                    function calculateTotalTime(data) {
                        let totalHours = 0;
                        let totalMinutes = 0;

                        data?.forEach(value => {
                            if (typeof value === 'string') {
                                // Split the float string into hours and minutes
                                const parts = value.split('.');
                                const hours = parseInt(parts[0]) || 0; // Get hours (default to 0)
                                const minutes = (parts[1] ? parseInt(parts[1]) : 0); // Get minutes (default to 0)

                                // Update total hours and minutes
                                totalHours += hours;
                                totalMinutes += minutes;
                            }
                        });

                        // Convert minutes to hours if greater than 60
                        totalHours += Math.floor(totalMinutes / 60);
                        totalMinutes = totalMinutes % 60; // Get remaining minutes

                        return `${totalHours} hours ${totalMinutes} minutes`
                        // return {
                        //     totalHours,
                        //     totalMinutes
                        // };
                    }
                    $(document).ready(function () {
                        makeAjaxCall(employeeId, 1);

                        $(document).on('change', '#attandance_range', function () {
                            renderStackedChart([], []);
                            const selectedRangeIndex = $(this).val()
                            let selectedValue = 1
                            if (selectedRangeIndex == 1) {
                                selectedValue = 7
                            } else if (selectedRangeIndex == 2) {
                                selectedValue = 30
                            }
                            makeAjaxCall(employeeId, selectedValue);
                        });
                    });

                    function makeAjaxCall(employeeId, selectedRange) {
                        $.ajax({
                            url: '{{ route('attendanceemployee.getSingleUserAttendance') }}',
                            type: 'POST',
                            data: {
                                "employeeId": employeeId,
                                "selectedRange": selectedRange,
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                if (response.success) {
                                    const { activeTimes, lates, overTimes, earlyleaves, flexiTimes, labels } = response;

                                    const series = [
                                        {
                                            name: 'Worked',
                                            data: activeTimes
                                        },
                                        {
                                            name: 'Late',
                                            data: lates
                                        },
                                        {
                                            name: 'Over Time',
                                            data: overTimes
                                        },
                                        {
                                            name: 'Early Leave',
                                            data: earlyleaves
                                        },
                                        {
                                            name: 'Flexi Time',
                                            data: flexiTimes
                                        }
                                    ];

                                    renderStackedChart(series, labels);
                                } else {
                                    console.log('Error occured')
                                }
                            }
                        });
                    }

                    function renderStackedChart(series = [], userCategories = [], reRender = false) {
                        var oldSeries = [];
                        var options = {
                            series: series,
                            noData: {
                                text: 'Loading...'
                            },
                            colors: ['#584ED2', '#FF4560', '#008FFB', '#FEB019', '#00E396'],
                            chart: {
                                type: 'bar',
                                height: 350,
                                stacked: true,
                                toolbar: {
                                    show: false
                                },
                                zoom: {
                                    enabled: false
                                }
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    legend: {
                                        position: 'bottom',
                                        offsetX: -10,
                                        offsetY: 0
                                    }
                                }
                            }],
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    show: true,
                                    borderRadius: 10,
                                    dataLabels: {
                                        enabled: true,
                                        formatter: function (val, opts) {
                                            alert(val)
                                            return val
                                        },
                                        total: {
                                            enabled: true,
                                            style: {
                                                fontSize: '14px',
                                                fontWeight: 900
                                            }
                                        }
                                    }
                                },
                            },

                            yaxis: {
                                labels: {
                                    formatter: (value) => {
                                        return value + ' hours'
                                    },
                                }
                            },
                            xaxis: {
                                categories: getDaysOfWeek(userCategories),
                                labels: {
                                    formatter: function (value) {
                                        const date = new Date(value);
                                        return date.toLocaleString('en-US', { weekday: 'narrow' });;
                                    }
                                },
                            },
                            legend: {
                                enabled: false,
                                position: 'right',
                                offsetY: 40
                            },
                            fill: {
                                opacity: 1
                            }
                        };

                        // Create the chart
                        var attendanceChart = new ApexCharts(document.querySelector("#line-charts"), options);
                        attendanceChart.render();
                        const workedTime = document.getElementById('workedTime');
                        const lateTime = document.getElementById('lateTime');
                        const overTime = document.getElementById('overTime');
                        const earlyLeave = document.getElementById('earlyLeave');
                        const flexiTime = document.getElementById('flexiTime');
                        let attendance_table = document.querySelector('#attendance_table');

                        workedTime.innerText = calculateTotalTime(series?.[0]?.data);
                        lateTime.innerText = calculateTotalTime(series?.[1]?.data);
                        overTime.innerText = calculateTotalTime(series?.[2]?.data);
                        earlyLeave.innerText = calculateTotalTime(series?.[3]?.data);
                        flexiTime.innerText = calculateTotalTime(series?.[4]?.data);
                        const weekDays = getDaysOfWeek(userCategories)
                        attendance_table.querySelector('thead tr').innerHTML = `<td></td>`
                        attendance_table.querySelector('tbody tr.worked').innerHTML = `<th>Worked Time</th>`
                        attendance_table.querySelector('tbody tr.late').innerHTML = `<th>Late Time</th>`
                        attendance_table.querySelector('tbody tr.over').innerHTML = `<th>Over Time</th>`
                        attendance_table.querySelector('tbody tr.early').innerHTML = `<th>Early Leave</th>`
                        attendance_table.querySelector('tbody tr.flexi').innerHTML = `<th>Flexi Time</th>`
                        weekDays?.forEach((category, index) => {
                            const theDate = new Date(userCategories?.[index]);
                            const formatedDate = theDate.toLocaleString('en-US', { day: "numeric", month: "short" });
                            attendance_table.querySelector('thead tr').innerHTML += `<td class="text-center"><div class="d-flex align-items-center flex-column">
                                  <span>${category}</span>
                                  <span>${formatedDate}</span>
                                  </div></td>`;

                            // Rows
                            attendance_table.querySelector('tbody tr.worked').innerHTML += `
                                  <td class="text-center">${formatTime(series?.[0]?.data?.[index])}</td>`;
                            attendance_table.querySelector('tbody tr.late').innerHTML += `
                                  <td class="text-center">${formatTime(series?.[1]?.data?.[index])}</td>`;
                            attendance_table.querySelector('tbody tr.over').innerHTML += `
                                  <td class="text-center">${formatTime(series?.[2]?.data?.[index])}</td>`;
                            attendance_table.querySelector('tbody tr.early').innerHTML += `
                                  <td class="text-center">${formatTime(series?.[3]?.data?.[index])}</td>`;
                            attendance_table.querySelector('tbody tr.flexi').innerHTML += `
                                  <td class="text-center">${formatTime(series?.[4]?.data?.[index])}</td>`;

                        });

                        if (JSON.stringify(oldSeries) !== JSON.stringify(series)) {
                            const newSeries = series;
                            const newCategories = userCategories;
                            attendanceChart.updateOptions({
                                series: newSeries,
                                xaxis: {
                                    categories: newCategories,
                                },
                            });
                        }
                    }
                </script>
                <script>
                    function startTour() {
                        var tour = introJs();

                        const attandanceOverview = document.querySelector('#attendanceoverviewcard');
                        const selectMonthlyAttandance = document.querySelector('.change-monthly-attendance-view');
                        const selectSpecificAttandance = document.querySelector('.specific-date-attendance');
                        const specificAttandance = document.querySelectorAll('#specific-attandance')
                        tour.setOptions({
                            steps: [
                                {
                                    element: attandanceOverview,
                                    intro: 'This is your overall attendance overview.',
                                    title: 'Attandance Overview',
                                    position: 'right'
                                },
                                {
                                    element: selectMonthlyAttandance,
                                    intro: "Here you can select a different month to view the attendance overview.",
                                    title: 'Specific Month Attandance Overview'
                                },
                                {
                                    element: '#donut-chart',
                                    intro: "This graph shows attendance data for specific dates.",
                                    title: 'Daily Attandance Overview',
                                    position: 'left'
                                },
                                {
                                    element: selectSpecificAttandance,
                                    intro: 'Use this option to select a specific date for the attendance overview.',
                                    title: 'Custom Date Overview'
                                },
                                {
                                    element: '#timesheets',
                                    intro: 'Here you can view the Timesheet data in graphical form, showing monthly, weekly, or daily attendance.',
                                    position: 'bottom',
                                    title: 'Time Sheets Data'
                                },
                                {
                                    element: '#changetimesheetrange',
                                    intro: "Change the time range for the view here.",
                                    title: 'Specific Time Range'
                                },
                                {
                                    element: '#graph-view-tab',
                                    intro: 'This tab displays a graphical overview of your timesheets.',
                                    title: 'Graph View'
                                },
                                {
                                    element: '#list-view-tab',
                                    intro: 'This tab displays a tabular overview of your timesheets.',
                                    title: 'Table View'
                                },
                                {
                                    intro: 'You’ve now learned about the main features of the app.',
                                    title: 'Congratulations!'
                                },
                                {
                                    intro: 'Feel free to explore the rest of the app on your own.',
                                    title: 'Tour Completed'
                                },
                                {
                                    intro: 'Good luck and enjoy using the app!',
                                    title: 'Good Luck'
                                },
                            ],

                            buttonClass: 'btn btn-warning btn-sm fw-default rounded-pill',
                            showProgress: true,
                            exitOnOverlayClick: false,
                            nextLabel: `&nbsp; Next <i class="ti ti-chevron-right"></i>`,
                            prevLabel: `<i class="ti ti-chevron-left"></i> Back &nbsp;`,
                            skipLabel: '<i class="ti ti-x"></i>',

                            // Set custom transition duration (in milliseconds)
                            transitionDuration: 2000, // 500ms for each transition

                            // Set a custom timing function for smooth transition (ease, linear, etc.)
                            transitionTimingFunction: 'ease-out'
                        });



                        tour.start();
                        tour.onexit(function () {
                            $.ajax({
                                url: '{{ route('employee.tourdone') }}',
                                type: 'POST',
                                data: {
                                    "tour": 'attendance',
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function (response) {
                                    alert('Tour Completed');
                                }
                            });
                        });


                    }

                    window.addEventListener('DOMContentLoaded', () => {
                        const isTourDone = '{{ Auth::user()->employee->istour_done }}';
                        if (isTourDone == 0) {
                            startTour();
                        }
                    })

                </script>


            @endpush