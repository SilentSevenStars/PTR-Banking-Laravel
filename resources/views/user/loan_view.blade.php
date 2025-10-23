@extends('layouts.app')

@section('page-content')
@include('layouts.navigation')

<main class="flex-1 p-6 overflow-y-auto">
    <div class="max-w-5xl mx-auto space-y-8">
        <div class="bg-white shadow-md rounded-xl p-6 flex items-center justify-between border">
            <h2 class="text-2xl font-bold text-gray-800">Loan Services</h2>
            <img src="{{ asset('image/logo.png') }}" alt="Bank Logo" class="h-16 w-auto object-contain">
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Loan Details</h3>
            <div id="loanDetails" class="space-y-2 text-gray-700">Loading loan details...</div>
            <div id="progressContainer" class="w-full bg-gray-200 rounded-full h-3 mt-4 hidden shadow-inner">
                <div id="progressBar" class="bg-green-500 h-3 rounded-full transition-all" style="width:0%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Make a Payment</h3>
            <form id="paymentForm" class="space-y-4">
                @csrf
                <input type="hidden" id="loan_id" value="{{ $loan_id }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Choose Months to Pay</label>
                    <div id="monthsOptions" class="flex flex-wrap gap-3"></div>
                    <p id="monthHelp" class="text-xs text-gray-500 mt-2"></p>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg border shadow-sm">
                    <p class="text-sm text-gray-600">Total Payment:</p>
                    <p class="text-2xl font-bold text-green-600" id="totalPayment">₱ 0.00</p>
                    <p class="text-xs text-gray-500 mt-1" id="paymentBreakdown"></p>
                </div>

                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-500 hover:shadow-md transition">
                    Submit Payment
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Payment History</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border rounded-lg shadow-sm overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 border">Installment</th>
                            <th class="px-3 py-2 border">Amount</th>
                            <th class="px-3 py-2 border">Date Paid</th>
                        </tr>
                    </thead>
                    <tbody id="historyTable"></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div id="successModalPayment" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-xl p-8 w-96 text-center">
        <h2 class="text-xl font-bold text-green-600 mb-4">Payment Successful</h2>
        <p class="text-gray-700 mb-6">Your payment has been processed successfully!</p>
        <button id="closeModalPayment" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 shadow hover:shadow-md transition">OK</button>
    </div>
</div>

@endsection

