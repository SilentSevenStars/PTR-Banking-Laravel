<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoanApplicationController extends Controller
{
    public function index()
    {
        $applications = LoanApplication::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.loan-applications.index', compact('applications'));
    }

    public function show(LoanApplication $application)
    {
        return view('admin.loan-applications.show', compact('application'));
    }

    public function review(Request $request, LoanApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,declined',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'reviewed_at' => now(),
            'reviewed_by' => Auth::user()->id,
        ]);

        return redirect()->route('loan-applications.index')
            ->with('success', 'Loan application has been ' . $validated['status']);
    }
}
