<x-admin>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        <div class="bg-white p-6 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300">
            <p class="text-lg text-gray-700">Total Jobs</p>
            <p class="text-3xl font-semibold text-gray-900">{{ $jobs }}</p>
            <div class="mt-4">
                <i class="fas fa-briefcase text-4xl text-blue-500"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300">
            <p class="text-lg text-gray-700">Total Companies</p>
            <p class="text-3xl font-semibold text-gray-900">{{ $companies }}</p>
            <div class="mt-4">
                <i class="fas fa-building text-4xl text-green-500"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300">
            <p class="text-lg text-gray-700">Total Users</p>
            <p class="text-3xl font-semibold text-gray-900">{{ $users }}</p>
            <div class="mt-4">
                <i class="fas fa-users text-4xl text-orange-500"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <a href="{{ route('admin.job.jobs') }}" class="bg-gradient-to-r from-green-400 to-green-600 text-white py-3 px-6 rounded-md shadow-lg hover:from-green-500 hover:to-green-700 transition-all duration-300 text-center flex items-center justify-center">
            <i class="fas fa-briefcase mr-2"></i> Manage Jobs
        </a>

        <a href="{{ route('admin.company.companies') }}" class="bg-gradient-to-r from-blue-400 to-blue-600 text-white py-3 px-6 rounded-md shadow-lg hover:from-blue-500 hover:to-blue-700 transition-all duration-300 text-center flex items-center justify-center">
            <i class="fas fa-building mr-2"></i> Manage Companies
        </a>

        <a href="{{ route('users.index') }}" class="bg-gradient-to-r from-sky-400 to-sky-600 text-white py-3 px-6 rounded-md shadow-lg hover:from-sky-500 hover:to-sky-700 transition-all duration-300 text-center flex items-center justify-center">
            <i class="fas fa-users mr-2"></i> Manage Users
        </a>
    </div>

    <div class="mt-12">
        <h2 class="text-2xl font-semibold text-gray-900">Jobs Companies and Users data</h2>
        <div class="bg-white p-6 rounded-lg shadow-lg mt-6">
            <canvas id="jobsCompaniesChart" width="400" height="100"></canvas>
        </div>
    </div>

    <div class="mt-12">
        <h2 class="text-2xl font-semibold text-gray-900">Applications Reports and Contact data</h2>
        <div class="bg-white p-6 rounded-lg shadow-lg mt-6">
            <canvas id="applicationsReportsChart" width="300" height="300"></canvas>
        </div>
    </div>
</x-admin>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx1 = document.getElementById('jobsCompaniesChart').getContext('2d');
    const jobsCompaniesChart = new Chart(ctx1, {
        type: 'bar', 
        data: {
            labels: ['Jobs', 'Companies', 'Users'], 
            datasets: [{
                label: 'Total Count',
                data: [{{ $jobs }}, {{ $companies }}, {{$users}}], 
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)', 
                    'rgba(255, 99, 132, 0.2)', 
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',  
                    'rgba(255, 99, 132, 1)',   
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const ctx2 = document.getElementById('applicationsReportsChart').getContext('2d');
const applicationsReportsChart = new Chart(ctx2, {
    type: 'doughnut', 
    data: {
        labels: ['Applications', 'Reports', 'Contact'],
        datasets: [{
            label: 'Total Count',
            data: [{{ $applications }}, {{ $reports }}, {{ $contact }}],
            backgroundColor: [
                'rgba(75, 192, 192, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(255, 206, 86, 0.6)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '50%',
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>
