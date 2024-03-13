{{ Form::open(['url' => 'carryover/changeaction/', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Employee') }}</th>
                    <td>{{ !empty($carryover->employees) ? $carryover->employees->name : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Leave Type ') }}</th>
                    <td>{{ !empty($carryover->leaveType) ? $carryover->leaveType->title : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Appplied On') }}</th>
                    <td>{{ \Auth::user()->dateFormat($carryover->created_at) }}</td>
                </tr>
                <tr>
                    <th>{{ __('No. of leaves') }}</th>
                    <td>{{ $carryover->leaves_count }}</td>
                </tr>
                <tr>
                    <th>{{ __('Status') }}</th>
                    <td>{{ !empty($carryover->status) ? $carryover->status : '' }}</td>
                </tr>
                <input type="hidden" value="{{ $carryover->id }}" name="leave_id">
            </table>
        </div>
    </div>
</div>

@if (Auth::user()->type == 'company' || Auth::user()->type == 'hr')
<div class="modal-footer">
    <input type="submit" value="{{ __('Approved') }}" class="btn btn-success rounded" name="status">
    <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
</div>
@endif


{{ Form::close() }}
