@extends('layouts.app')

@section('page-content')
@include('layouts.admin-nav')

<!-- Main Content Area -->
<div class="flex-1 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6 overflow-auto min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section with Enhanced Styling -->
        <div class="mb-8 animate-fadeIn">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-2">
                        Loan Requests
                    </h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Manage and review customer loan applications
                    </p>
                </div>
                <button onclick="loadLoans()" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
                    <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Enhanced Stats Cards with Animations -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Requests Card -->
            <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-blue-500 transform hover:-translate-y-1 animate-slideInLeft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Total Requests</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300" id="totalCount">0</h3>
                        <p class="text-xs text-gray-400 mt-1">All time applications</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Card -->
            <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-amber-500 transform hover:-translate-y-1 animate-slideInLeft" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Pending Review</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300" id="pendingCount">0</h3>
                        <p class="text-xs text-gray-400 mt-1">Awaiting approval</p>
                    </div>
                    <div class="bg-gradient-to-br from-amber-500 to-yellow-600 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300 relative">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Approved Card -->
            <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-emerald-500 transform hover:-translate-y-1 animate-slideInLeft" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Approved</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300" id="approvedCount">0</h3>
                        <p class="text-xs text-gray-400 mt-1">Successfully processed</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Paid Card -->
            <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-purple-500 transform hover:-translate-y-1 animate-slideInLeft" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Paid</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2 transition-all duration-300" id="paidCount">0</h3>
                        <p class="text-xs text-gray-400 mt-1">Fully paid loans</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl p-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Table Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeIn" style="animation-delay: 0.3s;">
            <!-- Table Header with Filter -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Loan Applications</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="text-white font-medium text-sm">Filter:</label>
                        <select id="statusFilter" class="bg-white/90 backdrop-blur-sm border-0 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-white/50 shadow-lg cursor-pointer transition-all duration-200 hover:bg-white">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full" id="loanTable">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Loan Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Term</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="relative">
                                        <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                                        <svg class="w-8 h-8 text-blue-600 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="mt-4 text-gray-500 font-medium">Loading loan requests...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-20px); }
    to { opacity: 1; transform: translateX(0); }
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease-out forwards;
}

.animate-slideInLeft {
    animation: slideInLeft 0.6s ease-out forwards;
    opacity: 0;
}

@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

.loading-shimmer {
    animation: shimmer 2s infinite linear;
    background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
    background-size: 1000px 100%;
}
</style>

<script type="module">
    let allLoans = [];

    $(function(){
        loadLoans();
        
        $('#statusFilter').on('change', function(){
            filterLoans();
        });
    });

    function loadLoans() {
        $.post("{{ url('/admin/loans/list') }}", { _token:"{{csrf_token()}}" }, function(res){
            allLoans = res;
            updateStats();
            filterLoans();
        });
    }

    function updateStats() {
        const total = allLoans.length;
        const pending = allLoans.filter(l => l.status === 'pending').length;
        const approved = allLoans.filter(l => l.status === 'approved').length;
        const paid = allLoans.filter(l => l.status === 'paid').length;
        
        animateValue('totalCount', 0, total, 800);
        animateValue('pendingCount', 0, pending, 800);
        animateValue('approvedCount', 0, approved, 800);
        animateValue('paidCount', 0, paid, 800);
    }

    function animateValue(id, start, end, duration) {
        const element = document.getElementById(id);
        const range = end - start;
        const increment = range / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                current = end;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 16);
    }

    function filterLoans() {
        const filter = $('#statusFilter').val();
        const filtered = filter === 'all' ? allLoans : allLoans.filter(l => l.status === filter);
        
        let html = "";
        
        if(filtered.length === 0) {
            html = `
            <tr>
                <td colspan="5" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <div class="bg-gray-100 rounded-full p-6 mb-4">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No loan requests found</h3>
                        <p class="text-sm text-gray-500">There are no ${filter === 'all' ? '' : filter} loan applications at this time.</p>
                    </div>
                </td>
            </tr>`;
        } else {
            filtered.forEach((row, index) => {
                let statusBadge = "";
                let btns = "";
                
                if(row.status === 'pending'){
                    statusBadge = `
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200 shadow-sm">
                        <span class="relative flex h-2 w-2 mr-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                        Pending
                    </span>`;
                    btns = `
                    <div class="flex items-center gap-2">
                        <button class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 approveBtn" data-id="${row.id}">
                            <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Approve
                        </button>
                        <button class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 rejectBtn" data-id="${row.id}">
                            <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reject
                        </button>
                    </div>`;
                } else if(row.status === 'approved'){
                    statusBadge = `
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200 shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Approved
                    </span>`;
                } else if(row.status === 'rejected'){
                    statusBadge = `
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200 shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Rejected
                    </span>`;
                } else if(row.status === 'paid'){
                    statusBadge = `
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-100 to-violet-100 text-purple-800 border border-purple-200 shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Paid
                    </span>`;
                }
                
                html += `
                <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 animate-fadeIn" style="animation-delay: ${index * 0.05}s;">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg transform hover:scale-110 transition-transform duration-200">
                                ${row.customer_name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">${row.customer_name}</div>
                                <div class="text-xs text-gray-500">Customer ID: #${row.id}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-base font-bold text-gray-900">₱${parseFloat(row.amount).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                        <div class="text-xs text-gray-500">Principal amount</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-lg">
                            <svg class="w-4 h-4 mr-1.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">${row.term} months</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        ${statusBadge}
                    </td>
                    <td class="px-6 py-4">
                        ${btns}
                    </td>
                </tr>`;
            });
        }
        
        $("#loanTable tbody").html(html);
    }

    $(document).on("click", ".approveBtn", function(){
        const btn = $(this);
        const originalHtml = btn.html();
        
        btn.prop('disabled', true).html(`
            <svg class="animate-spin h-4 w-4 text-white mr-1.5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `);
        
        $.post("{{ url('/admin/loans/approve') }}", {
            loan_id: btn.data("id"),
            _token:"{{csrf_token()}}"
        }, ()=> {
            loadLoans();
        }).fail(() => {
            btn.prop('disabled', false).html(originalHtml);
        });
    });

    $(document).on("click", ".rejectBtn", function(){
        const btn = $(this);
        const originalHtml = btn.html();
        
        btn.prop('disabled', true).html(`
            <svg class="animate-spin h-4 w-4 text-white mr-1.5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `);
        
        $.post("{{ url('/admin/loans/reject') }}", {
            loan_id: btn.data("id"),
            _token:"{{csrf_token()}}"
        }, ()=> {
            loadLoans();
        }).fail(() => {
            btn.prop('disabled', false).html(originalHtml);
        });
    });

    // Make loadLoans globally accessible
    window.loadLoans = loadLoans;
</script>
@endsection