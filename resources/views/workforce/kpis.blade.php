@extends('layouts.admin')
@section('page-title')
{{ __('View KPIs') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('View KPIs') }}</li>
@endsection

@push('script-page')
<script>
    $(document).ready(function() {
        // RENDERING CHARTS
        renderOverviewHeadcountChart(@json($dataForBranchChart['branches']), @json($dataForBranchChart['totalHeadcounts']), @json($dataForBranchChart['totalTargetHeadcounts']));
    });
    function renderOverviewHeadcountChart(labels, totalHeadcounts, totalTargetHeadcounts, reRender = false) {
        var options = {
            chart: {
                type: 'bar',
                width: '100%',
                height: '500px',
                stacked: true,
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '10px',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Total Headcounts',
                data: totalHeadcounts,
            }, {
                name: 'Total Target Headcounts',
                data: totalTargetHeadcounts,
                color: '#FF4560'
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
                show: true,
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

        var chart = new ApexCharts(document.querySelector("#hc-chart"), options);
        chart.render();

        if (reRender) {
            chart.destroy();
            // Create a new chart with updated data
            var newChart = new ApexCharts(document.querySelector("#hc-chart"), {
                ...options,
                series: [{
            name: 'Total Headcounts',
            data: totalHeadcounts,
            color: '#00E396'
        }, {
            name: 'Total Target Headcounts',
            data: totalTargetHeadcounts,
            color: '#FF4560'
        }],
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
                            <h6>{{ __('Total headcounts') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
                    <div class="display-1">{{$totalHeadcounts}}</div>
                    <span>People</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <h6>{{ __('Target Headcount') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
                    <div class="display-1">{{$totalTargetHeadcounts}}</div>
                    <span>People</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-106 col-md-6 col-sm-6">
                            <h6>{{ __('Total growth') }}</h6>
                        </div>
                        <div class="col-lg-106 col-md-6 col-sm-6">
                            {{ Form::month('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'month-btn form-control month-btn']) }}
                        </div>
                    </div>
                </div>
                <div class="card-body" style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
                    <div class="display-1 text-info" id="total-growth">+0%</div>
                    <div class="row w-100">
                        <div class="col-6 text-small" id="total-hc">Total Headcount: 0</div>
                        <div class="col-6" id="total-thc">Total Target Headcount: 0</div>
                    </div>
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
                            <h6>{{ __('Headcounts by branches') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="display: flex;justify-content: center;">
                    <div id="hc-chart" style="width: 100%;"></div>
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

<script>
    $(document).ready(function() {
        var month = $('input[name=month]').val();
        getTotalGrowth(month);
    });
    $(document).on('change', 'input[name=month]', function() {
        var month = $(this).val();
        console.log(month);
        getTotalGrowth(month);
    });

    function getTotalGrowth(month) {
        console.log("CSRF Token: {{ csrf_token() }}");

        $.ajax({
            url: '{{ route('workforce-planning.kpis.totalgrowth') }}',
            type: 'POST',
            data: {
                "month": month,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#total-growth').text('+' + data.total_growth_percentage + '%');
                $('#total-hc').text('Total Headcounts: ' + data.totalHeadcounts);
                $('#total-thc').text('Total Target Headcounts: ' + data.totalTargetHeadcounts);
                console.log(data);
            }
        });
    }
</script>
@endpush