<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class AdminLoanController extends Controller
{
    public function index()
    {
        return view('admin.loan');
    }

    public function list()
    {
        $loans = Loan::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($loans);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'status' => 'required|in:approved,rejected',
        ]);

        $loan = Loan::find($request->loan_id);
        $loan->status = $request->status;
        $loan->save();

        return response()->json(['success' => true, 'message' => 'Loan status updated successfully.']);
    }
}
