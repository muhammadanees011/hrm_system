@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Leave CarryOver') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Leave CarryOver') }}</li>
@endsection


@section('action-button')
    @can('Create Retirement')
        <a href="#" data-url="{{ route('carryover.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Carryover') }}" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
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
                    <a href="{{ route('leave.index') }}">{{ __("Leave Request") }} </a>
                    <a href="{{ route('leaveentitlement.index') }}">{{ __("Leave Entitlement Report") }} </a>
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
                            <th>{{ __('Employee') }}</th>
                                <th>{{ __('Leave Type') }}</th>
                                <th>{{ __('Leave Count') }}</th>
                                <th>{{ __('status') }}</th>
                                @if (Gate::check('Edit Termination') || Gate::check('Delete Termination'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                          

                            @foreach ($carryrequests as $carryover)
                            <tr>
                            <td>{{ !empty($carryover->employees) ? $carryover->employees->name : '' }}</td>
                            <td>{{ !empty($carryover->leaveType) ? $carryover->leaveType->title : '' }}</td>
                            <td>{{ $carryover->leaves_count }} days</td>
                            <td>
                                @if ($carryover->status == 'pending')
                                    <div class="badge bg-warning p-2 px-3 rounded">{{ $carryover->status }}</div>
                                @elseif($carryover->status == 'accepted')
                                    <div class="badge bg-success p-2 px-3 rounded">{{ $carryover->status }}</div>
                                @elseif($carryover->status == "rejected")
                                    <div class="badge bg-danger p-2 px-3 rounded">{{ $carryover->status }}</div>
                                @endif
                            </td>
                                <td class="Action">
                                    @if (Gate::check('Edit Termination') || Gate::check('Delete Termination'))
                                        <span>
                                            @can('Edit Retirement') 
                                            <div class="action-btn bg-success ms-2">
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                    data-url="{{ URL::to('carryover/' . $carryover->id . '/action') }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                    title="" data-title="{{ __('Leave Action') }}"
                                                    data-bs-original-title="{{ __('CarryOver Leave') }}">
                                                    <i class="ti ti-caret-right text-white"></i>
                                                </a>
                                            </div>
                                            @endcan
                                            @can('Edit Retirement')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                        data-url="{{ URL::to('carryover/' . $carryover->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Edit Leave CarryOver') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Retirement')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['carryover.destroy', $carryover->id], 'id' => 'delete-form-' . $carryover->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            @endcan
                                        </span>
                                    @endif
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
