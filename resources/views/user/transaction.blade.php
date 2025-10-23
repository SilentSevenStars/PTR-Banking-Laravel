@extends('layouts.app')

@section('page-content')
@include('layouts.navigation')
<main class="flex-1 p-6 overflow-y-auto">
    <h2 class="text-2xl font-bold mb-6">Transaction History</h2>

    <form id="filterForm" class="mb-6 bg-white p-4 rounded-lg shadow flex flex-wrap gap-4">
        <div>
            <label class="block text-gray-700">Type</label>
            <select name="type" class="border rounded p-2" id="type">
                <option value="">All</option>
                <option value="deposit">Deposit</option>
                <option value="withdraw">Withdraw</option>
                <option value="loan repayment">Loan Repayment</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-700">Status</label>
            <select name="status" class="border rounded p-2" id="status">
                <option value="">All</option>
                <option value="success">Success</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-700">From</label>
            <input type="date" name="from" value="<?= $_GET['from'] ?? '' ?>" class="border rounded p-2" id="from">
        </div>
        <div>
            <label class="block text-gray-700">To</label>
            <input type="date" name="to" value="<?= $_GET['to'] ?? '' ?>" class="border rounded p-2" id="to">
        </div>
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">Filter</button>
            <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-400" id="resetBtn">Reset</button>
        </div>
        <div class="flex items-end ml-auto">
            <button type="button" id="exportBtn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">Export CSV</button>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="table-auto w-full border-collapse">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left w-16">ID</th>
                    <th class="px-4 py-2 text-left w-32">Type</th>
                    <th class="px-4 py-2 text-left w-40">Amount</th>
                    <th class="px-4 py-2 text-left w-32">Status</th>
                    <th class="px-4 py-2 text-left w-48">Date</th>
                    <th class="px-4 py-2 text-left w-56">Receipt</th>
                </tr>
            </thead>
            <tbody id="tBodyTransaction"></tbody>
        </table>
        <div id="paginationLinks" class="mt-4"></div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="module">
    window.downloadReceipt = function(id) {
        window.open(`/transactions/receipt/${id}/download`, '_blank');
    }

    window.viewReceipt = function(id) {
        $.ajax({
            url: `/transactions/receipt/${id}`,
            type: 'GET',
            success: function(data) {
                alert(
                    `Transaction Receipt:\n\n` +
                    `ID: ${data.id}\n` +
                    `Type: ${data.type}\n` +
                    `Amount: ₱${data.amount}\n` +
                    `Status: ${data.status}\n` +
                    `Date: ${data.date}`
                );
            },
            error: function(err) {
                console.error('Error viewing receipt:', err);
            }
        });
    }
    $(document).ready(function() {
        loadTransactions();

        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            loadTransactions();
        });
    });

    function loadTransactions(page = 1) {
        let type = $('#type').val();
        let status = $('#status').val();
        let from_date = $('#from').val();
        let to_date = $('#to').val();

        $.ajax({
            url: `/transactions/fetch?page=${page}`,
            type: 'GET',
            data: {
                type,
                status,
                from_date,
                to_date
            },
            success: function(response) {
                let rows = '';
                if (response.data.length > 0) {
                    response.data.forEach(function(txn) {
                        let typeDisplay = txn.type.charAt(0).toUpperCase() + txn.type.slice(1);
                        let color = txn.type === 'withdraw' ? 'text-red-500' : 'text-green-500';
                        let statusColor = txn.status === 'success' ? 'text-green-500' : 'text-red-500';

                        rows += `
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-2 px-4">${txn.id}</td>
                            <td class="py-2 px-4">${typeDisplay}</td>
                            <td class="py-2 px-4 ${color} font-semibold">₱${parseFloat(txn.amount).toFixed(2)}</td>
                            <td class="py-2 px-4 ${statusColor} font-semibold">${txn.status}</td>
                            <td class="py-2 px-4">${txn.created_at}</td>
                            <td class="py-2 px-4">
                                <a href="/transactions/receipt/${txn.id}/view" class="text-blue-600 hover:underline" target="_blank">View</a> |
                                <button class="text-green-600 hover:underline" onclick="downloadReceipt(${txn.id})">Download</button>
                            </td>
                        </tr>`;
                    });
                } else {
                    rows = `
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">No transactions found</td>
                    </tr>`;
                }

                $('#tBodyTransaction').html(rows);

                // ✅ Pagination buttons
                let pagination = '';
                if (response.last_page > 1) {
                    pagination += `<div class="flex justify-center mt-4 space-x-2">`;

                    if (response.prev_page_url) {
                        pagination += `<button class="px-3 py-1 bg-gray-200 rounded" onclick="loadTransactions(${response.current_page - 1})">Prev</button>`;
                    }

                    for (let i = 1; i <= response.last_page; i++) {
                        pagination += `<button class="px-3 py-1 ${i === response.current_page ? 'bg-blue-600 text-white' : 'bg-gray-200'} rounded" onclick="loadTransactions(${i})">${i}</button>`;
                    }

                    if (response.next_page_url) {
                        pagination += `<button class="px-3 py-1 bg-gray-200 rounded" onclick="loadTransactions(${response.current_page + 1})">Next</button>`;
                    }

                    pagination += `</div>`;
                }

                $('#paginationLinks').html(pagination);
            },
            error: function(err) {
                console.error("Error loading transactions:", err);
            }
        });
    }

    $('#exportBtn').on('click', function() {
        window.location.href = '/transactions/export';
    });

</script>

@endsection