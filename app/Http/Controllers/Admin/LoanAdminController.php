<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanAdminController extends Controller
{
    public function index()
    {
        return view('admin.loan');
    }

    public function list(Request $request)
    {
        $loans = DB::table('loans')
            ->join('users', 'users.id', '=', 'loans.user_id')
            ->select(
                'loans.*',
                'users.name as customer_name'
            )
            ->orderBy('loans.id', 'DESC')
            ->get();

        return response()->json($loans);
    }

    public function approve(Request $request)
    {
        $loan = DB::table('loans')->where('id', $request->loan_id)->first();
        if (!$loan) return response()->json(['success' => false, 'message' => 'Loan not found']);

        DB::table('loans')->where('id', $loan->id)->update([
            'status' => 'approved',
            'next_due_date' => now()->addMonth(),
        ]);

        app(\App\Http\Controllers\LoanController::class)->depositLoanAmount($loan->id);

        return response()->json(['success' => true]);
    }

    public function reject(Request $request)
    {
        $loan = DB::table('loans')->where('id', $request->loan_id)->first();
        if (!$loan) return response()->json(['success' => false, 'message' => 'Loan not found']);

        DB::table('loans')->where('id', $request->loan_id)->update([
            'status' => 'rejected'
        ]);

        DB::table('transactions')->insert([
            'user_id' => $loan->user_id,
            'type' => 'loan request',
            'amount' => $loan->amount,
            'status' => 'failed',
            'created_at' => now()
        ]);

        return response()->json(['success' => true]);
    }
    public function loanHistory(Request $requestequest)
    {
        $userId = Auth::user()->id;

        $history = DB::table('loan_repayments')
            ->join('loans', 'loan_repayments.loan_id', '=', 'loans.id')
            ->select(
                'loan_repayments.*',
                'loans.amount as loan_amount',
                'loans.monthly_payment'
            )
            ->where('loans.user_id', $userId)
            ->orderBy('loan_repayments.id', 'desc')
            ->get();

        return response()->json($history);
    }
}
