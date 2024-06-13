<div class="modal-body">
    <div class="row py-4">
        <div class="col-md-12">
            <div class="info text-sm">
                <strong>{{ __('Branch') }} : </strong>
                <span>{{ !empty($appraisal->branches) ? $appraisal->branches->name : '' }}</span>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="info text-sm font-style">
                <strong>{{ __('Employee') }} : </strong>
                <span>{{ !empty($appraisal->employees) ? $appraisal->employees->name : '' }}</span>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="info text-sm font-style">
                <strong>{{ __('Appraisal Date') }} : </strong>
                <span>{{ $appraisal->appraisal_date }}</span>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="info text-sm font-style">
                <strong>{{ __('Completion Date') }} : </strong>
                <span>{{ $appraisal->completion_date }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <hr>
            <h6>{{ __('Target') }}</h6>
        </div>
        <div class="col-md-12 mt-3">
            <p class="text-sm">{{ $appraisal->remark }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr>
            <h6>{{ __('Notes') }}</h6>
        </div>
        <div class="col-md-12 mt-3">
            <p class="text-sm">{{ $appraisal->notes }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="info text-sm font-style">
                <strong>{{ __('Notes Date') }} : </strong>
                <span>{{ $appraisal->notes_date }}</span>
            </div>
        </div>
    </div>

</div>
