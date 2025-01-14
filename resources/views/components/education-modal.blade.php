<!-- resources/views/components/education-modal.blade.php -->

<x-modal id="educationModal" title="Add Education">
    <!-- Modal Body Content: Education Form -->
    <form action="{{ $formAction }}" method="POST">
        @csrf

        <input type="hidden" name="employee_id" value="{{ $employeesId }}">
        <div class="mb-3">
            <label for="institutionName" class="form-label">Institution Name</label>
            <input type="text" class="form-control" id="institutionName" name="institution_name" value="{{ old('institution_name') }}" required>
        </div>
        <div class="mb-3">
            <label for="degreeName" class="form-label">Degree Name</label>
            <input type="text" class="form-control" id="degreeName" name="degree_name" value="{{ old('degree_name') }}" required>
        </div>
        <div class="mb-3">
            <label for="startDate" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="startDate" name="start_date" value="{{ old('start_date') }}" required>
        </div>

        <div class="mb-3">
            <label for="endDate" class="form-label">End Date</label>
            <input type="date" class="form-control" id="endDate" name="end_date" value="{{ old('end_date') }}" required>
        </div>
        <div class="mb-3">
            <label for="totalMarks" class="form-label">Total Marks or CGPA</label>
            <input type="text" class="form-control" id="totalMarks" name="total_marks" value="{{ old('total_marks') }}" required>
        </div>
        <div class="mb-1">
            <label for="obtainedMarks" class="form-label
            ">Obtained Marks</label>
            <input type="text" class="form-control" id="obtainedMarks" name="obtained_marks" value="{{ old('obtained_marks') }}" required>
        </div>

        <div class="mb-3">
            <label for="locationEducation" class="form-label">Location</label>
            <input class="form-control" id="locationEducation" name="location" placeholder='City/Country Name' required value="{{ old('location') }}"/>
        </div>
        <div class="mb-3">
            <label for="courseDescription" class="form-label">Description</label>
            <textarea class="form-control" id="courseDescription" name="description" rows="3" required>{{ old('description') }}</textarea>
        </div>


        <button type="submit" class="btn btn-primary">Save Education</button>
    </form>
</x-modal>
