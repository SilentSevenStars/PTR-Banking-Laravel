@extends('layouts.app')

@section('page-content')
@include('layouts.navigation')
<main class="flex-1 p-6 overflow-y-auto">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div class="bg-white shadow-md rounded-xl p-6 flex items-center justify-between border">
            <h2 class="text-2xl font-bold text-gray-800">Loan Services</h2>
            <img src="{{ asset('assets/image/logo.png') }}" alt="Bank Logo" class="h-16 w-auto object-contain">
        </div>

        <!-- Balances -->
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-md border">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Available Loan Balance</h3>
                <p class="text-3xl font-bold text-blue-600" id="availableBalanceDisplay">₱0.00</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Current Account Balance</h3>
                <p class="text-3xl font-bold text-green-600" id="balanceDisplay">₱0.00</p>
            </div>
        </div>

        <!-- Loan Application -->
        <div class="bg-white p-6 rounded-xl shadow-md border">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Apply for a Loan</h3>
            <form id="loanForm" class="space-y-4">
                @csrf
                <input type="hidden" id="balance" name="balance">
                <input type="hidden" id="availableBalance" name="availableBalance">

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
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-500 transition">
                    Submit Application
                </button>
            </form>
        </div>

        <!-- Loan History -->
        <div class="bg-white p-6 rounded-xl shadow-md border">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Loan History</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border rounded-lg shadow-sm overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">Loan Amount</th>
                            <th class="px-4 py-2 border">Term</th>
                            <th class="px-4 py-2 border">Interest Rate</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Applied On</th>
                        </tr>
                    </thead>
                    <tbody id="tBodyLoan"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="successModalLoan"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl p-8 w-96 text-center">
            <h2 class="text-xl font-bold text-green-600 mb-4">Loan Application Successful</h2>
            <p class="text-gray-700 mb-6">Your loan has been applied successfully!</p>
            <button id="closeModalLoan"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition">OK</button>
        </div>
    </div>
</main>

<script type="module">
    $(document).ready(function() {
        loadBalances();
        loadLoanHistory();

        $('#loanForm').on('submit', function(e) {
            e.preventDefault();
            const amount = parseFloat($('#amount').val());
            const term = parseInt($('#term').val());

            $.ajax({
                url: "{{ route('loan.store') }}",
                method: "POST",
                data: {
                    amount: amount,
                    term: term,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        $("#successModalLoan").removeClass("hidden");
                        $('#loanForm')[0].reset();
                        loadLoanHistory();
                    }
                },
                error: function(xhr) {
                    console.error("Loan submission error:", xhr.responseText);
                }
            });
        });

        $('#closeModalLoan').on('click', function() {
            $('#successModalLoan').addClass('hidden');
        });
    });

    function loadBalances() {
        $.get("{{ route('loan.list') }}", function(loans) {
            const totalLoans = loans.reduce((sum, l) => sum + parseFloat(l.amount), 0);
            const availableBalance = 50000 - totalLoans; // sample formula
            $('#availableBalanceDisplay').text(`₱${availableBalance.toFixed(2)}`);
            $('#balanceDisplay').text(`₱${(10000).toFixed(2)}`); // sample static
        });
    }

    function loadLoanHistory() {
        $.get("{{ route('loan.list') }}", function(loans) {
            let rows = '';
            if (loans.length > 0) {
                loans.forEach(l => {
                    rows += `
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="px-4 py-2">₱${parseFloat(l.amount).toFixed(2)}</td>
                            <td class="px-4 py-2">${l.term} months</td>
                            <td class="px-4 py-2">${l.interest_rate}%</td>
                            <td class="px-4 py-2 capitalize">${l.status}</td>
                            <td class="px-4 py-2">${new Date(l.created_at).toLocaleDateString()}</td>
                        </tr>
                    `;
                });
            } else {
                rows = `<tr><td colspan="5" class="text-center text-gray-500 py-4">No loans found</td></tr>`;
            }
            $('#tBodyLoan').html(rows);
        });
    }
</script>
@endsection
