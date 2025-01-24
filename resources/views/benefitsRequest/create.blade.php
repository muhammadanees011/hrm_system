@extends('layouts.admin')

@section('page-title')
    {{ __('Create Benefits Request') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('benefitsRequest.index') }}">{{ __('Benefits Requests') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Benefits Request') }}</li>
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body">
                <form action="{{ route('benefitsRequest.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="benefit_id">{{ __('Benefit') }}</label>
                        <select name="benefit_id" id="benefit_id" class="form-control">
                            @foreach($benefits as $benefit)
                                <option value="{{ $benefit->id }}">{{ $benefit->scheme_name }}- {{ $benefit->contribution_percentage}}%</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reason">{{ __('Reason') }}</label>
                        <textarea name="reason" id="reason" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection