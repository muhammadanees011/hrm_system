<div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <h6>{{ __('Previous 7 Days Attendance') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="line-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>


        @push('script-page')
        <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Chart data and options for attendance chart
        var attendanceData = {!! json_encode($attendanceData) !!};
        var labels = {!! json_encode($labels) !!};
        
        // Log data to console for debugging
        console.log("Attendance Data:", attendanceData);
        console.log("Labels:", labels);

        var attendanceOptions = {
            chart: {
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
                horizontalAlign: 'right',
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

        // Create the chart
        var attendanceChart = new ApexCharts(document.querySelector("#line-chart"), attendanceOptions);

        // Render the chart and handle errors
        attendanceChart.render().then(function() {
            console.log("Chart rendered successfully!");
        }).catch(function(err) {
            console.error("Error rendering chart:", err);
        });

    });
</script>

@endpush