@php
    $chatgpt = Utility::getValByName('enable_chatgpt');
@endphp

{{ Form::model($loanrequest, ['route' => ['loanrequest.update', $loanrequest->id], 'method' => 'PUT']) }}
<div class="modal-body">

    @if ($chatgpt == 'on')
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['loan']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i>{{ __(' Generate With AI') }}
        </a>
    </div>
    @endif

    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Enter Title']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('loan_option', __('Loan Options*'), ['class' => 'col-form-label']) }}
                {{ Form::select('loan_option', $loan_options, null, ['class' => 'form-control select2','required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('type', __('Type'), ['class' => 'col-form-label']) }}
                {{ Form::select('type', $loans, null, ['class' => 'form-control select2 amount_type','required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('amount', __('Loan Amount'), ['class' => 'col-form-label amount_label']) }}
                {{ Form::number('amount', null, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Enter Amount']) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('reason', __('Reason'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('reason', null, ['class' => 'form-control ', 'required' => 'required','rows' => 3]) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}
            <select name="status" class="form-control  select2" id="status">
                <option value="Pending"  @if($loanrequest->status=='Pending') selected @endif>{{ __('Pending') }}</option>
                <option value="Approved" @if($loanrequest->status=='Approved') selected @endif>{{ __('Approved') }}</option>
                <option value="Rejected" @if($loanrequest->status=='Rejected') selected @endif>{{ __('Rejected') }}</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
