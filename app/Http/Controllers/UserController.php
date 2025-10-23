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
            $user = User::find($auth->id);

            $recent = [];

            if (method_exists($user, 'transactions')) {
                $recent = $user->transactions()
                    ->orderBy('date', 'desc')
                    ->limit(5)
                    ->get();
            }

            return response()->json([
                'auth' => [$user],
                'recent' => $recent,
            ]);
        }
        return view('user.dashboard');
    }

    public function getDashboardChart()
    {
        $userId = Auth::user()->id;

        $transactions = DB::table('transactions')
            ->select(
                DB::raw('DATE(created_at) as txn_date'),
                DB::raw("SUM(CASE WHEN type = 'deposit' THEN amount ELSE 0 END) as deposits"),
                DB::raw("SUM(CASE WHEN type = 'withdraw' THEN amount ELSE 0 END) as withdrawals"),
                DB::raw("SUM(CASE WHEN type = 'loan repayment' THEN amount ELSE 0 END) as loan_repayments")
            )
            ->where('user_id', $userId)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('txn_date', 'asc')
            ->get();

        return response()->json($transactions);
    }
}
