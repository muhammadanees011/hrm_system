
@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Leave') }}
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Leave ') }}</li>
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
                                @if (\Auth::user()->type != 'employee')
                                    <th>{{ __('Employee') }}</th>
                                @endif
                                <th>{{ __('Applied On') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Total Days') }}</th>
                                <th>{{ __('Duration') }}</th>
                                <th>{{ __('Leave Reason') }}</th>
                                <th>{{ __('Leave Hours') }}</th>
                                <th>{{ __('Paid') }}</th>
                                <th>{{ __('status') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $leave)
                                <tr>
                                    @if (\Auth::user()->type != 'employee')
                                        <td>{{ !empty($leave->employee_id) ? $leave->employees->name : '' }}
                                        </td>
                                    @endif              
                                    <td>{{ \Auth::user()->dateFormat($leave->applied_on) }}</td>
                                    <td>
                                        <strong>Start Date: </strong>{{\Auth::user()->dateFormat($leave->start_date)}} <br />
                                        <strong>End Date: </strong>{{\Auth::user()->dateFormat($leave->end_date)}}
                                    </td>
                                   
                                    <td>{{ $leave->total_leave_days }}</td>
                                    <td>{{ ucfirst(str_replace('_',' ', $leave->leave_duration)) }}</td>
                                    <td>{{ $leave->leave_reason }}</td>
                                    <td>
                                        @if($leave->leave_duration=="half_day")
                                            <strong>Start Time: </strong>{{\Auth::user()->timeFormat($leave->start_time)}} <br />
                                            <strong>End Time: </strong>{{\Auth::user()->timeFormat($leave->end_time)}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($leave->is_paid_leave)
                                            <div class="badge bg-warning p-2 px-3 rounded">Yes</div>
                                        @elseif(!$leave->is_paid_leave)
                                            <div class="badge bg-success p-2 px-3 rounded">No</div>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($leave->status == 'Pending')
                                            <div class="badge bg-warning p-2 px-3 rounded">{{ $leave->status }}</div>
                                        @elseif($leave->status == 'Approved')
                                            <div class="badge bg-success p-2 px-3 rounded">{{ $leave->status }}</div>
                                        @elseif($leave->status == "Reject")
                                            <div class="badge bg-danger p-2 px-3 rounded">{{ $leave->status }}</div>
                                        @endif
                                    </td>

                                    <td class="Action">
                                        <span>
                                            {{-- @if (\Auth::user()->type == 'employee')
                                                @if ($leave->status == 'Pending')
                                                    @can('Edit Leave')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                data-size="lg"
                                                                data-url="{{ URL::to('leave/' . $leave->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Leave') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                @endif
                                            @else
                                                <div class="action-btn bg-success ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                        data-url="{{ URL::to('leave/' . $leave->id . '/action') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Leave Action') }}"
                                                        data-bs-original-title="{{ __('Manage Leave') }}">
                                                        <i class="ti ti-caret-right text-white"></i>
                                                    </a>
                                                </div>
                                                @can('Edit Leave')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                            data-url="{{ URL::to('leave/' . $leave->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Leave') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                            @endif

                                            @can('Delete Leave')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['leave.destroy', $leave->id], 'id' => 'delete-form-' . $leave->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            @endcan --}}

                                            <div class="action-btn bg-success ms-2">
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                    data-size="lg"
                                                    data-url="{{ URL::to('leave/' . $leave->id . '/action') }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                    title="" data-title="{{ __('Leave Action') }}"
                                                    data-bs-original-title="{{ __('Manage Leave') }}">
                                                    <i class="ti ti-caret-right text-white"></i>
                                                </a>
                                            </div>
                                            @can('Edit Leave')
                                                @if (\Auth::user()->type != 'employee' || (\Auth::user()->type == 'employee' && strtolower($leave->status)=="pending"))
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-size="lg"
                                                            data-url="{{ URL::to('leave/' . $leave->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Leave') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endcan
                                            @can('Delete Leave')
                                                @if (\Auth::user()->type != 'employee' || (\Auth::user()->type == 'employee' && strtolower($leave->status)=="pending"))
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['leave.destroy', $leave->id],
                                                            'id' => 'delete-form-' . $leave->id,
                                                        ]) !!}
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endcan
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

