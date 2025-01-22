<x-admin>
    <h1>Create Job</h1>
<form action="{{ route('admin.jobs.store') }}" method="POST">
    @csrf
    <input type="text" name="job_title" placeholder="Job Title" required>
    <select name="company_id" required>
        @foreach($companies as $company)
            <option value="{{ $company->id }}">{{ $company->name }}</option>
        @endforeach
    </select>
    <select name="job_type" required>
        @foreach($jobTypes as $jobType)
            <option value="{{ $jobType }}">{{ $jobType }}</option>
        @endforeach
    </select>
    <textarea name="description" placeholder="Job Description" required></textarea>
    <input type="text" name="job_salary" placeholder="Job Salary" required>
    <button type="submit">Create</button>
</form>
</x-admin>