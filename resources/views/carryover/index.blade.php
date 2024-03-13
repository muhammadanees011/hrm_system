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
