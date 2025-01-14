<div class="card shadow-0">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="mb-0">{{ $education->institution_name }}</h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-bg-secondary">{{ $education->degree_name }}</span>
                    <b>|</b>
                    <span class="badge text-bg-secondary">{{ $education->start_date->format('Y') }} - {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}</span>
                    <b>|</b>
                    <span class="badge text-bg-secondary">{{ $education->location }}</span>
                </div>
                <div class="text-success">{{ $education->total_marks }} / {{ $education->obtained_marks }}</div>
            </div>
            <a href="{{ route('employee-education.edit', $education) }}" class="btn btn-sm btn-warning me-1"><i class="ti ti-pencil"></i></a>
            <form action="{{ route('employee-education.destroy', $education) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
            </form>
        </div>
        <p class="text-secondary">{{ $education->description }}</p>
    </div>
</div>
