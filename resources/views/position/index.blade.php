@extends('layouts.admin')

@section('page-title')
{{ __("Manage Positions") }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
<li class="breadcrumb-item">{{ __("Manage Positions") }}</li>
@endsection

@section('action-button')
@can('Create Position')
<a href="#" data-url="{{ route('position.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Position') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>
@endcan
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>{{__('Position title')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Job level')}}</th>
                            <th>{{__('Department')}}</th>
                            <th>{{__('Branch')}}</th>
                            <th>{{__('Added By')}}</th>
                            <th width="200px">{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($positions as $position)
                        <tr>
                            <td>{{ $position->title }}</td>
                            <td>{{ $position->status }}</td>
                            <td>{{ $position->job_level }}</td>
                            <td>{{ $position->departments->name }}</td>
                            <td>{{ $position->branches->name }}</td>
                            <td>{{ $position->createdBy->name }}</td>
                            <td class="Action">
                                <span>
                                    @can('Edit Position')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ URL::to('position/' . $position->id . '/edit') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Position') }}" data-bs-original-title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan

                                    @can('Delete Position')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['position.destroy', $position->id], 'id' => 'delete-form-' . $position->id]) !!}
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                        </form>
                                    </div>
                                    @endcan
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        {!! $chart->container() !!}

        </div>
    </div>
</div>
@endsection

<script src="{{ $chart->cdn() }}"></script>

{{ $chart->script() }}