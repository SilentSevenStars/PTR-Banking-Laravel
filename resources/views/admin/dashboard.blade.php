@extends('layouts.app')

@section('page-content')
@include('layouts.admin-nav')
<main class="flex-1 p-6 overflow-y-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6" id="greeting"></h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-4 text-center border">
            <h3 class="text-gray-500">Balance</h3>
            <p id="balanceText" class="text-2xl font-bold text-gray-800">₱0.00</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 text-center border">
            <h3 class="text-gray-500">Active Loans</h3>
            <p id="activeLoans" class="text-2xl font-bold text-green-500">0</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 text-center border">
            <h3 class="text-gray-500">Loan Balance</h3>
            <p id="loanBalance" class="text-2xl font-bold text-red-500">₱0.00</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 text-center border">
            <h3 class="text-gray-500">Savings</h3>
            <p id="savingsText" class="text-2xl font-bold text-blue-500">₱0.00</p>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 col-span-2 border h-64">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Finances</h3>
            <canvas id="financeChart"></canvas>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Quick Transaction</h3>
            <form id="quickTransactionForm" class="space-y-4">
                <input type="hidden" name="balance" id="balance">
                <input type="number" id="amountInput" name="amount" placeholder="Enter Amount"
                    class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500" required>
                <div class="flex space-x-2">
                    <button type="button" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-500">Deposit</button>
                    <button type="button" class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-500">Withdraw</button>
                </div>
            </form>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6 mt-6 border">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Transaction History</h3>
        <div class="max-h-64 overflow-y-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="pb-2 text-gray-500">Type</th>
                        <th class="pb-2 text-gray-500">Amount</th>
                        <th class="pb-2 text-gray-500">Status</th>
                        <th class="pb-2 text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody id="tBodyTransaction"></tbody>
            </table>
        </div>
    </div>
</main>

@endsection