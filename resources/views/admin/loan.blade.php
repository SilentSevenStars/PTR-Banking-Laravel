@extends('layouts.app')

@section('page-content')
@include('layouts.admin-nav')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Loan Requests</h1>

    <table class="w-full border" id="loanTable">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">User</th>
                <th class="p-2">Amount</th>
                <th class="p-2">Term</th>
                <th class="p-2">Status</th>
                <th class="p-2">Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script type="module">
    $(function(){
        loadLoans();
    });

    function loadLoans() {
        $.post("{{ url('/admin/loans/list') }}", { _token:"{{csrf_token()}}" }, function(res){
            let html = "";
            res.forEach(row => {
                let btns = "";
                if(row.status === 'pending'){
                    btns = `
                    <button class="px-3 py-1 bg-green-600 text-white mr-2 approveBtn" data-id="${row.id}">Approve</button>
                    <button class="px-3 py-1 bg-red-600 text-white rejectBtn" data-id="${row.id}">Reject</button>`;
                }
                html += `
                <tr class="border-b">
                    <td class="p-2">${row.customer_name}</td>
                    <td class="p-2">₱${parseFloat(row.amount).toFixed(2)}</td>
                    <td class="p-2">${row.term} months</td>
                    <td class="p-2">${row.status}</td>
                    <td class="p-2">${btns}</td>
                </tr>`;
            });
            $("#loanTable tbody").html(html);
        });
    }

    $(document).on("click", ".approveBtn", function(){
        $.post("{{ url('/admin/loans/approve') }}", {
            loan_id: $(this).data("id"),
            _token:"{{csrf_token()}}"
        }, ()=> loadLoans());
    });

    $(document).on("click", ".rejectBtn", function(){
        $.post("{{ url('/admin/loans/reject') }}", {
            loan_id: $(this).data("id"),
            _token:"{{csrf_token()}}"
        }, ()=> loadLoans());
    });
</script>
@endsection