@extends('layouts.app')

@section('page-content')
@include('layouts.admin-nav')
<main class="flex-1 p-6 overflow-y-auto">
    <div class="max-w-6xl mx-auto space-y-8">
        <div class="bg-white p-6 rounded-xl shadow-md border flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Admin Loan Management</h2>
            <img src="{{ asset('image/logo.png') }}" alt="Logo" class="h-14 w-auto">
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">All Loan Requests</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">Borrower</th>
                            <th class="px-4 py-2 border">Email</th>
                            <th class="px-4 py-2 border">Amount</th>
                            <th class="px-4 py-2 border">Term</th>
                            <th class="px-4 py-2 border">Interest</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Applied On</th>
                            <th class="px-4 py-2 border">Action</th>
                        </tr>
                    </thead>
                    <tbody id="adminLoanBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="module">
$(document).ready(function() {
    loadLoans();

    function loadLoans() {
        $.get("{{ route('admin.loans.list') }}", function(loans) {
            let rows = '';
            if (loans.length > 0) {
                loans.forEach(l => {
                    const statusColor = {
                        'pending': 'text-yellow-500',
                        'approved': 'text-green-600',
                        'rejected': 'text-red-600',
                        'paid': 'text-blue-600'
                    }[l.status];

                    rows += `
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">${l.user?.name ?? 'Unknown'}</td>
                            <td class="px-4 py-2">${l.user?.email ?? '-'}</td>
                            <td class="px-4 py-2">₱${parseFloat(l.amount).toFixed(2)}</td>
                            <td class="px-4 py-2">${l.term} months</td>
                            <td class="px-4 py-2">${l.interest_rate}%</td>
                            <td class="px-4 py-2 font-semibold ${statusColor}">${l.status}</td>
                            <td class="px-4 py-2">${new Date(l.created_at).toLocaleDateString()}</td>
                            <td class="px-4 py-2">
                                ${l.status === 'pending' ? `
                                <button class="bg-green-600 text-white px-3 py-1 rounded mr-2 hover:bg-green-500 approveBtn" data-id="${l.id}">Approve</button>
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-500 rejectBtn" data-id="${l.id}">Reject</button>
                                ` : `<span class="text-gray-500 italic">No action</span>`}
                            </td>
                        </tr>`;
                });
            } else {
                rows = `<tr><td colspan="8" class="text-center text-gray-500 py-4">No loans found</td></tr>`;
            }
            $('#adminLoanBody').html(rows);
        });
    }

    $(document).on('click', '.approveBtn', function() {
        updateLoanStatus($(this).data('id'), 'approved');
    });

    $(document).on('click', '.rejectBtn', function() {
        updateLoanStatus($(this).data('id'), 'rejected');
    });

    function updateLoanStatus(loanId, status) {
        $.ajax({
            url: "{{ route('admin.loans.updateStatus') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                loan_id: loanId,
                status: status
            },
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadLoans();
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseText, 'error');
            }
        });
    }
});
</script>
@endsection
