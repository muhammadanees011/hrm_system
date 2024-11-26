@extends('layouts.admin')

@section('page-title')
{{ __('Meet Team') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Meet Team') }}</li>
@endsection

@section('action-button')
<a class="btn btn-sm btn-primary collapsed" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button"
    aria-expanded="false" aria-controls="multiCollapseExample1" data-bs-toggle="tooltip" title="{{ __('Filter') }}">
    <i class="ti ti-filter"></i>
</a>
@endsection

@php
    $profile = asset(Storage::url('uploads/avatar/'));
@endphp

@section('content')
<div class="col-sm-12">
    <div class="multi-collapse mt-2 collapse" id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => ['employee.profile'], 'method' => 'get', 'id' => 'employee_profile_filter']) }}
                <div class="row align-items-center justify-content-end">
                    <div class="col-xl-10">
                        <div class="row">
                            <!-- Filters for Branch, Department, and Designation -->
                            <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                                @if(\Auth::user()->type == "hr" || \Auth::user()->type == "company")
                                    <div class="btn-box">
                                        {{ Form::label('branch', __('Select Branches*'), ['class' => 'form-label']) }}
                                        {{ Form::select('branch', $brances, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => ' select-width form-control', 'id' => 'branch_id']) }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                                @if(\Auth::user()->type == "hr" || \Auth::user()->type == "company")
                                    <div class="btn-box">
                                        <div class="btn-box" id="department_id">
                                            {{ Form::label('department', __('Department'), ['class' => 'form-label']) }}
                                            <select class="form-control select department_id" name="department"
                                                id="department_id" placeholder="Select Department">
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('designation', __('Designation'), ['class' => 'form-label']) }}
                                    <select class=" select-width select2-multiple form-control" id="designation_id"
                                        name="designation" data-placeholder="{{ __('Select Designation ...') }}">
                                        <option value="">{{ __('Designation') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="row">
                            <div class="col-auto mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('employee_profile_filter').submit(); return false;"
                                    data-bs-toggle="tooltip" title="{{ __('Apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('employee.profile') }}" class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip" title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
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

<div class="col-12">
    <div class="row">
        <!-- Display Manager and Team -->
        @foreach ($employees as $managerId => $team)
                @php
                    // Filter the team based on the current manager
                    $manager = $team->first()->manager;
                @endphp
                <hr class="mt-4">
                <h6 class="text-primary mb-3"><i class="ti ti-users"></i> Manager: {{ $manager->name ?? 'Unknown' }}'s Team</h6>
                <div class="row managers-team">
                    <!-- Manager Card -->
                    @if ($manager)
                            <div class="col-sm-6 col-md-4 col-lg-3 col-xxl-2 manager-card">
                                <div class="card text-center h-100 shadow-sm" style="background: #d4d2f6">
                                    <div class="card-body">
                                        <div class="badge bg-primary rounded-pill mb-2 p-2">Manager</div>
                                        <div class="avatar d-flex justify-content-center">
                                            @php
                                                $initial = strtoupper(substr($manager->name ?? 'N/A', 0, 2));
                                            @endphp
                                            @if (!empty($manager->user) && $manager->user->avatar != 'avatar.png')
                                                <img src="{{ $profile . $manager->user->avatar }}"
                                                    class="rounded-circle border border-primary" style="width: 60%" />
                                            @else
                                                <div class="img-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center fs-5"
                                                    style="width: 64px; height: 64px;">{{ $initial }}</div>
                                            @endif
                                        </div>
                                        <h4 class="mt-2 text-primary">{{ $manager->name ?? 'N/A' }}</h4>
                                        <div><small>{{ $manager->user->email ?? 'N/A' }}</small></div>
                                        <div><small>{{ $manager->department->name ?? 'N/A' }}</small></div>
                                        <small>{{ $manager->designation->name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </div>
                    @endif

                    <!-- Team Members Under Manager -->
                    @foreach ($team as $employee)
                            <div class="col-sm-6 col-md-4 col-lg-3 col-xxl-2 employee-card">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <div class="avatar text-center">
                                            @php
                                                $initial = strtoupper(substr($employee->name ?? 'N/A', 0, 2));
                                            @endphp
                                            @if (!empty($employee->user) && $employee->user->avatar != 'avatar.png')
                                                <img src="{{ $profile . $employee->user->avatar }}" class="rounded-circle"
                                                    style="width: 60%" />
                                            @else
                                                <div class="img-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center fs-5"
                                                    style="width: 64px; height: 64px;">{{ $initial }}</div>
                                            @endif
                                        </div>
                                        <h4 class="mt-2 text-primary">{{ $employee->name ?? 'N/A' }}</h4>
                                        <div><small>{{ $employee->user->email ?? 'N/A' }}</small></div>
                                        <div><small>{{ $employee->department->name ?? 'N/A' }}</small></div>
                                        <small>{{ $employee->designation->name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </div>
        @endforeach
    </div>
</div>
@endsection

@push('script-page')
    <script>
        function startTour() {
            var tour = introJs();

            const managerTeam = document.querySelector('.managers-team');
            const managerCard = document.querySelector('.manager-card');
            const empCard = document.querySelector('.employee-card');
            tour.setOptions({
                steps: [
                    {
                        element: managerTeam,
                        intro: "Here is your manager's team where you can meet all the members of your team.",
                        position: 'top',
                        title: 'Your Team'
                    },
                    {
                        element: managerCard,
                        intro: "This is your manager.",
                        title: 'The Manager'
                    },
                    {
                        element: empCard,
                        intro: "Here are your colleagues.",
                        title: 'The Employees'
                    },
                    {
                        intro: 'Let’s move to the next step and view your profile.',
                        title: 'Proceed to next Step'
                    },
                    {
                        intro: 'Please wait...',
                        title: 'HRM Pro Tour'
                    },
                ],
                
                buttonClass: 'btn btn-warning btn-sm fw-default rounded-pill',
                    showProgress: true,
                    exitOnOverlayClick: false,
                    nextLabel: `&nbsp; Next <i class="ti ti-chevron-right"></i>`,
                    prevLabel: `<i class="ti ti-chevron-left"></i> Back &nbsp;`,
                    skipLabel: '<i class="ti ti-x"></i>'
            });

            tour.onbeforechange(function (targetElement) {
                var currentStep = tour._currentStep;
                // Check if we are on Step 3 (index 2)
                if (currentStep === 4) {  // Step 3 is index 2, since steps are 0-indexed
                    // var stepElement = document.querySelector('#clock_in');
                    // Check if the element has been clicked before allowing the tour to continue
                    window.location.href = '/employee'
                    return false;
                }

                return true;  // Allow the tour to continue
            });

            tour.start();

        }
        $(document).ready(function () {
            var b_id = $('#branch_id').val();
            // getDepartment(b_id);
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
                    var emp_selct = `<select class="department_id form-control multi-select" id="choices-multiple" multiple="" required="required" name="department_id[]"></select>`;
                    $('.department_div').html(emp_selct);
                    $('.department_id').append('<option value=""> {{ __('Select Department') }} </option>');
                    $.each(data, function (key, value) {
                        $('.department_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        }

        window.addEventListener('DOMContentLoaded', () => {
            const employee = '{{ Auth::user()->employee }}';
                if (employee && employee.istour_done == 0) {
                    startTour();
                }
        })
    </script>
@endpush