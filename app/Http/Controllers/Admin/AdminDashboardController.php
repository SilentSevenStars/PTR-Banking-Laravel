<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function admin()
    {
        $metrics = [
            'totalUsers' => User::count(),
            'totalBalance' => (float) User::sum('balance'),
            'totalTransactions' => Transaction::count(),
            'totalDeposits' => (float) Transaction::where('type', 'deposit')->sum('amount'),
            'totalWithdrawals' => (float) Transaction::where('type', 'withdrawal')->sum('amount'),
            'activeLoans' => Loan::where('status', 'approved')->count(),
            'loanBalance' => (float) Loan::sum('remaining_balance'),
        ];

        return view('admin.dashboard', $metrics);
    }
}
