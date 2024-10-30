@extends('layouts.admin')
@section('page-title')
{{ isset($employee) ? 'Attandance Overview of ' . $employee->name : 'Failed To Load Attendance Overview of Employee' }}
<!-- __('Employee Attandance Overview') -->
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="/attendanceemployee">Employees Attendance</a></li>
<li class="breadcrumb-item">{{ isset($employee) ? $employee->name : '/' }}</li>
@endsection
@section('content')
<div class="row">
  <div class="col-12">
    <label for="attandance_range">Filter By Range</label>
    {{ Form::select('attandance_range', ["Day", "Week", "Month"], "Day", ['class' => 'form-control select2', 'id' => 'attandance_range']) }}
  </div>
</div>
<div class="row mt-4">
  <div class="col-12">
    <nav>
      <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="graph-view-tab" data-bs-toggle="tab" data-bs-target="#graph-view"
          type="button" role="tab" aria-controls="graph-view" aria-selected="true">Graph Report</button>
        <button class="nav-link" id="list-view-tab" data-bs-toggle="tab" data-bs-target="#list-view" type="button"
          role="tab" aria-controls="list-view" aria-selected="false">Time Sheets</button>
      </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade show active" id="graph-view" role="tabpanel" aria-labelledby="graph-view-tab">

        <div class="row mt-4">
          <div class="col-lg-3 col-md-4">
            <div class="card card-sm shadow-sm  p-0">
              <div class="card-header p-2">
                <h6 style="color: #584ED2 !important" class="mb-0"><span class="ti ti-point"></span> Worked</h6>
              </div>
              <div class="card-body p-2 px-3" id="workedTime">
                0 hours 0 minutes
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-4">
            <div class="card card-sm shadow-sm  p-0">
              <div class="card-header p-2">
                <h6 style="color: #FF4560 !important" class="mb-0"><span class="ti ti-point"></span> Late</h6>
              </div>
              <div class="card-body p-2 px-3" id="lateTime">
                0 hours 0 minutes
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-4">
            <div class="card card-sm shadow-sm  p-0">
              <div class="card-header p-2">
                <h6 style="color: #008FFB !important" class="mb-0"><span class="ti ti-point"></span> Over time</h6>
              </div>
              <div class="card-body p-2 px-3" id="overTime">
                0 hours 0 minutes
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-4">
            <div class="card card-sm shadow-sm  p-0">
              <div class="card-header p-2">
                <h6 style="color: #FEB019 !important" class="mb-0"><span class="ti ti-point"></span> Early Leave</h6>
              </div>
              <div class="card-body p-2 px-3" id="earlyLeave">
                0 hours 0 minutes
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 d-none">
            <div class="card card-sm shadow-sm  p-0">
              <div class="card-header p-2">
                <h6 style="color: #00E396 !important" class="mb-0"><span class="ti ti-point"></span> Flexi Time</h6>
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

@endsection
@push('script-page')
  <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
  <script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>

  <script>
    const url = window.location.href;

    // Split the URL by slashes
    const parts = url.split('/');

    // Get the last part, which is the parameter you want
    const param = parts[parts.length - 1];
    const employeeId = param; // Replace 'employee_id' with the actual parameter name if different


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

@endpush