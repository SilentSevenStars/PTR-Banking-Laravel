<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    public function index()
    {
        return view('user.loan');
    }

    public function list()
    {
        $loans = Loan::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($loans);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:1'],
            'term' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $loan = Loan::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'term' => $request->term,
            'interest_rate' => 5.00,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'loan' => $loan]);
    }
}
