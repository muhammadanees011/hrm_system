@extends('layouts.admin')

@section('page-title')
    {{ __('Team Time Off') }}
@endsection

@php
    $setting = App\Models\Utility::settings();
@endphp


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Training Event') }}</li>
@endsection

@section('action-button')

@endsection


@section('content')

<style>

.employees-actions{
    display:flex;
    justify-content:end;
}

.dropdown {
    position: relative;
    display: inline-block;
  }
  
  .dropbtn {
    background-color: orange;
    color: white;
    padding: 6px;
    padding-left: 1.3rem;
    padding-right: 1.3rem;
    font-size: 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
  }
  
  .dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    right: 1px;
    font-size: 12px;
  }
  
  .dropdown-content a {
    color: black !important;
    padding: 5px 12px;
    text-decoration: none;
    display: block;
  }
  
  .dropdown-content a:hover {
    background-color: orange;
    color:white !important;
}
  .dropdown:hover .dropdown-content {display: block;}
  .dropdown:hover .dropbtn {color: white;}
</style>

    @if (\Auth::user()->type == 'employee')
    <div class="employees-actions">
        <div class="employees-nav mb-2">
            <div class="nav-titles">
                <div class="dropdown">
                <button class="dropbtn">Manage Leaves &#9660;</button>
                <div class="dropdown-content">
                    <a href="{{ route('carryover.index') }}">{{ __('Leave CarryOver Request') }}</a>
                    <a href="{{ route('leave.team') }}">{{ __('Team Time Off') }}</a>
                </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="employees-actions">
        <div class="employees-nav me-4 mb-2">
            <div class="nav-titles">
                <div class="dropdown">
                <button class="dropbtn">Manage Leaves &#9660;</button>
                <div class="dropdown-content">
                    <a href="{{ route('holidayplanner.index') }}">{{ __("Holiday Planner") }} </a>
                    <a href="{{ route('leave.index') }}">{{ __("Leave Request") }} </a>
                    <a href="{{ route('leaveentitlement.index') }}">{{ __("Leave Entitlement Report") }} </a>
                    <a href="{{ route('carryover.index') }}">{{ __('Leave CarryOver Request') }}</a>
                </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
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
                                    <option value="local_calender" selected="true">{{ __('Local Calendar') }}</option>
                                </select>
                            @endif
                            {{-- </div> --}}
                        </div>
                        <div class="card-body">
                            <div id='calendar' class='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script-page')
    <script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>

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
                url: $("#path_admin").val() + "/leave/team_off",
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
                            slotLabelFormat: {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                            },
                            themeSystem: 'bootstrap',
                            // slotDuration: '00:10:00',
                            allDaySlot: true,
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            events: data,
                            height: 'auto',
                            timeFormat: 'H(:mm)',
                        });
                        calendar.render();
                    })();
                }
            });

        }
    </script>

    <script>
        $(document).on('change', '.event_color', function(e) {
            $('.event_color').parent().removeClass('event_color_active');
            $(this).parent().addClass('event_color_active');
        });
    </script>
@endpush
