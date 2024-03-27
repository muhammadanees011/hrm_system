<div class="modal-body">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            {{ Form::select('attandance_range', ["7 Days","15 Days", "30 Days"], "7 Days", ['class' => 'form-control select2', 'id' => 'attandance_range']) }}
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="line-charts" style="height: 300px;"></div>
        </div>
    </div>
</div>

<script>
    const employeeId = "{{$employeeId}}"
    $(document).ready(function() {
        makeAjaxCall(employeeId, 7);

        $(document).on('change', '#attandance_range', function(){
            renderStackedChart([], []);
            const selectedRangeIndex = $(this).val()
            const selectedValue = selectedRangeIndex===0 ? 7 : (selectedRangeIndex==1 ? 15 : 30);
            makeAjaxCall(employeeId, selectedValue);
        });
    });

    function makeAjaxCall(employeeId, selectedRange){
        $.ajax({
            url: '{{ route('attendanceemployee.getSingleUserAttendance') }}',
            type: 'POST',
            data: {
                "employeeId": employeeId,
                "selectedRange": selectedRange,
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                if(response.success){
                    const {activeTimes, lates, overTimes, earlyleaves, flexiTimes, labels} = response;
                    const series = [
                        {
                          name: 'Active Time',
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
                }else{
                    console.log('Error occured')
                }
            }
        });
    }

    function renderStackedChart(series=[], userCategories=[], reRender=false){
        var oldSeries = [];
        var options = {
        series: series,
        noData: {
            text: 'Loading...'
        },
        colors: ['#00E396', '#FF4560', '#008FFB', '#FEB019', '#775DD0'],
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
            borderRadius: 10,
            dataLabels: {
              total: {
                enabled: true,
                style: {
                  fontSize: '13px',
                  fontWeight: 900
                }
              }
            }
          },
        },
        xaxis: {
          type: 'datetime',
          categories: userCategories
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

        if(JSON.stringify(oldSeries) !== JSON.stringify(series)){
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