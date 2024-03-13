@extends('layouts.admin')

@section('page-title')
  {{ __("Config Word Count") }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
    <li class="breadcrumb-item">{{ __("Config Word Count") }}</li>
@endsection

@section('content')
        <div class="row">
            <div class="card">
                <div class="card-body table-border-style">

                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Input Title')}}</th>
                                <th>{{__('Words Limit')}}</th>
                                <th width="200px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody >
                            @foreach ($jobWordCounts as $wordCount)
                                <tr>
                                    <td>{{ $wordCount->title }}</td>
                                    <td>{{ $wordCount->limit }}</td>
                                    <td class="Action">
                                        <span>
                                            @can('Edit Word Count')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ route('config-word-count.edit', $wordCount->id) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Word Count') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
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
