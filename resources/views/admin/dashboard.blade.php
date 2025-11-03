@extends('layouts.app')

@section('page-content')
@include('layouts.admin-nav')
<main class="flex-1 p-6 overflow-y-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Admin Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-5 border">
            <div class="text-gray-500">Total Users</div>
            <div class="text-3xl font-semibold text-gray-900">{{ number_format($totalUsers) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border">
            <div class="text-gray-500">Total Balance</div>
            <div class="text-3xl font-semibold text-gray-900">₱{{ number_format($totalBalance, 2) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border">
            <div class="text-gray-500">Total Deposits</div>
            <div class="text-3xl font-semibold text-green-600">₱{{ number_format($totalDeposits, 2) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border">
            <div class="text-gray-500">Total Withdrawals</div>
            <div class="text-3xl font-semibold text-red-600">₱{{ number_format($totalWithdrawals, 2) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-5 border">
            <div class="text-gray-500">Active Loans</div>
            <div class="text-3xl font-semibold text-indigo-600">{{ number_format($activeLoans) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border">
            <div class="text-gray-500">Outstanding Loan Balance</div>
            <div class="text-3xl font-semibold text-gray-900">₱{{ number_format($loanBalance, 2) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border">
            <div class="text-gray-500">Total Transactions</div>
            <div class="text-3xl font-semibold text-gray-900">{{ number_format($totalTransactions) }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 border">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Overview</h3>
            <span class="text-sm text-gray-500">Aggregated system stats</span>
        </div>
        <div class="h-40 flex items-center justify-center text-gray-400">
            Chart placeholder
        </div>
    </div>
</main>

@endsection