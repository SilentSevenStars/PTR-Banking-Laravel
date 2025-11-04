<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanApplicationController extends Controller
{
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        // Prevent creating multiple applications: if user has a pending or approved application, redirect appropriately
        $existing = $user->loanApplications()->whereIn('status', ['pending', 'approved'])->latest()->first();
        if ($existing) {
            if ($existing->status === 'approved') {
                // If already approved, direct user to loan application show page with instruction to apply for loan
                return redirect()->route('user.loan-applications.show', $existing)
                    ->with('info', 'You already have an approved application. You may now apply for a loan.');
            }

            // If pending or other status, show the existing application instead of allowing a new one
            return redirect()->route('loan-application.show', $existing)
                ->with('info', 'You already submitted a loan application which is under review.');
        }

        return view('user.loan-application.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'civil_status' => 'required|in:single,married,divorced,widowed',
            'nationality' => 'required|string|max:255',
            'present_address' => 'required|string|max:500',
            'permanent_address' => 'required|string|max:500',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            
            'employment_status' => 'required|in:employed,self-employed,business-owner',
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'company_phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'monthly_income' => 'required|numeric|min:1',
            'years_employed' => 'required|integer|min:0',
            
            'valid_id_type' => 'required|in:passport,drivers_license,sss,national_id,voters_id',
            'valid_id_number' => 'required|string|max:255',
            'valid_id_front' => 'required|image|max:2048',
            'valid_id_back' => 'required|image|max:2048',
            'proof_of_income' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'proof_of_billing' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Prevent duplicate submissions: check again server-side
        /** @var User $user */
        $user = Auth::user();
        $existing = $user->loanApplications()->whereIn('status', ['pending', 'approved'])->exists();
        if ($existing) {
            return redirect()->route('user.loan-applications.index')
                ->with('error', 'You already have an active loan application. You cannot submit another one at this time.');
        }

        // Store files
        $validIdFrontPath = $request->file('valid_id_front')->store('loan-applications/ids', 'public');
        $validIdBackPath = $request->file('valid_id_back')->store('loan-applications/ids', 'public');
        $proofOfIncomePath = $request->file('proof_of_income')->store('loan-applications/income', 'public');
        $proofOfBillingPath = $request->file('proof_of_billing')->store('loan-applications/billing', 'public');

        // Create loan application
        $loanApplication = LoanApplication::create([
            'user_id' => Auth::user()->id,
            ...$validated,
            'valid_id_front_path' => $validIdFrontPath,
            'valid_id_back_path' => $validIdBackPath,
            'proof_of_income_path' => $proofOfIncomePath,
            'proof_of_billing_path' => $proofOfBillingPath,
        ]);

        return redirect()->route('loan-applications.show', $loanApplication)
            ->with('success', 'Loan application submitted successfully.');
    }

    public function show(LoanApplication $loanApplication)
    {
        // Ensure user can only view their own applications
        if ($loanApplication->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }
        return view('user.loan-application.show', compact('loanApplication'));
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $loanApplications = $user->loanApplications()
            ->latest()
            ->paginate(10);

        return view('loan-application.index', compact('loanApplications'));
    }
}
