@extends('layouts.app')

@section('page-content')
@include('layouts.navigation')
<main class="flex-1 p-6 overflow-y-auto" data-uid="{{ auth()->id() }}">
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
                @csrf
                <input type="hidden" name="balance" id="balance">
                <input type="number" id="amountInput" name="amount" placeholder="Enter Amount"
                    class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500" required>
                <div class="flex space-x-2">
                    <button type="button" onclick="submitTransaction('deposit')" id="depositBtn" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-500">Deposit</button>
                    <button type="button" onclick="submitTransaction('withdraw')" id="withdrawBtn" class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-500">Withdraw</button>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="module">
    $(document).ready(function() {
        loadData()
        loadChart()
    })

    function loadData() {
        $.ajax({
            url: "/dashboard",
            type: "GET",
            dataType: 'json',
            success: function(response) {
                let tBody = ""

                if (response.recent && response.recent.length > 0) {
                    response.recent.forEach(function(data) {
                        let typeDisplay = data.type.split(" ")
                            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                            .join(" ");

                        let amount = parseFloat(data.amount)
                        let isDeduction = (data.type === "withdraw" || data.type === "loan repayment")
                        let sign = isDeduction ? '-' : '+'
                        let amountDisplay = amount.toFixed(2)
                        let amountColor = isDeduction ? 'text-red-500' : 'text-green-500'
                        let statusColor = data.status.toLowerCase() === 'success' ? 'text-green-500' : 'text-red-500'

                        tBody += `
                            <tr class="border-b hover:bg-gray-100">
                                <td class="py-3 px-6">${typeDisplay}</td>
                                <td class="py-3 px-6 ${amountColor} font-semibold">${sign}₱${amountDisplay}</td>
                                <td class="py-3 px-6 ${statusColor} font-semibold">
                                    ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                                </td>
                                <td class="py-3 px-6">${data.date}</td>
                            </tr>
                        `
                    })
                } else {
                    tBody += `
                        <tr class="border-b hover:bg-gray-100 text-center">
                            <td colspan="4" class="py-3 text-gray-500">No transactions found</td>
                        </tr>
                    `;
                }

                $("#tBodyTransaction").html(tBody);
            },
            error: function(err) {
                console.error("AJAX error:", err);
            }
        })
    }

    function submitTransaction(transactionType) {
        let amount = $('#amountInput').val()
        let balance = $('#quickTransactionForm #balance').val()
        amount = parseFloat(amount)
        balance = parseFloat(balance)
        if (transactionType === 'deposit') {
            if (amount > 0) {
                balance = balance + amount;
                $.ajax({
                    url: "/dashboard",
                    method: "POST",
                    data: {
                        'type': transactionType,
                        'amount': amount,
                        'status': 'success',
                        'balance': balance,
                        _token: $("meta[name='csrf-token']").attr("content"),
                    },
                    success: function(response) {
                        if (response.errors) {
                            alert("Error")
                        } else {
                            alert('Success')
                            loadData()
                            $('#amountInput').val('')
                        }
                    },
                    error: function() {

                    }
                });
            } else {
                alert("Invalid amount, must be greater than zero");
            }
        }
        if (transactionType === 'withdraw') {
            if (balance > amount && amount > 0) {
                balance -= amount;
                $.ajax({
                    url: "/dashboard",
                    method: "POST",
                    data: {
                        'type': transactionType,
                        'amount': amount,
                        'status': 'success',
                        'balance': balance,
                        _token: $("meta[name='csrf-token']").attr("content"),
                    },
                    success: function(response) {
                        if (response.errors) {
                            alert("Error")
                        } else {
                            alert('Success')
                            loadData()
                            $('#amountInput').val('')
                        }
                    },
                    error: function() {

                    }
                });
            } else {
                alert("Invalid amount, must be greater than zero");
            }
        }
    }

    function loadChart() {
        $.ajax({
            url: "/dashboard/chart",
            method: "GET",
            dataType: "json",
            success: function(datas) {
                if (!datas || datas.length === 0) return

                let labels = datas.map(d => d.txn_date)
                let deposits = datas.map(d => parseFloat(d.deposits))
                let withdrawals = datas.map(d => parseFloat(d.withdrawals))
                let repayments = datas.map(d => parseFloat(d.loan_repayments))

                const ctx = document.getElementById('financeChart').getContext('2d')
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Deposits',
                                data: deposits,
                                borderColor: 'green',
                                fill: false,
                                tension: 0.3
                            },
                            {
                                label: 'Withdrawals',
                                data: withdrawals,
                                borderColor: 'red',
                                fill: false,
                                tension: 0.3
                            },
                            {
                                label: 'Loan Repayments',
                                data: repayments,
                                borderColor: 'blue',
                                fill: false,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function(err) {
                console.error("Chart load error:", err)
            }
        })
    }

    window.submitTransaction = submitTransaction;
</script>
@endsection