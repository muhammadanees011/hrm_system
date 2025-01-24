@extends('layouts.admin')

@section('page-title')
{{ __("Manage Benefits Schemes") }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
<li class="breadcrumb-item">{{ __("Schemes") }}</li>
@endsection

@section('action-button')
@can('Create Scheme')
<a href="#" data-url="{{ route('benefits-schemes.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Scheme') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
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
                            <th>{{__('Scheme Name')}}</th>
                            <th>{{__('Contribution Percentage')}}</th>
                            <th width="200px">{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schemes as $scheme)
                        <tr>
                            <td>{{ $scheme->scheme_name }}</td>
                            <td>{{ $scheme->contribution_percentage }}%</td>
                            <td class="Action">
                                <span>
                                    @can('Edit Scheme')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ URL::to('benefits-schemes/' . $scheme->id . '/edit') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Scheme') }}" data-bs-original-title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan

                                    @can('Delete Scheme')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['benefits-schemes.destroy', $scheme->id], 'id' => 'delete-form-' . $scheme->id]) !!}
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
        </div>
    </div>
</div>
@endsection