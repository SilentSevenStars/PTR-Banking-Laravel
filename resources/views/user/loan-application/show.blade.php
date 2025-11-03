@extends('layouts.app')

@section('page-content')
@include('layouts.navigation')
<main class="flex-1 p-6 overflow-y-auto bg-gray-50">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Loan Application Details</h2>
                        <p class="text-blue-100">Application ID: #{{ str_pad($loanApplication->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-lg text-sm font-semibold shadow-lg
                        @if($loanApplication->status === 'approved') bg-green-500 text-white
                        @elseif($loanApplication->status === 'declined') bg-red-500 text-white
                        @else bg-yellow-500 text-white
                        @endif">
                        {{ ucfirst($loanApplication->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Status Messages -->
                @if($loanApplication->status === 'approved')
                    <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-green-900">Application Approved!</p>
                                <p class="text-green-800 mt-1">
                                    Congratulations! Your loan application has been approved. 
                                    <a href="{{ route('loan') }}" class="font-semibold underline hover:text-green-600">
                                        Apply for loan now →
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($loanApplication->status === 'declined')
                    <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-red-900">Application Declined</p>
                                <p class="text-red-800 mt-1">
                                    Your loan application has been declined.
                                    @if($loanApplication->admin_notes)
                                        <br>
                                        <strong class="block mt-2">Reason:</strong> {{ $loanApplication->admin_notes }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-yellow-900">Under Review</p>
                                <p class="text-yellow-800 mt-1">
                                    Your application is currently under review. We will notify you once a decision has been made.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Application Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Personal Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Full Name</span>
                                <span class="font-medium text-gray-900 text-right">{{ $loanApplication->first_name }} {{ $loanApplication->middle_name }} {{ $loanApplication->last_name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Birth Date</span>
                                <span class="font-medium text-gray-900">{{ $loanApplication->birth_date->format('F j, Y') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Civil Status</span>
                                <span class="font-medium text-gray-900">{{ ucfirst($loanApplication->civil_status) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Nationality</span>
                                <span class="font-medium text-gray-900">{{ $loanApplication->nationality }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Contact Number</span>
                                <span class="font-medium text-gray-900">{{ $loanApplication->contact_number }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Email</span>
                                <span class="font-medium text-gray-900 text-right break-all">{{ $loanApplication->email }}</span>
                            </div>
                            <div class="py-2">
                                <span class="text-gray-600 block mb-1">Present Address</span>
                                <span class="font-medium text-gray-900">{{ $loanApplication->present_address }}</span>
                            </div>
                            <div class="py-2">
                                <span class="text-gray-600 block mb-1">Permanent Address</span>
                                <span class="font-medium text-gray-900">{{ $loanApplication->permanent_address }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                            </svg>
                            Employment Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Status</span>
                                <span class="font-medium text-gray-900">{{ ucfirst($loanApplication->employment_status) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Company</span>
                                <span class="font-medium text-gray-900 text-right">{{ $loanApplication->company_name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Position</span>
                                <span class="font-medium text-gray-900 text-right">{{ $loanApplication->position }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Years Employed</span>
                                <span class="font-medium text-gray-900">{{ $loanApplication->years_employed }} years</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Monthly Income</span>
                                <span class="font-semibold text-green-600">₱{{ number_format($loanApplication->monthly_income, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Company Phone</span>
                                <span class="font-medium text-gray-900">{{ $loanApplication->company_phone }}</span>
                            </div>
                            <div class="py-2">
                                <span class="text-gray-600 block mb-1">Company Address</span>
                                <span class="font-medium text-gray-900">{{ $loanApplication->company_address }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                        Submitted Documents
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Valid ID -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="font-semibold text-gray-900">Valid ID</h4>
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ ucfirst(str_replace('_', ' ', $loanApplication->valid_id_type)) }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <p class="text-xs text-gray-500 mb-2">Front</p>
                                <div class="aspect-[3/2] bg-gray-100 rounded overflow-hidden">
                                    <img src="{{ Storage::url($loanApplication->valid_id_front_path) }}" 
                                        alt="ID Front" 
                                        class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition"
                                        onclick="window.open(this.src, '_blank')">
                                </div>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <p class="text-xs text-gray-500 mb-2">Back</p>
                                <div class="aspect-[3/2] bg-gray-100 rounded overflow-hidden">
                                    <img src="{{ Storage::url($loanApplication->valid_id_back_path) }}" 
                                        alt="ID Back" 
                                        class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition"
                                        onclick="window.open(this.src, '_blank')">
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 bg-white rounded px-3 py-2 border border-gray-200">
                                <span class="font-medium">ID Number:</span> {{ $loanApplication->valid_id_number }}
                            </p>
                        </div>

                        <!-- Proof of Income -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-900">Proof of Income</h4>
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                @php
                                    $extension = pathinfo(Storage::url($loanApplication->proof_of_income_path), PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="aspect-[3/2] bg-gray-100 rounded overflow-hidden">
                                        <img src="{{ Storage::url($loanApplication->proof_of_income_path) }}" 
                                            alt="Proof of Income" 
                                            class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition"
                                            onclick="window.open(this.src, '_blank')">
                                    </div>
                                @else
                                    <div class="aspect-[3/2] bg-gray-100 rounded flex items-center justify-center">
                                        <a href="{{ Storage::url($loanApplication->proof_of_income_path) }}" 
                                            target="_blank"
                                            class="inline-flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                            </svg>
                                            View Document
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Proof of Billing -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-900">Proof of Billing</h4>
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                @php
                                    $extension = pathinfo(Storage::url($loanApplication->proof_of_billing_path), PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="aspect-[3/2] bg-gray-100 rounded overflow-hidden">
                                        <img src="{{ Storage::url($loanApplication->proof_of_billing_path) }}" 
                                            alt="Proof of Billing" 
                                            class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition"
                                            onclick="window.open(this.src, '_blank')">
                                    </div>
                                @else
                                    <div class="aspect-[3/2] bg-gray-100 rounded flex items-center justify-center">
                                        <a href="{{ Storage::url($loanApplication->proof_of_billing_path) }}" 
                                            target="_blank"
                                            class="inline-flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                            </svg>
                                            View Document
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review Information -->
                @if($loanApplication->reviewed_at)
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center text-sm text-blue-800">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span>
                            <strong>Reviewed on:</strong> {{ $loanApplication->reviewed_at->format('F j, Y g:i A') }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection