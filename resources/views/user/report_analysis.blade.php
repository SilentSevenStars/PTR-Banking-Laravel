@extends('layouts.app')

@section('page-content')
@include('layouts.navigation')

<main class="flex-1 p-6 overflow-y-auto">
    <h2 class="text-3xl font-bold mb-8 text-gray-800">Reports & Analysis</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition text-center">
            <h3 class="text-gray-500 text-sm">Total Deposits</h3>
            <p id="totalDeposits" class="text-3xl font-bold text-green-600 mt-2">₱0.00</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition text-center">
            <h3 class="text-gray-500 text-sm">Total Withdrawals</h3>
            <p id="totalWithdrawals" class="text-3xl font-bold text-red-600 mt-2">₱0.00</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition text-center">
            <h3 class="text-gray-500 text-sm">Net Balance</h3>
            <p id="netBalance" class="text-3xl font-bold text-blue-600 mt-2">₱0.00</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">Monthly Deposits vs Withdrawals</h3>
        <canvas id="reportChart" style="height:450px;"></canvas>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow flex gap-4">
        <a href="/report-analysis/export/csv" class="px-5 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition">Export CSV</a>
        <a href="/report-analysis/export/xlsx" class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">Export Excel</a>
        <a href="/report-analysis/export/pdf" class="px-5 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">Export PDF</a>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="module">
$(document).ready(function() {
    loadReportData();
});

function loadReportData() {
    $.ajax({
        url: "/report-analysis",
        method: "GET",
        dataType: "json",
        success: function(response) {
            const summary = response.summary;
            const chartData = response.chartData;

            $("#totalDeposits").text("₱" + parseFloat(summary.deposit || 0).toFixed(2));
            $("#totalWithdrawals").text("₱" + Math.abs(parseFloat(summary.withdraw || 0)).toFixed(2));
            $("#netBalance").text("₱" + parseFloat(summary.balance || 0).toFixed(2));

            if (!chartData || chartData.length === 0) {
                $("#reportChart").replaceWith("<p class='text-gray-500 text-center'>⚠️ No data available for chart.</p>");
                return;
            }

            const ctx = document.getElementById('reportChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.map(r => r.month),
                    datasets: [
                        {
                            label: 'Deposits',
                            data: chartData.map(r => r.deposits),
                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                            borderRadius: 6
                        },
                        {
                            label: 'Withdrawals',
                            data: chartData.map(r => r.withdrawals),
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderRadius: 6
                        },
                        {
                            label: 'Net Balance',
                            type: 'line',
                            data: chartData.map(r => (r.deposits - r.withdrawals)),
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    scales: { x: { grid: { display: false } }, y: { beginAtZero: true } }
                }
            });
        },
        error: function(err) {
            console.error("Error loading report analysis:", err);
        }
    });
}
</script>
@endsection
