@extends('layouts.admin')

@section('page-title')
{{ __('Manage Employee') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Employee') }}</li>
@endsection

@section('action-button')

<div class="employees-actions">
 
<div class="employees-nav me-2">
    <div class="nav-titles">
        <div class="dropdown">
        <button class="dropbtn">Documents &#9660;</button>
        <div class="dropdown-content">
            <!-- <a href="{{ route('document-upload.index') }}" target="_blank">Documents </a> -->
            <a href="{{ route('documentdirectories.index') }}">Employee Documents</a>
        </div>
        </div>
    </div>
</div>

<div class="employees-nav me-2">
    <div class="nav-titles">
        <div class="dropdown">
        <button class="dropbtn">Leaves &#9660;</button>
        <div class="dropdown-content">
            <a href="{{ route('leave.index') }}">Leave Request </a>
            <a href="{{ route('carryover.index') }}" >CarryOver Request</a>
            <a href="{{ route('leave.team') }}">Team Time Off</a>
            <a href="{{ route('holiday.index') }}">Holidays</a>
        </div>
        </div>
    </div>
</div>

<div class="employees-nav me-2">
    <div class="nav-titles">
        <div class="dropdown">
        <button class="dropbtn">Payrolls &#9660;</button>
        <div class="dropdown-content">
        <a href="{{ route('setsalary.index') }}">Set Salary </a>
        <a href="{{ route('payslip.index') }}">Payslip </a>
        </div>
        </div>
    </div>
</div>

<div class="employees-nav me-2">
    <div class="nav-titles">
        <div class="dropdown">
        <button class="dropbtn">Performance &#9660;</button>
        <div class="dropdown-content">
            <a href="{{ route('performancecycle.index') }}">{{ __("Manage Performance") }} </a>
            <a href="{{ route('meetingtemplate.index') }}">{{ __("1 on 1's") }} </a>
            <a href="{{ route('employeereviews.index') }}">{{ __('Employee Reviews') }}</a>
            <a href="{{ route('appraisal.index') }}">{{ __('Appraisal') }}</a>
            <a href="{{ route('trainingevent.index') }}">{{ __('Manage Training Events') }}</a>
            <a href="{{ route('trainingeventrequest.index') }}">{{ __('Training Event Requests') }}</a>
            <a href="{{ route('employee.probation.index') }}">{{ __('Probation') }}</a>
            <a href="{{ route('promotion.index') }}">{{ __('Employee Promotion') }}</a>
            <a href="{{ route('warning.index') }}">{{ __('Employee Warnings') }}</a>
        </div>
        </div>
    </div>
</div>

<div>
    <a href="{{ route('employee.export') }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{ __('Export') }}" class="btn btn-sm btn-warning">
        <i class="ti ti-file-export"></i>
    </a>
    <a href="#" data-url="{{ route('employee.file.import') }}" data-ajax-popup="true" data-title="{{ __('Import  employee CSV file') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Import') }}">
        <i class="ti ti-file"></i>
    </a>
    @can('Create Employee')
    <a href="{{ route('employee.create') }}" data-title="{{ __('Create New Employee') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
        <i class="ti ti-plus"></i>
    </a>
    @endcan
</div>

</div>

@endsection

@section('content')


<style>

.employees-actions{
    display:flex;
    justify-content:space-between;
}

/* Dropdown container */
.dropdown {
    position: relative;
    display: inline-block;
  }
  
  /* Dropdown button */
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
  
  /* Dropdown content */
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
  
  /* Links inside the dropdown */
  .dropdown-content a {
    color: black !important;
    padding: 5px 12px;
    text-decoration: none;
    display: block;
  }
  
  /* Change color of links on hover */
  .dropdown-content a:hover {
    background-color: orange;
    color:white !important;
}
  
  /* Show the dropdown menu on hover */
  .dropdown:hover .dropdown-content {display: block;}
  
  /* Change the background color of the dropdown button when the dropdown content is shown */
  .dropdown:hover .dropbtn {color: white;}
  



</style>




<div class="col-xl-12">
    <div class="card">
        <div class="card-header card-body table-border-style">
            {{-- <h5></h5> --}}
            <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Branch') }}</th>
                            <th>{{ __('Manager') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Designation') }}</th>
                            <th>{{ __('Date Of Joining') }}</th>
                            <th>{{ __('Type') }}</th>
                            @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))
                            <th width="200px">{{ __('Action') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                        <tr>
                            <td>
                                <a href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}">
                                    <img class="rounded-circle me-1" src="{{asset( '/assets/images/user/avatar-4.jpg' )}}" alt="{{ env('APP_NAME') }}"  style="height: 15%;width: 15%" />
                                    {{ $employee->name }}
                                    <i class="ti ti-arrow-right"></i>
                                </a>
                            </td>
                            <td>{{ !empty($employee->email) ? $employee->email : '-' }}</td>
                            <td>
                                {{ !empty($employee->branch_id) ? $employee->branch->name : '-' }}
                            </td>
                            <td>
                                {{ !empty($employee->manager_id) ? $employee->manager->name : '-' }}
                            </td>
                            <td>
                                {{ !empty($employee->department_id) ? $employee->department->name : '-' }}
                            </td>
                            <td>
                                {{ !empty($employee->designation_id) ? $employee->designation->name : '-' }}
                            </td>
                            <td>
                                {{ \Auth::user()->dateFormat($employee->company_doj) }}
                            </td>
                            <td>
                                {{ $employee->employee_type }}
                            </td>
                            @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))
                            <td class="Action">
                                @if ($employee->is_active == 1)
                                <span>

                                    @can('Show Personal File')
                                    <div class="action-btn bg-warning ms-2">
                                        <a href="{{ route('employee.personalFile', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('Personal File') }}">
                                            <i class="ti ti-file text-white"></i>
                                        </a>
                                    </div>
                                    @endcan

                                    @can('Edit Employee')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="{{ route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan

                                    @can('Delete Employee')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $employee->id], 'id' => 'delete-form-' . $employee->id]) !!}
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                        </form>
                                    </div>
                                    @endcan
                                </span>
                                @else
                                <i class="ti ti-lock"></i>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


