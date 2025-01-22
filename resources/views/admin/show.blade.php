<x-layout>
    <h1>Jobs at {{ $company->name }}</h1>

<table>
    <thead>
        <tr>
            <th>Job Title</th>
            <th>Job Type</th>
            <th>Salary</th>
        </tr>
    </thead>
    <tbody>
        @foreach($company->jobs as $job)
            <tr>
                <td>{{ $job->job_title }}</td>
                <td>{{ $job->job_type }}</td>
                <td>{{ $job->job_salary }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</x-layout>