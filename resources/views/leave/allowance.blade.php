
@extends('layouts.admin')

@section('page-title')
    {{ __('Leave Allowance') }}
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Leave Allowance') }}</li>
@endsection

@section('action-button')
    <a href="{{ route('leave.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Export') }}">
        <i class="ti ti-file-export"></i>
    </a>

    <a href="{{ route('leave.calender') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Calendar View') }}">
        <i class="ti ti-calendar"></i>
    </a>

    @can('Create Leave')
        <a href="#" data-url="{{ route('leave.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Leave') }}"
            data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
        <a href="#" data-url="{{ route('leave.view-allowanace') }}" data-ajax-popup="true" data-title="{{ __('Create New Leave Allowance') }}"
            data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            Create Allowance Request
        </a>
    @endcan
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
        <div class="employees-nav me-1 mb-2">
            <div class="nav-titles">
                <div class="dropdown">
                <button class="dropbtn">Manage Leaves &#9660;</button>
                <div class="dropdown-content">
                    <a href="{{ route('holidayplanner.index') }}">{{ __("Holiday Planner") }} </a>
                    <a href="{{ route('leaveentitlement.index') }}">{{ __("Leave Entitlement Report") }} </a>
                    <a href="{{ route('carryover.index') }}">{{ __('Leave CarryOver Request') }}</a>
                    <a href="{{ route('leave.team') }}">{{ __('Team Time Off') }}</a>
                </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Request By') }}</th>
                                <th>{{ __('Applied On') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        <tr>
                                <td>Request 1</td>
                                <td>Rafay</td>
                                <td>21 Jan 2025</td>
                                <td>$500</td>
                                <td width="200px">
                                    <button class="btn btn-primary btn-sm" ><i class="ti ti-check"></i> Approve</button>
                                    <button class="btn btn-danger btn-sm" ><i class="ti ti-check"></i> Reject</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

@push('script-page')
    <script>
        $(document).on('change', '#employee_id', function() {
            var employee_id = $(this).val();

            $.ajax({
                url: '{{ route('leave.jsoncount') }}',
                type: 'POST',
                data: {
                    "employee_id": employee_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    var oldval = $('#leave_type_id').val();
                    $('#leave_type_id').empty();
                    $('#leave_type_id').append(
                        '<option value="">{{ __('Select Leave Type') }}</option>');

                    $.each(data, function(key, value) {

                        if (value.total_leave == value.days) {
                            $('#leave_type_id').append('<option value="' + value.id +
                                '" disabled>' + value.title + '&nbsp(' + value.total_leave +
                                '/' + value.days + ')</option>');
                        } else {
                            $('#leave_type_id').append('<option value="' + value.id + '">' +
                                value.title + '&nbsp(' + value.total_leave + '/' + value
                                .days + ')</option>');
                        }
                        if (oldval) {
                            if (oldval == value.id) {
                                $("#leave_type_id option[value=" + oldval + "]").attr(
                                    "selected", "selected");
                            }
                        }
                    });

                }
            });
        });
    </script>
@endpush

