<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        return view('user.transaction');
    }

    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:1'],
            'type' => ['required', 'in:deposit,withdraw,loan repayment'],
        ]);

        if ($validate->fails()) {
            return response()->json(["errors" => $validate->errors()]);
        }

        DB::beginTransaction();
        try {
            $auth = Auth::user();
            $user = User::find($auth->id);

            $amount = floatval($request->amount);

            // Calculate new balance
            if ($request->type == 'deposit') {
                $user->balance += $amount;
            } elseif ($request->type == 'withdraw') {
                if ($amount > $user->balance) {
                    return response()->json(["errors" => ["balance" => ["Insufficient balance."]]]);
                }
                $user->balance -= $amount;
            }

            // Create transaction
            $transact = Transaction::create([
                'user_id' => $auth->id,
                'type' => $request->type,
                'amount' => $amount,
                'status' => 'success',
            ]);

            $user->save();
            DB::commit();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetch(Request $request)
    {
        $query = Transaction::query()
            ->where('user_id', Auth::id());

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$request->from_date, $request->to_date]);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->all());

        return response()->json($transactions);
    }


    public function export(Request $request)
    {
        $userId = Auth::id();

        $query = Transaction::query()
            ->where('user_id', $userId);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$request->from_date, $request->to_date]);
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->get(['id', 'type', 'amount', 'status', 'created_at']);

        $filename = "transactions_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Type', 'Amount', 'Status', 'Date']);
            foreach ($transactions as $txn) {
                fputcsv($file, [
                    $txn->id,
                    ucfirst($txn->type),
                    number_format($txn->amount, 2),
                    ucfirst($txn->status),
                    $txn->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function viewReceipt($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'id' => $transaction->id,
            'type' => ucfirst($transaction->type),
            'amount' => number_format($transaction->amount, 2),
            'status' => ucfirst($transaction->status),
            'date' => $transaction->created_at->format('F d, Y h:i A'),
        ]);
    }

    public function downloadReceipt($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.receipt', compact('transaction'));
        return $pdf->download("transaction_receipt_{$id}.pdf");
    }

    public function showReceipt($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.receipt', compact('transaction'));
    }
}
