@extends('layouts.admin')

@section('page-title')
{{ __("Manage Cases") }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
<li class="breadcrumb-item">{{ __("Manage Cases") }}</li>
@endsection

@section('action-button')
@can('Create Case')
<a href="#" data-url="{{ route('case.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Case') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
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
                            <th>{{__('Case ID')}}</th>
                            <th>{{__('Case title')}}</th>
                            <th>{{__('Category')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Filed By')}}</th>
                            <th width="200px">{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cases as $case)
                        <tr>
                            <td>{{$case->uuid}}</td>
                            <td>{{$case->title}}</td>
                            <td>{{$case->category->title}}</td>
                            <td>
                                @if($case->status == 'Investigating')
                                <span class="badge bg-primary p-2 px-3 rounded status-badge w-auto">Investigating</span>
                                @elseif($case->status == 'Closed')
                                <span class="badge bg-danger p-2 px-3 rounded status-badge w-auto">Closed</span>
                                @else
                                <span class="badge bg-info p-2 px-3 rounded status-badge w-auto">New Case</span>
                                @endIf
                            </td>
                            <td>
                                @if($case->is_private)
                                @if($case->created_by == auth()->id())
                                <span class="badge bg-primary p-2 px-3 rounded status-badge w-auto">Your Case</span>
                                @else
                                <span class="badge bg-danger p-2 px-3 rounded status-badge w-auto">Private Case</span>
                                @endif
                                @else
                                {{$case->createdBy->name}}
                                @endif
                            </td>
                            <td class="Action">
                                <span>
                                    @if(($case->created_by == auth()->id() && count($case->discussions) > 0) || $case->created_by != auth()->id())
                                    <div class="action-btn bg-primary ms-2">
                                        <a href="{{ route('case-discussion.index', ['id' => $case->uuid]) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-title="{{ __('Discussion') }}" data-bs-original-title="{{ __('Discussion') }}">
                                            <i class="ti ti-messages text-white"></i>
                                        </a>
                                    </div>
                                    @endif

                                    @if($case->representative == auth()->id() && $case->status != 'Closed')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="{{ route('case.closed', $case->id) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-title="{{ __('Closed') }}" data-bs-original-title="{{ __('Closed') }}">
                                            <i class="ti ti-star text-white"></i>
                                        </a>
                                    </div>
                                    @endif

                                    @if($case->created_by == \Auth::user()->id)
                                    @can('Edit Case')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ URL::to('case/' . $case->id . '/edit') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Case') }}" data-bs-original-title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('Delete Case')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['case.destroy', $case->id], 'id' => 'delete-form-' . $case->id]) !!}
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                        </form>
                                    </div>
                                    @endcan
                                    @endif
                                </span>
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