{{-- <div class="row"> --}}
{{-- <div class="col-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee ID') }}</th>
<th>{{ __('Name') }}</th>
<th>{{ __('Email') }}</th>
<th>{{ __('Branch') }}</th>
<th>{{ __('Department') }}</th>
<th>{{ __('Designation') }}</th>
<th>{{ __('Date Of Joining') }}</th>
@if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))
<th>{{ __('Action') }}</th>
@endif
</tr>
</thead>
<tbody>
    @foreach ($employees as $employee)
    <tr>
        <td>
            @can('Show Employee')
            <a href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}">{{ \Auth::user()->employeeIdFormat($employee->employee_id) }}</a>
            @else
            <a href="#">{{ \Auth::user()->employeeIdFormat($employee->employee_id) }}</a>
            @endcan
        </td>
        <td>{{ $employee->name }}</td>
        <td>{{ $employee->email }}</td>
        <td>
            {{ !empty(\Auth::user()->getBranch($employee->branch_id)) ? \Auth::user()->getBranch($employee->branch_id)->name : '' }}
        </td>
        <td>
            {{ !empty(\Auth::user()->getDepartment($employee->department_id)) ? \Auth::user()->getDepartment($employee->department_id)->name : '' }}
        </td>
        <td>
            {{ !empty(\Auth::user()->getDesignation($employee->designation_id)) ? \Auth::user()->getDesignation($employee->designation_id)->name : '' }}
        </td>
        <td>
            {{ \Auth::user()->dateFormat($employee->company_doj) }}
        </td>
        @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))
        <td class="d-flex">
            @if ($employee->is_active == 1)
            @can('Edit Employee')
            <a href="{{ route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}" class="action-btn btn-primary me-1 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Edit') }}"><i class="ti ti-pencil"></i></a>
            @endcan
            @can('Delete Employee')
            {!! Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $employee->id], 'id' => 'delete-form-' . $employee->id]) !!}
            <a href="#!" class="action-btn btn-danger me-1 btn btn-sm d-inline-flex align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Delete') }}">
                <i class="ti ti-trash"></i></a>
            {!! Form::close() !!}
            @endcan
            @else
            <i class="fas fa-lock"></i>
            @endif
        </td>
        @endif
    </tr>
    @endforeach
</tbody>
</table>
</div>
</div>
</div>
</div>
</div> --}}
@endsection