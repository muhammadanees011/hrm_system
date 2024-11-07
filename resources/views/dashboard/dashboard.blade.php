@extends('layouts.admin')

@section('page-title')
    {{ __('Dashboard') }}
@endsection

@php
    $setting = App\Models\Utility::settings();
    function formatElapsedTime($clockIn) {
        // Check if clock-in time is not '00:00:00'
            // dd($clockIn);
        if ($clockIn !== '00:00:00') {
            // Create a DateTime object for the clock-in time
            $checkInTime = \Carbon\Carbon::createFromFormat('H:i:s', $clockIn);
            // Get the current time
            $currentTime = \Carbon\Carbon::now();

            // If the date is also important, you might want to include it:
            // Assuming you also have a date field, e.g., $date
            // $date = '2024-11-04'; // Example date from your array
            //$checkInTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $clockIn);

            // Calculate the difference
            $elapsedTime = $currentTime->diff($checkInTime);

            // Format the elapsed time as needed (e.g., hours:minutes:seconds)
            return sprintf('%02d:%02d:%02d', $elapsedTime->h, $elapsedTime->i, $elapsedTime->s);
        }

        return null; // Return null if clock-in time is '00:00:00'
    }

@endphp

{{-- @section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
@endsection --}}

@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif


    @if (\Auth::user()->type == 'employee')
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5>{{ __('Calendar') }}</h5>
                            <input type="hidden" id="path_admin" value="{{ url('/') }}">
                        </div>
                        <div class="col-lg-6">
                            {{-- <div class="form-group"> --}}
                                <label for=""></label>
                                @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
                                    <select class="form-control" name="calender_type" id="calender_type"
                                    style="float: right;width: 155px;" onchange="get_data()">
                                        <option value="google_calender">{{ __('Google Calendar') }}</option>
                                        <option value="local_calender" selected="true">
                                            {{ __('Local Calendar') }}</option>
                                    </select>
                                @endif
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id='event_calendar' class='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header d-flex align-items-center gap-2 flex-wrap justify-content-between">
                    
                    <h5><i class="ti ti-clock me-2"></i> {{ __('Mark Attandance') }}</h5>
                    @if (!empty($employeeAttendance) && $employeeAttendance->clock_out == '00:00:00')
            <h6 class="mb-0 elpased-time">{{formatElapsedTime($employeeAttendance->clock_in)}}
            @if (!empty($employeeAttendance->break_start) && ($employeeAttendance->break_end === '00:00:00'))
            <i class="ti ti-player-pause text-warning"></i>
            @else
            <i class="ti ti-player-play text-primary"></i>
            @endif
            </h6>
        @endif
                </div>
                <div class="card-body">
                    @if(!empty($flexiTime))
                        <p class="text-warning p-1">Your request time of {{$flexiTime->hours}} hours for today will be adjusted when you clockout.</p> 
                    @endif
                    <p class="text-muted pb-0-5">
                        {{ __('My Office Time: ' . $officeTime['startTime'] . ' to ' . $officeTime['endTime']) }}</p>
                        <div class="row">
                    <div class="col-md-4 border-right">
                        {{ Form::open(['url' => 'attendanceemployee/attendance', 'method' => 'post']) }}
                        @if (empty($employeeAttendance) || $employeeAttendance->clock_out != '00:00:00')
                            <button type="submit" value="0" name="in" id="clock_in" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-play">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 7v5l2 2" />
                                <path d="M17 22l5 -3l-5 -3z" />
                                <path d="M13.017 20.943a9 9 0 1 1 7.831 -7.292" />
                            </svg>
                                {{ __('CLOCK IN') }}</button>
                        @else
                            <button type="submit" value="0" name="in" id="clock_in" class="btn btn-primary disabled" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-play">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 7v5l2 2" />
                            <path d="M17 22l5 -3l-5 -3z" />
                            <path d="M13.017 20.943a9 9 0 1 1 7.831 -7.292" />
                            </svg>
                            {{ __('CLOCK IN') }}</button>
                        @endif
                        {{ Form::close() }}
                    </div>
    
                    <div class="col-md-4">
        @if (!empty($employeeAttendance) && $employeeAttendance->clock_out == '00:00:00')
        <div class="d-flex justify-content-between">
            {{ Form::open(['url' => 'attendanceemployee/attendance', 'method' => 'post', 'class' => 'mr-2']) }}
            
            @if (empty($employeeAttendance->break_start) || $employeeAttendance->break_start === '00:00:00')
                <!-- Show START BREAK button if break hasn't started -->
                <button type="submit" value="start" name="break" class="btn btn-warning text-nowrap text-truncate">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-pause">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M20.942 13.018a9 9 0 1 0 -7.909 7.922" />
                <path d="M12 7v5l2 2" />
                <path d="M17 17v5" />
                <path d="M21 17v5" />
                </svg>
                    {{ __('START BREAK') }}</button>
            @elseif (!empty($employeeAttendance->break_start) && ($employeeAttendance->break_end === '00:00:00' || empty($employeeAttendance->break_end)))
                <!-- Show END BREAK button if break has started but not ended -->
                <button type="submit" value="end" name="break" class="btn btn-warning">{{ __('END BREAK') }}</button>
            @endif
            
            {{ Form::close() }}
        </div>
    @endif
