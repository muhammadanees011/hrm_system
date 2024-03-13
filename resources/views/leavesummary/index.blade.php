@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Leave Summary') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('leavesummary.employees') }}">{{ __('Summaries') }}</a></li>
    <li class="breadcrumb-item">{{ __('Leave Summary') }}</li>
@endsection


@section('action-button')
        <a href="#" data-url="{{ route('carryover.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Retirement') }}" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('CarryOver Request') }}">
            CarryOver Request
        </a>
    @can('Create Retirement')
        <a href="#" data-url="{{ route('leavesummary.create',$employee_id) }}" data-ajax-popup="true"
            data-title="{{ __('Create New Retirement') }}" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Leave Type') }}</th>
                                <th>{{ __('Entitled') }}</th>
                                <th>{{ __('Taken') }}</th>
                                <th>{{ __('Pending') }}</th>
                                <th>{{ __('CarriedOver') }}</th>
                                <th>{{ __('Balance') }}</th>
                                @if (Gate::check('Edit Termination') || Gate::check('Delete Termination'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                          

                            @foreach ($leavesummaries as $leavesummary)
                            <tr>
                            <td>{{ !empty($leavesummary->leaveType) ? $leavesummary->leaveType->title : '' }}
                            </td>
                            <td>{{ $leavesummary->entitled }} days</td>
                            <td>{{ $leavesummary->taken }} days</td>
                            <td>{{ $leavesummary->pending }} days</td>
                            <td>{{ $leavesummary->carried_over }} days</td>
                            <td>{{ $leavesummary->balance }} days</td>
                                <td class="Action">
                                    @if (Gate::check('Edit Termination') || Gate::check('Delete Termination'))
                                        <span>
                                            @can('Edit Retirement')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                        data-url="{{ URL::to('retirement/' . $leavesummary->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Edit Leave Summary') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Retirement')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'GET', 'route' => ['leavesummary.destroy', $leavesummary->id,$employee_id], 'id' => 'delete-form-' . $leavesummary->id]) !!}
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
