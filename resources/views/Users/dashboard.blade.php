<x-usersdashboard>
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">My Jobs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalJobs }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-briefcase text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">My Companies</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalCompanies }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-green-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('User.job.jobs') }}" class="flex items-center gap-3 bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 rounded-xl p-4 transition-colors group">
            <div class="w-9 h-9 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-briefcase text-blue-600 text-sm"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Manage My Jobs</span>
            <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto group-hover:text-blue-500"></i>
        </a>
        <a href="{{ route('User.company.companies') }}" class="flex items-center gap-3 bg-white border border-gray-200 hover:border-green-300 hover:bg-green-50 rounded-xl p-4 transition-colors group">
            <div class="w-9 h-9 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-building text-green-600 text-sm"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Manage My Companies</span>
            <i class="fas fa-chevron-right text-xs text-gray-400 ml-auto group-hover:text-green-500"></i>
        </a>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Jobs & Companies Overview</h3>
        <canvas id="jobsCompaniesChart" height="100"></canvas>
    </div>
</x-usersdashboard>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('jobsCompaniesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jobs', 'Companies'],
            datasets: [{
                label: 'Total Count',
                data: [{{ $totalJobs }}, {{ $totalCompanies }}],
                backgroundColor: ['rgba(59,130,246,0.15)', 'rgba(34,197,94,0.15)'],
                borderColor: ['rgb(59,130,246)', 'rgb(34,197,94)'],
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
</script>
