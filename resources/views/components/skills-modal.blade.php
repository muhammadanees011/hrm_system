
<x-modal id="skillsModal" title="Add Skill">
    <!-- Modal Body Content: Education Form -->
    <form action="{{ $formAction }}" method="POST">
        @csrf

        <input type="hidden" name="employee_id" value="{{ $employeesId }}">
        <div class="mb-3">
            <label for="skillName" class="form-label">Skill Name</label>
            <input type="text" class="form-control" id="skillName" name="skill_name" value="{{ old('skill_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="skillDescription" class="form-label">Skill Description</label>
            <textarea class="form-control" id="skillDescription" name="skill_description" rows="3" required>{{ old('skill_description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Skill</button>
    </form>
</x-modal>
