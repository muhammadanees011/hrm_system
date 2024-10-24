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
        $('input[name="type"]:radio').on('change', function(e) {
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
        $(document).ready(function() {
            var b_id = $('#branch_id').val();
            // getDepartment(b_id);

            // RENDERING OVERVIEW CHART
            const data = @json($attendanceOverview);
            renderOverviewChart(data)
        });
        $(document).on('change', 'select[name=branch]', function() {
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

                    $('.department_id').append('<option value="0"> {{ __('All') }} </option>');
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

        $(document).on('change','.overview-date', function(){
            const selectedDate = $(this).val();
            $.ajax({
                url: '{{ route('attendanceemployee.getoverview') }}',
                type: 'POST',
                data: {
                    "date": selectedDate,
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
            colors: ['#00E396', '#FF4560', '#008FFB', '#FEB019', '#775DD0'],
            labels: ["Present","Absent","Leaves","Late","FlexiTime"],
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
                                            class="form-check-input"
                                            {{ isset($_GET['type']) && $_GET['type'] == 'monthly' ? 'checked' : 'checked' }}>
                                        <label class="form-check-label" for="monthly">{{ __('Monthly') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline form-group">
                                        <input type="radio" id="daily" value="daily" name="type"
                                            class="form-check-input"
                                            {{ isset($_GET['type']) && $_GET['type'] == 'daily' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="daily">{{ __('Daily') }}</label>
                                    </div>

                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 month">
                                    <div class="btn-box">
                                        {{ Form::label('month', __('Month'), ['class' => 'form-label']) }}
                                        {{ Form::month('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'month-btn form-control month-btn']) }}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                    <div class="btn-box">
                                        {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
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
                                            {{ Form::select('department', $department, isset($_GET['department']) ? $_GET['department'] : '', ['class' => 'form-control select']) }}
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

                                    <a href="#" data-url="{{ route('attendance.file.import') }}"
                                        data-ajax-popup="true" data-title="{{ __('Import  Attendance CSV File') }}"
                                        data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
                                        data-bs-original-title="{{ __('Import') }}">
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
                <div class="card">
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
            <div class="col-xl-5">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <h6>{{ __('Specific Date Attendance Overview') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group col-md-12">
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
                                        <a 
                                            href="#"
                                            data-url="{{ URL::to('user-graph/' . $attendance->employee_id) }}"
                                            data-ajax-popup="true" data-size="lg" 
                                            data-bs-toggle="tooltip" title=""
                                            data-title="{{ __($attendance->employee->name.' Attendance Overview') }}"
                                            data-bs-original-title="{{ __($attendance->employee->name.' Attendance Overview') }}"
                                        >
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
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-size="lg"
                                                            data-url="{{ URL::to('attendanceemployee/' . $attendance->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Attendance') }}"
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
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
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
@endsection


@push('script-page')
    {{-- Include necessary JavaScript files --}}
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Chart data and options for attendance chart
        var attendanceData = {!! json_encode($attendanceData) !!};
        var labels = {!! json_encode($labels) !!};
        console.log(attendanceData, labels);
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

<script type="text/javascript">

</script>

    
@endpush