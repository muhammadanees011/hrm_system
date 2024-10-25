@extends('layouts.admin')

@section('page-title')
{{ __('Manage Teams') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Teams') }}</li>
@endsection

@section('action-button')

@can('Create Team')
@endcan

<a href="#" data-url="{{ route('teams.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Team') }}"
    data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>

@endsection

@section('content')
<div class="col-3">
    @include('layouts.hrm_setup')
</div>
<div class="col-9">
    <div class="card">
        <div class="card-body table-border-style">

            <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>{{ __('Team') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th width="200px">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team)
                            <tr>
                                <td>{{ !empty($team->id) ? $team->name : '' }}</td>
                                <td>{{ !empty($team->department_id) ? $team->departments->name : '' }}</td>
                                <td class="Action">
                                    <span>
                                        @can('Edit Team')
                                        @endcan
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                data-url="{{  route('teams.edit', $team->id) }}" data-ajax-popup="true"
                                                data-size="md" data-bs-toggle="tooltip" title=""
                                                data-title="{{ __('Edit Team') }}"
                                                data-bs-original-title="{{ __('Edit') }}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>

                                        @can('Delete Team')
                                        @endcan
                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['teams.destroy', $team->id], 'id' => 'delete-form-' . $team->id]) !!}
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                            </form>
                                        </div>

                                        @can('See Members')
                                        @endcan
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="members"
                                                class="mx-3 btn btn-sm align-items-center bs-pass-para"
                                                data-bs-toggle="tooltip" title="See Members" aria-label="See Members">
                                                <i class="ti ti-users text-white"></i>
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