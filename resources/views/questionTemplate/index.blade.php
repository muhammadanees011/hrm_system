@extends('layouts.admin')

@section('page-title')
{{ __('Manage Question Template') }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Questions Template') }}</li>
@endsection

@section('action-button')
@can('Create Question Template')

<a href="{{ route('question-template.create') }}" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Question Template') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>


@endcan
@endsection

@section('content')

<div class="col-xl-12">
    <div class="card">
        <div class="card-header card-body table-border-style">
            {{-- <h5></h5> --}}
            <div class="table-responsive">
                <table class="table" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th>{{ __('Template') }}</th>
                            <th width="200px">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $question)
                        <tr>
                            <td>{{$question->title}}</td>
                            <td class="Action">
                                <span>
                                    @can('Manage Branching')
                                    <div class="action-btn bg-primary ms-2">
                                        <a href="{{ route('question-template.branching', $question->id) }}" class="mx-3 btn btn-sm  align-items-center" data-url="" data-ajax-popup="true" data-title="{{ __('Manage Branching') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Manage Branching') }}">
                                            <i class="ti ti-vector text-white"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('Edit Question Template')
                                    <div class="action-btn bg-info ms-2">
                                        <a href="{{ route('question-template.edit', $question->id) }}" class="mx-3 btn btn-sm  align-items-center" data-url="" data-ajax-popup="true" data-title="{{ __('Edit') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('Delete Question Template')
                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['question-template.destroy', $question->id], 'id' => 'delete-form-' . $question->id] ) !!}
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