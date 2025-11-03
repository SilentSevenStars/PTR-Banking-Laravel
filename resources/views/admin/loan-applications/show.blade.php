@extends('layouts.app')

@section('page-content')
@include('layouts.admin-nav')
<main class="flex-1 p-6 overflow-y-auto bg-gray-50">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Loan Application Review</h2>
                        <p class="text-indigo-100">Application ID: #{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-lg text-sm font-semibold shadow-lg
                        {{ $application->status === 'approved' ? 'bg-green-500 text-white' : '' }}
                        {{ $application->status === 'declined' ? 'bg-red-500 text-white' : '' }}
                        {{ $application->status === 'pending' ? 'bg-yellow-500 text-white' : '' }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Applicant Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Applicant Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Full Name</span>
                                <span class="font-medium text-gray-900 text-right">{{ $application->first_name }} {{ $application->middle_name }} {{ $application->last_name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Birth Date</span>
                                <span class="font-medium text-gray-900">{{ $application->birth_date->format('F j, Y') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Civil Status</span>
                                <span class="font-medium text-gray-900">{{ ucfirst($application->civil_status) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Nationality</span>
                                <span class="font-medium text-gray-900">{{ $application->nationality }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Contact Number</span>
                                <span class="font-medium text-gray-900">{{ $application->contact_number }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Email</span>
                                <span class="font-medium text-gray-900 text-right break-all">{{ $application->email }}</span>
                            </div>
                            <div class="py-2">
                                <span class="text-gray-600 block mb-1">Present Address</span>
                                <span class="font-medium text-gray-900">{{ $application->present_address }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Employment Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Status</span>
                                <span class="font-medium text-gray-900">{{ ucfirst($application->employment_status) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Company</span>
                                <span class="font-medium text-gray-900 text-right">{{ $application->company_name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Position</span>
                                <span class="font-medium text-gray-900 text-right">{{ $application->position }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Years Employed</span>
                                <span class="font-medium text-gray-900">{{ $application->years_employed }} years</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Monthly Income</span>
                                <span class="font-semibold text-green-600">₱{{ number_format($application->monthly_income, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Company Phone</span>
                                <span class="font-medium text-gray-900">{{ $application->company_phone }}</span>
                            </div>
                            <div class="py-2">
                                <span class="text-gray-600 block mb-1">Company Address</span>
                                <span class="font-medium text-gray-900">{{ $application->company_address }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-5">Submitted Documents</h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Valid ID -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="font-semibold text-gray-900">Valid ID</h4>
                                <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded">{{ ucfirst(str_replace('_', ' ', $application->valid_id_type)) }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <p class="text-xs text-gray-500 mb-2">Front</p>
                                <div class="aspect-[3/2] bg-gray-100 rounded overflow-hidden">
                                    <img src="{{ Storage::url($application->valid_id_front_path) }}" 
                                        alt="ID Front" 
                                        class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition"
                                        onclick="window.open(this.src, '_blank')">
                                </div>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <p class="text-xs text-gray-500 mb-2">Back</p>
                                <div class="aspect-[3/2] bg-gray-100 rounded overflow-hidden">
                                    <img src="{{ Storage::url($application->valid_id_back_path) }}" 
                                        alt="ID Back" 
                                        class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition"
                                        onclick="window.open(this.src, '_blank')">
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 bg-white rounded px-3 py-2 border border-gray-200">
                                <span class="font-medium">ID Number:</span> {{ $application->valid_id_number }}
                            </p>
                        </div>

                        <!-- Proof of Income -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-900">Proof of Income</h4>
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                @php
                                    $incomeExt = pathinfo(Storage::url($application->proof_of_income_path), PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array($incomeExt, ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="aspect-[3/2] bg-gray-100 rounded overflow-hidden">
                                        <img src="{{ Storage::url($application->proof_of_income_path) }}" 
                                            alt="Proof of Income" 
                                            class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition"
                                            onclick="window.open(this.src, '_blank')">
                                    </div>
                                @else
                                    <div class="aspect-[3/2] bg-gray-100 rounded flex items-center justify-center">
                                        <a href="{{ Storage::url($application->proof_of_income_path) }}" 
                                            target="_blank"
                                            class="inline-flex items-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
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
                                    $billingExt = pathinfo(Storage::url($application->proof_of_billing_path), PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array($billingExt, ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="aspect-[3/2] bg-gray-100 rounded overflow-hidden">
                                        <img src="{{ Storage::url($application->proof_of_billing_path) }}" 
                                            alt="Proof of Billing" 
                                            class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition"
                                            onclick="window.open(this.src, '_blank')">
                                    </div>
                                @else
                                    <div class="aspect-[3/2] bg-gray-100 rounded flex items-center justify-center">
                                        <a href="{{ Storage::url($application->proof_of_billing_path) }}" 
                                            target="_blank"
                                            class="inline-flex items-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                            View Document
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($application->status === 'pending')
                <!-- Review Form -->
                <div class="bg-white border-2 border-indigo-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-5">Review Application</h3>
                    <form action="{{ route('loan-applications.review', $application) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                Decision *
                            </label>
                            <select id="status" name="status" required
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="">Select Decision</option>
                                <option value="approved">Approve Application</option>
                                <option value="declined">Decline Application</option>
                            </select>
                        </div>

                        <div>
                            <label for="admin_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                Notes / Reason
                            </label>
                            <textarea id="admin_notes" name="admin_notes" rows="4"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                placeholder="Add any notes or reasons for your decision"></textarea>
                            <p class="mt-1 text-xs text-gray-500">This will be visible to the applicant</p>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <a href="{{ route('loan-applications.index') }}"
                                class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                                Back to List
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-medium shadow-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <!-- Review Details -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 border-2 border-gray-200">
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Review Details</h4>
                    @if($application->admin_notes)
                        <div class="bg-white rounded-lg p-4 mb-3 border border-gray-200">
                            <p class="text-sm font-medium text-gray-700 mb-1">Admin Notes:</p>
                            <p class="text-gray-900">{{ $application->admin_notes }}</p>
                        </div>
                    @endif
                    <div class="flex items-center text-sm text-gray-600">
                        <span>Reviewed on {{ $application->reviewed_at->format('F j, Y') }}</span>
                    </div>
                </div>
                
                <div class="flex justify-start">
                    <a href="{{ route('loan-applications.index') }}"
                        class="inline-flex items-center px-5 py-2.5 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                        Back to List
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection