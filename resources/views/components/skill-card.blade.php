<div class="card shadow-0">
    <div class="card-body">
        <h6>{{ $skill->skill_name }}</h6>
        <p class="text-secondary">{{ $skill->skill_description }}</p>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-warning rounded-circle">
                <i class="ti ti-pencil"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger rounded-circle">
                <i class="ti ti-trash"></i>
            </button>
        </div>
    </div>
</div>
