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

<div id="transactionModal"
    class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center">
    <div id="modalBox"
        class="bg-white rounded-2xl shadow-2xl p-8 w-96 text-center transform scale-90 opacity-0 transition-all duration-300 ease-out">

        <div id="modalIcon" class="flex items-center justify-center mb-6"></div>
        <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 mb-2"></h2>
        <p id="modalMessage" class="text-gray-600 mb-6"></p>
        <button id="modalCloseBtn"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-500 transition-all duration-200">
            OK
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- <script src="asset('js/chart.js')"></script>  -->

<script type="module">
    let financeChart = null;
    $(document).ready(function() {
        loadData()
        loadChart()
        $('#modalCloseBtn').on('click', function() {
            closeModal()
        })
    })

    function showModal(title, message, type = "info") {
        let iconHtml = "";

        if (type === "success") {
            iconHtml = `
            <div class="relative mx-auto flex items-center justify-center w-20 h-20">
                <div class="absolute inset-0 rounded-full border-4 border-green-500 animate-pulse"></div>
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="w-12 h-12 text-green-600 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        `;
        } else if (type === "error") {
            iconHtml = `
            <div class="relative mx-auto flex items-center justify-center w-20 h-20">
                <div class="absolute inset-0 rounded-full border-4 border-red-500 animate-pulse"></div>
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="w-12 h-12 text-red-600 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        `;
        } else {
            iconHtml = `
            <div class="relative mx-auto flex items-center justify-center w-20 h-20">
                <div class="absolute inset-0 rounded-full border-4 border-blue-500 animate-pulse"></div>
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="w-12 h-12 text-blue-600 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                </svg>
            </div>
        `;
        }

        $("#modalIcon").html(iconHtml);
        $("#modalTitle").text(title);
        $("#modalMessage").text(message);

        $("#transactionModal").removeClass("hidden");
        setTimeout(() => {
            $("#modalBox")
                .removeClass("scale-90 opacity-0")
                .addClass("scale-100 opacity-100");
        }, 50);
    }

    function closeModal() {
        $("#modalBox")
            .removeClass("scale-100 opacity-100")
            .addClass("scale-90 opacity-0");
        setTimeout(() => {
            $("#transactionModal").addClass("hidden");
        }, 200);
    }

    function loadData() {
        $.ajax({
            url: "/",
            type: "GET",
            dataType: 'json',
            success: function(response) {
                let tBody = "";

                const user = response.auth ? response.auth[0] : {};
                $("#balance").val(user.balance ?? 0);
                $("#balanceText").text(`₱${parseFloat(user.balance ?? 0).toFixed(2)}`);

                if (response.recent && response.recent.length > 0) {
                    response.recent.forEach(function(data) {
                        let typeDisplay = data.type.split(" ")
                            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                            .join(" ");

                        let amount = parseFloat(data.amount);
                        let isDeduction = (data.type === "withdraw" || data.type === "loan repayment");
                        let sign = isDeduction ? '-' : '+';
                        let amountDisplay = amount.toFixed(2);
                        let amountColor = isDeduction ? 'text-red-500' : 'text-green-500';
                        let statusColor = data.status.toLowerCase() === 'success' ? 'text-green-500' : 'text-red-500';

                        tBody += `
                            <tr class="border-b hover:bg-gray-100">
                                <td class="py-3 px-6">${typeDisplay}</td>
                                <td class="py-3 px-6 ${amountColor} font-semibold">${sign}₱${amountDisplay}</td>
                                <td class="py-3 px-6 ${statusColor} font-semibold">
                                    ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                                </td>
                                <td class="py-3 px-6">${formatDate(data.date)}</td>
                            </tr>
                        `;
                    });
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
        });
    }

    function submitTransaction(transactionType) {
        let amount = parseFloat($('#amountInput').val() || 0);
        let balance = parseFloat($('#quickTransactionForm #balance').val() || 0);

        if (isNaN(amount) || amount <= 0) {
            showModal("Invalid Amount", "Please enter an amount greater than zero.", "error");
            return;
        }

        if (transactionType === 'withdraw' && amount > balance) {
            showModal("Insufficient Balance", "You do not have enough funds to withdraw this amount.", "error");
            return;
        }

        let newBalance = balance;
        if (transactionType === 'deposit') newBalance += amount;
        else if (transactionType === 'withdraw') newBalance -= amount;

        $.ajax({
            url: "/transaction/create",
            method: "POST",
            data: {
                'type': transactionType,
                'amount': amount,
                'status': 'success',
                'balance': newBalance,
                _token: $("meta[name='csrf-token']").attr("content"),
            },
            success: function(response) {
                if (response.errors) {
                    let messages = Object.values(response.errors).flat().join("<br>");
                    showModal("Transaction Failed", messages, "error");
                } else {
                    const actionWord = transactionType === 'deposit' ? 'deposited' : 'withdrawn';
                    showModal("Transaction Successful", `₱${amount.toFixed(2)} has been ${actionWord} successfully.`, "success");
                    loadData();
                    loadChart();
                    $('#amountInput').val('');
                }
            },
            error: function() {
                showModal("Error", "An error occurred while processing your transaction.", "error");
            }
        });
    }

    function loadChart() {
        $.ajax({
            url: "/user/chart",
            method: "GET",
            dataType: "json",
            success: function(datas) {
                if (!datas || datas.length === 0) return;

                let labels = datas.map(d => d.txn_date);
                let deposits = datas.map(d => parseFloat(d.deposits));
                let withdrawals = datas.map(d => parseFloat(d.withdrawals));
                let repayments = datas.map(d => parseFloat(d.loan_repayments));

                const ctx = document.getElementById('financeChart').getContext('2d');

                // ✅ Destroy old chart before rendering new one
                if (financeChart !== null) {
                    financeChart.destroy();
                }

                financeChart = new Chart(ctx, {
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
                console.error("Chart load error:", err);
            }
        });
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        if (isNaN(date)) return dateString; 

        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        const year = date.getFullYear();

        return `${month}/${day}/${year}`;
    }

    window.submitTransaction = submitTransaction;
</script>
@endsection