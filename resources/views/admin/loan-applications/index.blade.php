@extends('layouts.app')

@section('page-content')
@include('layouts.admin-nav')
<main class="flex-1 p-6 overflow-y-auto bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 shadow-lg rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Loan Applications</h2>
                    <p class="text-indigo-100">Review and manage loan applications</p>
                </div>
                <img src="{{ asset('image/logo.png') }}" alt="Bank Logo" class="h-16 w-auto object-contain bg-white rounded-lg p-2">
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pending Review</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $applications->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center">
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Approved</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $applications->where('status', 'approved')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center">
                    <div class="p-3 bg-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Declined</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $applications->where('status', 'declined')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">All Applications</h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Total: {{ $applications->total() }}</span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Applicant
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Contact
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Employment
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Date Applied
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($applications as $application)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-semibold text-sm">
                                                {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $application->first_name }} {{ $application->middle_name }} {{ $application->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $application->nationality }} • {{ ucfirst($application->civil_status) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    {{ $application->email }}
                                </div>
                                <div class="text-sm text-gray-500 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    {{ $application->contact_number }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ ucfirst($application->employment_status) }}</div>
                                <div class="text-sm text-green-600 font-semibold">₱{{ number_format($application->monthly_income, 2) }}/mo</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $application->status === 'declined' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $application->created_at->format('M j, Y') }}
                                </div>
                              
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('loan-applications.show', $application) }}" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No loan applications found</p>
                                <p class="text-gray-400 text-sm mt-1">Applications will appear here once submitted</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $applications->links() }}
            </div>
            @endif
        </div>
    </div>
</main>
@endsection