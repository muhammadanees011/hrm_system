@extends('layouts.admin')

@section('content')
<div class="col-12">
    <!-- Display Authenticated User's Manager Card and Team -->
    <h6 class="text-primary mb-3"><i class="ti ti-users"></i> Your Manager's Team</h6>

    <div class="row">
        <!-- Manager Card -->
        @if ($employees[$managerId]->first()->manager ?? false)
            <div class="col-sm-6 col-md-4 col-lg-3 col-xxl-2">
                <div class="card h-100 bg-light border-primary">
                    <div class="card-body text-center">
                        @php
                            $manager = $employees[$managerId]->first()->manager;
                            $profile = \App\Models\Utility::get_file('uploads/avatar/');
                            $initial = strtoupper(substr($manager->name ?? 'N/A', 0, 2));
                        @endphp
                        <div class="badge bg-primary rounded-pill mb-2 p-2">Manager</div>
                        
                        <!-- Manager Profile Image -->
                        @if (!empty($manager->user) && $manager->user->avatar != 'avatar.png')
                            <img id="blah" width="100" style="border-radius: 50%" src="{{ $profile . $manager->user->avatar }}" />
                        @else
                            <div class="img-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center fs-5" style="width: 64px; height: 64px;">{{ $initial }}</div>
                        @endif

                        <h4 class="mt-2 text-primary">{{ $manager->name ?? 'N/A' }}</h4>
                        <small>{{ $manager->user->email ?? 'N/A' }}</small>
                        <div><small>{{ $manager->department->name ?? 'N/A' }}</small></div>
                        <small>{{ $manager->designation->name ?? 'N/A' }}</small>
                    </div>
                </div>
            </div>
        @endif

        <!-- Team Members Under Authenticated User's Manager -->
        @foreach ($employees[$managerId] ?? [] as $employee)
            <div class="col-sm-6 col-md-4 col-lg-3 col-xxl-2">
                <div class="card h-100">
                    <div class="card-body text-center">
                        @php
                            $profile = \App\Models\Utility::get_file('uploads/avatar/');
                            $initial = strtoupper(substr($employee->name ?? 'N/A', 0, 2));
                        @endphp
                        
                        <!-- Employee Profile Image -->
                        @if (!empty($employee->user) && $employee->user->avatar != 'avatar.png')
                            <img id="blah" width="100" style="border-radius: 50%" src="{{ $profile . $employee->user->avatar }}" />
                        @else
                            <div class="img-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center fs-5" style="width: 64px; height: 64px;">{{ $initial }}</div>
                        @endif

                        <h4 class="mt-2 text-primary">{{ $employee->name ?? 'N/A' }}</h4>
                        <small>{{ $employee->user->email ?? 'N/A' }}</small>
                        <div><small>{{ $employee->department->name ?? 'N/A' }}</small></div>
                        <small>{{ $employee->designation->name ?? 'N/A' }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Display Other Managers' Cards and Their Teams -->
    @foreach ($employees as $otherManagerId => $otherEmployees)
        @if ($otherManagerId != $managerId)
            <hr class="mt-4">
            <h6 class="text-primary mb-3"><i class="ti ti-users"></i> Manager {{ $otherEmployees->first()->manager->name ?? 'Unknown' }}'s Team</h6>
            
            <div class="row">
                <!-- Manager Card -->
                @if ($otherEmployees->first()->manager ?? false)
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xxl-2">
                        <div class="card h-100 bg-light border-primary">
                            <div class="card-body text-center">
                                @php
                                    $manager = $otherEmployees->first()->manager;
                                    $profile = \App\Models\Utility::get_file('uploads/avatar/');
                                    $initial = strtoupper(substr($manager->name ?? 'N/A', 0, 2));
                                @endphp
                                <div class="badge bg-primary rounded-pill mb-2 p-2">Manager</div>
                                
                                <!-- Manager Profile Image -->
                                @if (!empty($manager->user) && $manager->user->avatar != 'avatar.png')
                                    <img id="blah" width="100" style="border-radius: 50%" src="{{ $profile . $manager->user->avatar }}" />
                                @else
                                    <div class="img-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center fs-5" style="width: 64px; height: 64px;">{{ $initial }}</div>
                                @endif

                                <h4 class="mt-2 text-primary">{{ $manager->name ?? 'N/A' }}</h4>
                                <small>{{ $manager->user->email ?? 'N/A' }}</small>
                                <div><small>{{ $manager->department->name ?? 'N/A' }}</small></div>
                                <small>{{ $manager->designation->name ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Team Members Under Other Managers -->
                @foreach ($otherEmployees as $employee)
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xxl-2">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                @php
                                    $profile = \App\Models\Utility::get_file('uploads/avatar/');
                                    $initial = strtoupper(substr($employee->name ?? 'N/A', 0, 2));
                                @endphp
                                
                                <!-- Employee Profile Image -->
                                @if (!empty($employee->user) && $employee->user->avatar != 'avatar.png')
                                    <img id="blah" width="100" style="border-radius: 50%" src="{{ $profile . $employee->user->avatar }}" />
                                @else
                                    <div class="img-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center fs-5" style="width: 64px; height: 64px;">{{ $initial }}</div>
                                @endif

                                <h4 class="mt-2 text-primary">{{ $employee->name ?? 'N/A' }}</h4>
                                <small>{{ $employee->user->email ?? 'N/A' }}</small>
                                <div><small>{{ $employee->department->name ?? 'N/A' }}</small></div>
                                <small>{{ $employee->designation->name ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach
</div>
@endsection