@push('scripts')
<script type="module">
    const LOAN_ID = {{ (int)$loan_id }};
    const csrfToken = "{{ csrf_token() }}";
    let monthsPaid = 0;
    let term = 0;
    let monthly = 0.0;

    $(document).ready(function() {
        loadLoanDetails();
        loadLoanHistory();

        $('#paymentForm').on('submit', function(e) {
            e.preventDefault();
            const selected = $('.monthChk:checked').map(function(){ return parseInt($(this).val()); }).get();
            const unpaid = selected.filter(v => v > monthsPaid);
            const monthsToPay = unpaid.length;

            let payload = {
                loan_id: LOAN_ID,
                _token: csrfToken
            };

            if (monthsToPay > 0) payload.months_to_pay = monthsToPay;

            $.post("{{ route('loan.pay') }}", payload, function(resp) {
                if (resp.success) {
                    $("#successModalPayment").removeClass("hidden");
                    loadLoanDetails();
                    loadLoanHistory();
                } else {
                    alert(resp.message || 'Payment failed.');
                }
            }).fail(function(xhr){
                const text = xhr.responseJSON?.message || xhr.responseText || 'Payment failed';
                alert(text);
            });
        });

        $("#closeModalPayment").on("click", function() {
            $("#successModalPayment").addClass("hidden");
            loadLoanDetails();
            loadLoanHistory();
        });
    });

    function loadLoanDetails() {
        $.post("{{ route('loan.get') }}", { loan_id: LOAN_ID, _token: csrfToken }, function(result) {
            const loanData = result;
            if (loanData && loanData.id) {
                const amount = Number(loanData.amount);
                monthly = Number(loanData.monthly_payment);
                const remaining = Number(loanData.remaining_balance);
                term = Number(loanData.term);
                monthsPaid = Number(loanData.months_paid || 0);

                const progress = Math.round((monthsPaid / term) * 100);

                $('#loanDetails').html(`
                    <p><strong>Loan Amount:</strong> ₱${amount.toFixed(2)}</p>
                    <p><strong>Monthly Payment:</strong> ₱${monthly.toFixed(2)}</p>
                    <p><strong>Remaining Balance:</strong> ₱${remaining.toFixed(2)}</p>
                    <p><strong>Term:</strong> ${term} months</p>
                    <p><strong>Months Paid:</strong> ${monthsPaid}/${term}</p>
                    <p><strong>Next Due Date:</strong> ${loanData.next_due_date ?? '---'}</p>
                    <p><strong>Status:</strong> ${monthsPaid >= term ? '<span class="text-green-600 font-bold">Fully Paid</span>' : '<span class="text-yellow-600 font-bold">Active</span>'}</p>
                `);

                $('#progressContainer').removeClass("hidden");
                $('#progressBar').css("width", progress + "%");

                generateMonthOptions(term, monthsPaid);
                computePayment();
            } else {
                $('#loanDetails').html(`<p class="text-red-600">Failed to load loan details.</p>`);
                $('#paymentForm').hide();
            }
        }).fail(function(){
            $('#loanDetails').html(`<p class="text-red-600">Failed to load loan details.</p>`);
            $('#paymentForm').hide();
        });
    }

    function generateMonthOptions(termVal, monthsPaidVal) {
        let html = '';
        for (let i = 1; i <= termVal; i++) {
            const paid = i <= monthsPaidVal;
            html += `
            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer ${paid ? 'bg-gray-200 opacity-60 cursor-not-allowed' : 'hover:bg-gray-50'}">
                <input type="checkbox" class="monthChk" value="${i}" ${paid ? 'disabled checked' : ''}>
                <span>Month ${i}</span>
            </label>`;
        }
        $('#monthsOptions').html(html);

        if (monthsPaidVal >= termVal) {
            $('#monthHelp').text("Loan fully paid. No more payments needed.");
            $('.monthChk').prop('disabled', true);
            return;
        }

        $('#monthHelp').text(`Select contiguous months starting from Month ${monthsPaidVal + 1}.`);
        $(`.monthChk[value="${monthsPaidVal + 1}"]`).prop('checked', true);

        $('.monthChk').on('change', enforceContiguousSelection);
    }

    function enforceContiguousSelection() {
        const start = monthsPaid + 1;
        let selectedVal = parseInt($(this).val());
        let isChecked = $(this).is(':checked');

        if (isChecked) {
            for (let i = start; i <= selectedVal; i++) {
                $(`.monthChk[value="${i}"]`).prop('checked', true);
            }
        } else {
            for (let i = selectedVal; i <= term; i++) {
                $(`.monthChk[value="${i}"]`).prop('checked', false);
            }
        }
        computePayment();
    }

    function computePayment() {
        const selected = $('.monthChk:checked').map(function(){ return parseInt($(this).val()); }).get();
        const unpaid = selected.filter(v => v > monthsPaid);
        if (unpaid.length === 0) {
            $('#totalPayment').text('₱ 0.00');
            $('#paymentBreakdown').text('');
            return;
        }
        const monthsToPay = unpaid.length;
        const total = (monthly * monthsToPay).toFixed(2);
        $('#totalPayment').text(`₱ ${total}`);
        $('#paymentBreakdown').text(`${monthsToPay} month(s) × ₱${monthly.toFixed(2)}`);
    }

    function loadLoanHistory() {
        $.post("{{ route('loan.history') }}", { loan_id: LOAN_ID, _token: csrfToken }, function(history) {
            if (Array.isArray(history) && history.length > 0) {
                let html = '';
                history.forEach((h, idx) => {
                    html += `
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-3 py-2 border">Installment ${idx + 1}</td>
                            <td class="px-3 py-2 border">₱${parseFloat(h.amount).toFixed(2)}</td>
                            <td class="px-3 py-2 border">${h.paid_at}</td>
                        </tr>`;
                });
                $('#historyTable').html(html);
            } else {
                $('#historyTable').html(`<tr><td colspan="3" class="px-3 py-2 text-center text-gray-500">No payments yet.</td></tr>`);
            }
        }).fail(function(){
            $('#historyTable').html(`<tr><td colspan="3" class="px-3 py-2 text-center text-red-500">Failed to load history</td></tr>`);
        });
    }
</script>
@endpush
