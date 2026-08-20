<x-admin>
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Jobs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $jobs }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-briefcase text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Companies</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $companies }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-green-600 text-lg"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $users }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-orange-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('admin.job.jobs') }}" class="flex items-center gap-3 bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 rounded-xl p-4 transition-colors group">
            <div class="w-9 h-9 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-briefcase text-blue-600 text-sm"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Manage Jobs</span>
            <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto group-hover:text-blue-500"></i>
        </a>
        <a href="{{ route('admin.company.companies') }}" class="flex items-center gap-3 bg-white border border-gray-200 hover:border-green-300 hover:bg-green-50 rounded-xl p-4 transition-colors group">
            <div class="w-9 h-9 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-building text-green-600 text-sm"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Manage Companies</span>
            <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto group-hover:text-green-500"></i>
        </a>
        <a href="{{ route('users.index') }}" class="flex items-center gap-3 bg-white border border-gray-200 hover:border-orange-300 hover:bg-orange-50 rounded-xl p-4 transition-colors group">
            <div class="w-9 h-9 bg-orange-100 group-hover:bg-orange-200 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-users text-orange-500 text-sm"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 group-hover:text-orange-700">Manage Users</span>
            <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto group-hover:text-orange-500"></i>
        </a>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Jobs, Companies & Users Overview</h3>
            <canvas id="jobsCompaniesChart" height="100"></canvas>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Activity Breakdown</h3>
            <canvas id="applicationsReportsChart" height="220"></canvas>
        </div>
    </div>
</x-admin>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx1 = document.getElementById('jobsCompaniesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Jobs', 'Companies', 'Users'],
            datasets: [{
                label: 'Total Count',
                data: [{{ $jobs }}, {{ $companies }}, {{ $users }}],
                backgroundColor: ['rgba(59,130,246,0.15)', 'rgba(34,197,94,0.15)', 'rgba(249,115,22,0.15)'],
                borderColor: ['rgb(59,130,246)', 'rgb(34,197,94)', 'rgb(249,115,22)'],
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }
        }
    });

    const ctx2 = document.getElementById('applicationsReportsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Applications', 'Reports', 'Contacts'],
            datasets: [{
                data: [{{ $applications }}, {{ $reports }}, {{ $contact }}],
                backgroundColor: ['rgba(59,130,246,0.8)', 'rgba(239,68,68,0.8)', 'rgba(250,204,21,0.8)'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } } }
        }
    });
</script>
