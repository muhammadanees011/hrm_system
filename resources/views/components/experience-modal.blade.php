<x-modal id="experienceModal" title="Add Education">
    <!-- Modal Body Content: Education Form -->
    <form action="{{ $formAction }}" method="POST">
        @csrf
        <input type="hidden" name="employee_id" value="{{ $employeesId }}">
        <div class="mb-3">
            <label for="companyName" class="form-label">Company Name</label>
            <input type="text" class="form-control" id="companyName" name="company_name" value="{{ old('company_name') }}" required>
        </div>
        <div class="mb-3">
            <label for="jobTitle" class="form-label">Job Title</label>
            <input type="text" class="form-control" id="jobTitle" name="job_title" value="{{ old('job_title') }}" required>
        </div>
        <div class="mb-3">
            <label for="startDatea" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="startDatea" name="start_date" value="{{ old('start_date') }}" required>
        </div>

        <div class="mb-3">
            <label for="endDatea" class="form-label">End Date</label>
            <input type="date" class="form-control" id="endDatea" name="end_date" value="{{ old('end_date') }}" required>
        </div>

        <div class="mb-3">
            <label for="locationExperience" class="form-label">Location</label>
            <input class="form-control" id="locationExperience" name="location" placeholder='City/Country Name' required value="{{ old('location') }}"/>
        </div>
        <div class="mb-3">
            <label for="jobDescription" class="form-label">Description</label>
            <textarea class="form-control" id="jpbDescription" name="description" rows="3" required>{{ old('description') }}</textarea>
        </div>


        <button type="submit" class="btn btn-primary">Save Experience</button>
    </form>
</x-modal>
