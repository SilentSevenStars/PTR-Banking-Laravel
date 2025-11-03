@extends('layouts.app')

@section('page-content')
@include('layouts.navigation')
<main class="flex-1 p-6">
    <div class="max-w-6xl mx-auto space-y-8">

        <div class="bg-white shadow-md rounded-xl p-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Loan Services</h2>
            <img src="{{ asset('image/logo.png') }}" alt="Bank Logo" class="h-16 w-auto object-contain">
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Available Loan Balance</h3>
                <p class="text-3xl font-bold text-blue-600" id="availableBalanceDisplay">₱0.00</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Current Account Balance</h3>
                <p class="text-3xl font-bold text-green-600" id="balanceDisplay">₱0.00</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Apply for a Loan</h3>
            <form id="loanForm" class="space-y-4">
                <input type="hidden" name="balance" id="balance">
                <input type="hidden" name="availableBalance" id="availableBalance">

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Loan Amount</label>
                    <input type="number" id="amount" name="amount" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="term" class="block text-sm font-medium text-gray-700">Term (Months)</label>
                    <select id="term" name="term" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="12">12 Months</option>
                        <option value="24">24 Months</option>
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-500 hover:shadow-md transition">
                    Submit Application
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Loan History</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border rounded-lg shadow-sm overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">Loan Amount</th>
                            <th class="px-4 py-2 border">Monthly Payment</th>
                            <th class="px-4 py-2 border">Term</th>
                            <th class="px-4 py-2 border">Remaining Balance</th>
                            <th class="px-4 py-2 border">Next Due Date</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tBodyLoan"></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div id="successModalLoan" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-xl p-8 w-96 text-center">
        <h2 class="text-xl font-bold text-green-600 mb-4">Loan Application Successful</h2>
        <p class="text-gray-700 mb-6">Your loan application was submitted and is pending admin approval.</p>
        <button id="closeModalLoan" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-500 transition">OK</button>
    </div>
</div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

<script type="module">

    $(document).ready(function() {
        loadBalance();
        loadLoanHistory();
    });

    function loadBalance() {
        $.post("{{ url('/loan/balance') }}", {
            _token: "{{ csrf_token() }}",
        }, function(res) {
            $('#availableBalance').val(res.availableBalance);
            $('#balance').val(res.balance);

            $('#availableBalanceDisplay').text(`₱${Number(res.availableBalance).toFixed(2)}`);
            $('#balanceDisplay').text(`₱${Number(res.balance).toFixed(2)}`);
        }).fail(() => alert("Error loading balance"));
    }

    function loadLoanHistory() {
        $.post("{{ url('/loan/list') }}", {
            _token: "{{ csrf_token() }}",
        }, function(rows) {
            let html = "";

            if (!rows.length) {
                html = `<tr><td colspan="7" class="text-center py-4 text-gray-500">No loans found</td></tr>`;
            } else {
                rows.forEach(row => {
                    html += `
                    <tr class="border">
                        <td class="px-3 py-2">₱${Number(row.amount).toFixed(2)}</td>
                        <td class="px-3 py-2">₱${Number(row.monthly_payment).toFixed(2)}</td>
                        <td class="px-3 py-2">${row.term} months</td>
                        <td class="px-3 py-2">₱${Number(row.remaining_balance).toFixed(2)}</td>
                        <td class="px-3 py-2">${row.next_due_date ?? '---'}</td>
                        <td class="px-3 py-2">
                            <span class="px-2 py-1 rounded-md text-sm ${
                                row.status === "approved" ? "bg-yellow-200 text-yellow-700" :
                                row.status === "pending" ? "bg-blue-200 text-blue-700" :
                                row.status === "rejected" ? "bg-red-200 text-red-700" :
                                "bg-green-200 text-green-700"
                            }">
                                ${row.status === "approved" ? `${row.months_paid}/${row.term} paid` : row.status}
                            </span>
                        </td>
                        <td class="px-3 py-2">
                            ${
                                row.status === 'approved'
                                ? `<a href="{{ url('/loan/view') }}/${row.id}"
                                    class="px-3 py-1 rounded-lg text-white bg-red-600 hover:bg-red-500">
                                        Pay
                                </a>`
                                : row.status === 'paid'
                                ? `<a href="{{ url('/loan/view') }}/${row.id}"
                                    class="px-3 py-1 rounded-lg text-white bg-blue-600 hover:bg-blue-500">
                                        View
                                </a>`
                                : `<span class="text-gray-400 text-sm">No actions</span>`
                            }
                        </td>
                    </tr>`;
                });
            }

            $("#tBodyLoan").html(html);
        }).fail(() => alert("Error loading loans"));
    }

    $("#loanForm").on("submit", function(e) {
        e.preventDefault();

        $.post("{{ url('/loan/apply') }}", {
            _token: "{{ csrf_token() }}",
            amount: $("#amount").val(),
            term: $("#term").val()
        }, function(res) {
            if (res.success) {
                $("#successModalLoan").removeClass("hidden");
                loadBalance();
                loadLoanHistory();
            } else {
                alert(res.message || "Application failed");
            }
        }).fail(() => alert("Request failed — check controller"));
    });

    $("#closeModalLoan").on("click", function() {
        $("#successModalLoan").addClass("hidden");
    });
</script>
@endsection