</div>
    <div class="col-md-4">
        @if (!empty($employeeAttendance) && $employeeAttendance->clock_out == '00:00:00')
            {{ Form::model($employeeAttendance, ['route' => ['attendanceemployee.update', $employeeAttendance->id], 'method' => 'PUT']) }}
            <button type="submit" value="1" name="out" id="clock_out" class="btn btn-danger">
            
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-stop">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M21 12a9 9 0 1 0 -9 9" />
            <path d="M12 7v5l1 1" />
            <path d="M16 16h6v6h-6z" />
            </svg>
            {{ __('CLOCK OUT') }}</button>
            
            {{ Form::close() }}
        @else
            <button type="submit" value="1" name="out" id="clock_out" class="btn btn-danger disabled" disabled>{{ __('CLOCK OUT') }}</button>
        @endif
    </div>
    



</div>

                </div>
            </div>
            
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5>{{ __('Upcoming Holidays') }}</h5>
                </div>
                <div class="card-body">
                   <ul class="">
                    @foreach ($leaves as $leave)
                    <li class="mb-2">
                        <h6 class="mb-0 text-primary">{{ $leave->leaveType->title}}|{{ ucfirst(str_replace('_',' ', $leave->leave_duration)) }}</h6>
                        <span class="text-dark"> <b>Start Date : </b> {{\Auth::user()->dateFormat($leave->start_date)}} </span>
                        <span class="text-dark"><b>End Date : </b> {{\Auth::user()->dateFormat($leave->end_date)}} </span>
                        <p class="mb-0 text-secondary" style="font-size: 14px;">{{ $leave->leave_reason }}</p>
                    </li>    
                    @endforeach
                      
                   </ul>
                    
                </div>
            </div>
            <div class="card" style="height: 462px;">
                <div class="card-header card-body table-border-style">
                    <h5>{{ __('Meeting schedule') }}</h5>
                </div>
                <div class="card-body" style="height: 320px">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Meeting title') }}</th>
                                    <th>{{ __('Meeting Date') }}</th>
                                    <th>{{ __('Meeting Time') }}</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($meetings as $meeting)
                                    <tr>
                                        <td>{{ $meeting->title }}</td>
                                        <td>{{ \Auth::user()->dateFormat($meeting->date) }}</td>
                                        <td>{{ \Auth::user()->timeFormat($meeting->time) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5>{{ __('Announcement List') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Description') }}</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($announcements as $announcement)
                                    <tr>
                                        <td>{{ $announcement->title }}</td>
                                        <td>{{ \Auth::user()->dateFormat($announcement->start_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($announcement->end_date) }}</td>
                                        <td>{{ $announcement->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="col-xxl-12">

            {{-- start --}}
            <div class="row">

                <div class="col-lg-4 col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-3 mb-sm-0">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-users"></i>
                                        </div>
                                        <div class="ms-3">
                                            <small class="text-muted">{{ __('Total') }}</small>
                                            <h6 class="m-0">{{ __('Staff') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto text-end">
                                    <h4 class="m-0 text-primary">{{ $countUser + $countEmployee }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-3 mb-sm-0">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-info">
                                            <i class="ti ti-ticket"></i>
                                        </div>
                                        <div class="ms-3">
                                            <small class="text-muted">{{ __('Total') }}</small>
                                            <h6 class="m-0">{{ __('Ticket') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto text-end">
                                    <h4 class="m-0 text-info"> {{ $countTicket }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-3 mb-sm-0">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-warning">
                                            <i class="ti ti-wallet"></i>
                                        </div>
                                        <div class="ms-3">
                                            <small class="text-muted">{{ __('Total') }}</small>
                                            <h6 class="m-0">{{ __('Account Balance') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto text-end">
                                    <h4 class="m-0 text-warning">{{ \Auth::user()->priceFormat($accountBalance) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-primary">
                                    <i class="ti ti-cast"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted">{{ __('Total') }}</small>
                                    <h6 class="m-0">{{ __('Jobs') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-primary">{{ $activeJob + $inActiveJOb }}</h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-4 col-md-6">

            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-info">
                                    <i class="ti ti-cast"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted">{{ __('Total') }}</small>
                                    <h6 class="m-0">{{ __('Active Jobs') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-info"> {{ $activeJob }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">

            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-cast"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted">{{ __('Total') }}</small>
                                    <h6 class="m-0">{{ __('Inactive Jobs') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-warning">{{ $inActiveJOb }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- </div> --}}

        {{-- end --}}

        <div class="col-xxl-12">
            <div class="row">
                <div class="col-xl-5">

                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            <h5>{{ __('Meeting schedule') }}</h5>
                        </div>
                        <div class="card-body" style="height: 324px; overflow:auto">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Time') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($meetings as $meeting)
                                            <tr>
                                                <td>{{ $meeting->title }}</td>
                                                <td>{{ \Auth::user()->dateFormat($meeting->date) }}</td>
                                                <td>{{ \Auth::user()->timeFormat($meeting->time) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            <h5>{{ __("Today's Not Clock In") }}</h5>
                        </div>
                        <div class="card-body" style="height: 324px; overflow:auto">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($notClockIns as $notClockIn)
                                            <tr>
                                                <td>{{ $notClockIn->name }}</td>
                                                <td><span class="absent-btn">{{ __('Absent') }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-7">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5>{{ __('Calendar') }}</h5>
                                    <input type="hidden" id="path_admin" value="{{ url('/') }}">
                                </div>
                                <div class="col-lg-6">
                                    {{-- <div class="form-group"> --}}
                                        <label for=""></label>
                                        @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
                                            <select class="form-control" name="calender_type" id="calender_type"
                                            style="float: right;width: 155px;" onchange="get_data()">
                                                <option value="google_calender">{{ __('Google Calendar') }}</option>
                                                <option value="local_calender" selected="true">
                                                    {{ __('Local Calendar') }}</option>
                                            </select>
                                        @endif
                                    {{-- </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body card-635">
                            <div id='calendar' class='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5>{{ __('Announcement List') }}</h5>
                </div>
                <div class="card-body" style="height: 270px; overflow:auto">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Description') }}</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($announcements as $announcement)
                                    <tr>
                                        <td>{{ $announcement->title }}</td>
                                        <td>{{ \Auth::user()->dateFormat($announcement->start_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($announcement->end_date) }}</td>
                                        <td>{{ $announcement->description }}</td>
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
@endsection

@push('script-page')
    <script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>

    @if (Auth::user()->type == 'company' || Auth::user()->type == 'hr')
    <script type="text/javascript">
        
        $(document).ready(function() {
            get_data();
        });

        function get_data() {
            var calender_type = $('#calender_type :selected').val();
            console.log(calender_type);
            $('#calendar').removeClass('local_calender');
            $('#calendar').removeClass('google_calender');
            if (calender_type == undefined) {
                calender_type = 'local_calender';
            }
            $('#calendar').addClass(calender_type);

            $.ajax({
                url: $("#path_admin").val() + "/event/get_event_data",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'calender_type': calender_type
                },
                success: function(data) {
                    (function() {
                        var etitle;
                        var etype;
                        var etypeclass;
                        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            buttonText: {
                                timeGridDay: "{{ __('Day') }}",
                                timeGridWeek: "{{ __('Week') }}",
                                dayGridMonth: "{{ __('Month') }}"
                            },
                            // slotLabelFormat: {
                            //     hour: '2-digit',
                            //     minute: '2-digit',
                            //     hour12: false,
                            // },
                            themeSystem: 'bootstrap',
                            slotDuration: '00:10:00',
                            allDaySlot: true,
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            events: data,
                            // height: 'auto',
                            // timeFormat: 'H(:mm)',
                        });
                        calendar.render();
                    })();
                }
            });

        }
    </script>
    @else
    <script>
        function updateTimeDifference(clock_in) {
            // Get the current time
            const now = new Date();
            
            // Parse the clock_in time (format HH:MM:SS)
            const [hours, minutes, seconds] = clock_in.split(':').map(num => parseInt(num, 10));
            
            // Create a Date object for clock_in, assuming today's date
            const clockInDate = new Date(now);
            clockInDate.setHours(hours, minutes, seconds, 0); // set the hours, minutes, and seconds to the clock_in values
            
            // Calculate the difference in milliseconds
            const diffMs = now - clockInDate;
            
            // Convert milliseconds into hours, minutes, seconds
            const diffSec = Math.floor(diffMs / 1000);
            const diffMin = Math.floor(diffSec / 60);
            const diffHr = Math.floor(diffMin / 60);
            
            const remainingSec = diffSec % 60;
            const remainingMin = diffMin % 60;
            
            // Display the result
            const timeDifference = `${diffHr}:${remainingMin}:${remainingSec}`;
            document.querySelector('.elpased-time').innerText = timeDifference;
        }
        const elpasedTime = document.querySelector('.elpased-time');
        // if (elpasedTime) {
        //     const checkIn = elpasedTime.innerText;
        //     return
        //     setInterval(() => {
        //         updateTimeDifference(checkIn)
        //     }, 1000);
            
        // }
        $(document).ready(function() {
            get_data();
        });

        function get_data() {
            var calender_type = $('#calender_type :selected').val();
            console.log(calender_type);
            $('#event_calendar').removeClass('local_calender');
            $('#event_calendar').removeClass('google_calender');
            if (calender_type == undefined) {
                calender_type = 'local_calender';
            }
            $('#event_calendar').addClass(calender_type);

            $.ajax({
                url: $("#path_admin").val() + "/event/get_event_data",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'calender_type': calender_type
                },
                success: function(data) {
                    (function() {
                        var etitle;
                        var etype;
                        var etypeclass;
                        var calendar = new FullCalendar.Calendar(document.getElementById(
                        'event_calendar'), {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            buttonText: {
                                timeGridDay: "{{ __('Day') }}",
                                timeGridWeek: "{{ __('Week') }}",
                                dayGridMonth: "{{ __('Month') }}"
                            },
                            // slotLabelFormat: {
                            //     hour: '2-digit',
                            //     minute: '2-digit',
                            //     hour12: false,
                            // },
                            themeSystem: 'bootstrap',
                            slotDuration: '00:10:00',
                            allDaySlot: true,
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            events: data,
                            // height: 'auto',
                            // timeFormat: 'H(:mm)',
                        });
                        calendar.render();
                    })();
                }
            });

        }
    </script>
    @endif
@endpush
