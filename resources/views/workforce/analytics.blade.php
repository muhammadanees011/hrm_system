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
    $(document).ready(function() {
        // RENDERING CHARTS
        const departmentChartLabels = @json($dataForDepartmentChart['labels']);
        const departmentChartValue = @json($dataForDepartmentChart['values']);

        const statusChartLabels = @json($dataForStatusChart['labels']);
        const statusChartValue = @json($dataForStatusChart['values']);

        const statusJobLevelLabels = @json($dataForJobLevelChart['labels']);
        const statusJobLevelValue = @json($dataForJobLevelChart['values']);

        const statusBranchLabels = @json($dataForBranchChart['labels']);
        const statusBranchValue = @json($dataForBranchChart['values']);

        renderOverviewDepartmentChart(departmentChartLabels, departmentChartValue);
        renderOverviewStatusChart(statusChartLabels, statusChartValue);
        renderOverviewJobLevelChart(statusJobLevelLabels, statusJobLevelValue);
        renderOverviewBranchChart(statusBranchLabels, statusBranchValue);
    });

    function renderOverviewDepartmentChart(labels, value, reRender = false) {
        var options = {
            chart: {
                width: 380,
                type: 'donut',
            },
            // colors: ['#00E396', '#FF4560', '#008FFB', '#FEB019', '#775DD0'],
            labels: labels,
            series: value,
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
                // horizontalAlign: 'left',
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

        var chart = new ApexCharts(document.querySelector("#department-chart"), options);
        chart.render();

        if (reRender) {
            chart.destroy();
            const newData = value
            // Create a new chart with updated data
            var newChart = new ApexCharts(document.querySelector("#department-chart"), {
                ...options,
                series: newData
            });
            newChart.render();
        }
    }

    function renderOverviewStatusChart(labels, value, reRender = false) {
        var options = {
            chart: {
                width: 380,
                type: 'donut',
            },
            // colors: ['#00E396', '#FF4560', '#008FFB', '#FEB019', '#775DD0'],
            labels: labels,
            series: value,
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
                // horizontalAlign: 'left',
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

        var chart = new ApexCharts(document.querySelector("#status-chart"), options);
        chart.render();

        if (reRender) {
            chart.destroy();
            const newData = value
            // Create a new chart with updated data
            var newChart = new ApexCharts(document.querySelector("#status-chart"), {
                ...options,
                series: newData
            });
            newChart.render();
        }
    }

    function renderOverviewJobLevelChart(labels, value, reRender = false) {
        var options = {
            chart: {
                width: 380,
                type: 'donut',
            },
            // colors: ['#00E396', '#FF4560', '#008FFB', '#FEB019', '#775DD0'],
            labels: labels,
            series: value,
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
                // horizontalAlign: 'left',
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

        var chart = new ApexCharts(document.querySelector("#jobLevel-chart"), options);
        chart.render();

        if (reRender) {
            chart.destroy();
            const newData = value
            // Create a new chart with updated data
            var newChart = new ApexCharts(document.querySelector("#jobLevel-chart"), {
                ...options,
                series: newData
            });
            newChart.render();
        }
    }

    function renderOverviewBranchChart(labels, values, reRender = false) {
        var options = {
            chart: {
                type: 'bar',
                width: '100%',
                height: '500px',
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '8px',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Number of positions',
                data: values,
                colors: ['#00E396', '#FF4560', '#008FFB', '#FEB019', '#775DD0'] 
            }],
            xaxis: {
                categories: labels
            },
            // colors: ['#00E396', '#FF4560', '#008FFB', '#FEB019', '#775DD0'],
            fill: {
                type: 'solid',
            },
            grid: {
                strokeDashArray: 4,
            },
            legend: {
                show: false,
            },
            markers: {
                size: 6,
                // colors: ['#00E396', '#FF4560', '#008FFB', '#FEB019', '#775DD0'],
                opacity: 0.9,
                strokeWidth: 2,
                hover: {
                    size: 7,
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#branch-chart"), options);
        chart.render();

        if (reRender) {
            chart.destroy();
            // Create a new chart with updated data
            var newChart = new ApexCharts(document.querySelector("#branch-chart"), {
                ...options,
                series: [{
                    name: 'Number of positions',
                    data: values
                }]
            });
            newChart.render();
        }
    }
</script>
@endpush



@section('content')
{{-- Graphs --}}
<div class="col-xl-12">
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <h6>{{ __('Positions by department') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="display: flex;justify-content: center;">
                    <div id="department-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <h6>{{ __('Positions by status') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="display: flex;justify-content: center;">
                    <div id="status-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <h6>{{ __('Positions by job level') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="display: flex;justify-content: center;">
                    <div id="jobLevel-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <h6>{{ __('Positions by branches') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="display: flex;justify-content: center;">
                    <div id="branch-chart" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script-page')
{{-- Include necessary JavaScript files --}}
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>

<script type="text/javascript">

</script>


@endpush