<div class="card shadow-0">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="me-auto ">
                <h4 class="mb-0">{{ $experience->job_title }}</h4>
                <div class="d-flex align-items-center gap-2 text-warning">
                    <span class="badge text-bg-warning">{{ $experience->company_name }}</span>
                    <b>|</b>
                    <span class="badge text-bg-warning">{{ $experience->start_date->format('Y') }} - {{ $experience->end_date ? $experience->end_date->format('Y') : 'Present' }}</span>
                    <b>|</b>
                    <span class="badge text-bg-warning">{{ $experience->location }}</span>
                </div>
            </div>
            <button class="btn btn-sm btn-warning me-1"><i class="ti ti-pencil"></i></button>
            <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
        </div>
        <p class="text-secondary">{{ $experience->description }}</p>
    </div>
</div>
