@extends('layouts.admin')

@section('page-title')
   {{ __('Manage Employee Salary') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Employee Salary') }}</li>
@endsection


@section('content')


<style>
.emp-col {
    display: flex;
    align-items: center; /* Vertical alignment */
}

.emp-col a {
    display: flex;
    align-items: center; /* Vertical alignment */
    text-decoration: none; /* Remove underline from link */
}

.emp-col img {
    height: 40px; /* Adjust the height as needed */
    width: 40px; /* Adjust the width as needed */
    margin-right: 10px; /* Spacing between image and text */
}

.emp-name {
    display: flex;
    flex-direction: column;
}

.emp-name small {
    margin-left: auto; /* Push the employee ID to the right */
}

</style>


    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5></h5>> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <!-- <th>{{ __('Name') }}</th> -->
                                <th>{{ __('Payroll Type') }}</th>
                                <th>{{ __('Salary') }}</th>
                                <th>{{ __('Net Salary') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td class="emp-col">
                                        <a href="{{ route('setsalary.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                                            class="">
                                            <img class="rounded-circle me-4" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 15%;width: 15%" />
                                            <span class="emp-name">
                                            <small class=" ps-1">{{ \Auth::user()->employeeIdFormat($employee->employee_id) }}</small>
                                            {{ $employee->name }}
                                            <!-- <i class="ti ti-arrow-right"></i> -->
                                            </span>
                                        </a>
                                    </td>
                                    <!-- <td>
                                        <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 10%;width: 10%" />
                                        {{ $employee->name }}
                                    </td> -->
                                    <td>{{ $employee->salary_type() }}</td>
                                    <td>{{ \Auth::user()->priceFormat($employee->salary) }}</td>
                                    <td>{{ !empty($employee->get_net_salary()) ? \Auth::user()->priceFormat($employee->get_net_salary()) : '' }}
                                    </td>
                                    <td class="Action">
                                        <span>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{route('setsalary.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                                                    class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip"
                                                    title="" data-bs-original-title="{{ __('View') }}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
