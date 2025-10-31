<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $auth = Auth::user();

            $activeLoans = DB::table('loans')
                ->where('user_id', $auth->id)
                ->whereIn('status', ['approved'])
                ->count();

            $loanBalance = DB::table('loans')
                ->where('user_id', $auth->id)
                ->whereIn('status', ['approved'])
                ->sum('remaining_balance');

            $savings = $auth->balance - $loanBalance;

            $recent = DB::table('transactions')
                ->where('user_id', $auth->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'auth' => [[
                    'id' => $auth->id,
                    'name' => $auth->name,
                    'balance' => $auth->balance,
                    'activeLoans' => $activeLoans,
                    'loanBalance' => $loanBalance,
                    'savings' => $savings,
                ]],
                'recent' => $recent
            ]);
        }

        return view('user.dashboard');
    }

    public function getDashboardChart()
    {
        $userId = Auth::id();

        $transactions = DB::table('transactions')
            ->selectRaw('DATE(created_at) as txn_date')
            ->selectRaw("SUM(CASE WHEN type = 'deposit' THEN amount ELSE 0 END) as deposits")
            ->selectRaw("SUM(CASE WHEN type = 'withdraw' THEN amount ELSE 0 END) as withdrawals")
            ->selectRaw("SUM(CASE WHEN type = 'loan repayment' THEN amount ELSE 0 END) as loan_repayments")
            ->where('user_id', $userId)
            ->groupBy('txn_date')
            ->orderBy('txn_date', 'asc')
            ->get();

        return response()->json($transactions);
    }
}
