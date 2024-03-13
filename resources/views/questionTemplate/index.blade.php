@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Custom Question for interview') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Questions Template') }}</li>
@endsection

@section('action-button')
        @can('Create Custom Question')

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

                            <tr>
                                <td>Qualification</td>
                                <td class="Action">
                                    <span>



                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE'] ) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                    aria-label="Delete"><i
                                                        class="ti ti-trash text-white text-white"></i></a>
                                                </form>
                                            </div>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Extra Skills</td>
                                <td class="Action">
                                    <span>



                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE'] ) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                    aria-label="Delete"><i
                                                        class="ti ti-trash text-white text-white"></i></a>
                                                </form>
                                            </div>
                                    </span>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

