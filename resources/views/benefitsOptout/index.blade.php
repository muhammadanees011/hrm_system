@extends('layouts.admin')

@section('page-title')
{{ __("Manage Benefits Opt-out") }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
<li class="breadcrumb-item">{{ __("Opt-outs") }}</li>
@endsection

@section('action-button')
@can('Create Pension Optout')
<a href="{{ route('benefits-optout.create') }}" data-title="{{ __('Create Opt-out') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
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
                            <th>{{__('Employee')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Reasons')}}</th>
                            <th width="200px">{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($optouts as $optout)
                        <tr>
                            <td>{{ $optout->employee->name}} </td>
                            <td> {{\Auth::user()->dateFormat($optout->date) }}</td>
                            <td> {{$optout->reasons}} </td>
                            <td class="Action">
                                <span>
                                    @can('Edit Pension Optout')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="{ route('benefits-optout.edit', $optout->id) }" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan

                                    @can('Delete Pension Optout')
                                    <div class="action-btn bg-danger ms-2">
                                        
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