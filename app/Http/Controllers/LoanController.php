<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    public function index()
    {
        return view('user.loan');
    }

    public function viewLoan($id)
    {
        return view('user.loan-view', ['id' => $id]);
    }

    public function getBalance(Request $request)
    {
        $userId = Auth::user()->id;

        $active = DB::table('loans')
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->sum('principal_amount');

        $available = 100000 - $active;
        $balance = DB::table('users')->where('id', $userId)->value('balance');

        return response()->json([
            'availableBalance' => $available,
            'balance' => $balance ?? 0
        ]);
    }

    public function listLoans(Request $request)
    {
        $userId = Auth::user()->id;

        $loans = DB::table('loans')
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($loan) {
                $loan->months_paid = DB::table('loan_repayments')->where('loan_id', $loan->id)->count();
                return $loan;
            });

        return response()->json($loans);
    }

    public function applyLoan(Request $r)
    {
        $r->validate([
            'amount' => 'required|numeric|min:1',
            'term' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        $amount = floatval($r->amount);
        $term = intval($r->term);
        $interestRate = 0.01;

        $total = $amount + ($amount * $interestRate * $term);
        $monthly = $total / max($term, 1);

        $id = DB::table('loans')->insertGetId([
            'user_id' => $user->id,
            'amount' => $amount,
            'principal_amount' => $amount,
            'term' => $term,
            'interest_rate' => $interestRate,
            'monthly_payment' => round($monthly, 2),
            'remaining_balance' => round($total, 2),
            'next_due_date' => now()->addMonth(),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('transactions')->insert([
            'user_id' => $user->id,
            'type' => 'loan request',
            'amount' => $amount,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'loan_id' => $id]);
    }

    public function loanDetails(Request $request)
    {
        $loan = DB::table('loans')->where('id', $request->loan_id)->where('user_id', Auth::user()->id)->first();

        if (!$loan) {
            return response()->json([]);
        }

        $monthsPaid = DB::table('loan_repayments')->where('loan_id', $request->loan_id)->count();
        $loan->months_paid = $monthsPaid;
        $loan->months_left = max(0, $loan->term - $monthsPaid);

        return response()->json($loan);
    }

    public function loanPay(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|numeric',
            'months_to_pay' => 'required|numeric|min:1',
        ]);

        $userId = Auth::id();

        $loan = DB::table('loans')
            ->where('id', $request->loan_id)
            ->where('user_id', $userId)
            ->first();

        if (!$loan) {
            return response()->json(['success' => false, 'message' => 'Loan not found']);
        }

        if ($loan->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Loan not approved yet']);
        }

        $totalPayment = $loan->monthly_payment * $request->months_to_pay;
        $balance = DB::table('users')->where('id', $userId)->value('balance');

        if ($balance < $totalPayment) {
            return response()->json(['success' => false, 'message' => 'Insufficient balance']);
        }

        DB::beginTransaction();

        try {
            DB::table('users')->where('id', $userId)->decrement('balance', $totalPayment);

            $currentInstallments = DB::table('loan_repayments')
                ->where('loan_id', $loan->id)
                ->count();

            for ($i = 1; $i <= $request->months_to_pay; $i++) {
                DB::table('loan_repayments')->insert([
                    'loan_id' => $loan->id,
                    'amount' => $loan->monthly_payment,
                    'installment_no' => $currentInstallments + $i,
                    'paid_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $newBalance = $loan->remaining_balance - $totalPayment;

            $monthsPaid = DB::table('loan_repayments')->where('loan_id', $loan->id)->count();
            $isFullyPaid = $newBalance <= 0 || $monthsPaid >= $loan->term;

            DB::table('loans')->where('id', $loan->id)->update([
                'remaining_balance' => max($newBalance, 0),
                'status' => $isFullyPaid ? 'paid' : 'approved',
                'next_due_date' => $isFullyPaid ? null : now()->addMonth(),
                'updated_at' => now(),
            ]);

            DB::table('transactions')->insert([
                'user_id' => $userId,
                'type' => 'loan repayment',
                'amount' => $totalPayment,
                'status' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isFullyPaid
                    ? 'Loan fully paid! Congratulations!'
                    : 'Loan payment successful.',
                'is_fully_paid' => $isFullyPaid
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error while processing payment']);
        }
    }

    public function depositLoanAmount($loanId)
    {
        $userId = Auth::id();

        $loan = DB::table('loans')
            ->where('id', $loanId)
            ->where('user_id', $userId)
            ->first();

        if (!$loan) {
            return response()->json(['success' => false, 'message' => 'Loan not found']);
        }

        if ($loan->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Loan not approved yet']);
        }

        if ($loan->is_disbursed ?? false) {
            return response()->json(['success' => false, 'message' => 'Loan already deposited']);
        }

        DB::beginTransaction();
        try {

            DB::table('users')->where('id', $userId)->increment('balance', $loan->principal_amount);

            DB::table('loans')->where('id', $loanId)->update([
                'is_disbursed' => true,
                'updated_at' => now()
            ]);

            DB::table('transactions')->insert([
                'user_id' => $userId,
                'type' => 'loan credit',
                'amount' => $loan->principal_amount,
                'status' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Loan deposited successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Error depositing loan']);
        }
    }
    public function loanHistory(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|numeric'
        ]);

        $userId = Auth::id();
        $loanId = $request->loan_id;

        $loan = DB::table('loans')
            ->where('id', $loanId)
            ->where('user_id', $userId)
            ->first();

        if (!$loan) {
            return response()->json([]);
        }

        $history = DB::table('loan_repayments')
            ->where('loan_id', $loanId)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($repayment, $index) {
                return [
                    'installment_no' => $index + 1,
                    'amount' => $repayment->amount,
                    'paid_at' => $repayment->paid_at ?? $repayment->created_at ?? null,
                ];
            });

        return response()->json($history);
    }
}
