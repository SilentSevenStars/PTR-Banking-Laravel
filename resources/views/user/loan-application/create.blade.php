@extends('layouts.app')

@section('page-content')
@include('layouts.navigation')
<main class="flex-1 p-6 overflow-y-auto">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-xl p-6 border">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Loan Application Form</h2>

            <form action="{{ route('loan-application.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Personal Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name (Optional)</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="civil_status" class="block text-sm font-medium text-gray-700">Civil Status</label>
                            <select id="civil_status" name="civil_status" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Status</option>
                                <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality</label>
                            <input type="text" id="nationality" name="nationality" value="{{ old('nationality') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="present_address" class="block text-sm font-medium text-gray-700">Present Address</label>
                            <input type="text" id="present_address" name="present_address" value="{{ old('present_address') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="permanent_address" class="block text-sm font-medium text-gray-700">Permanent Address</label>
                            <input type="text" id="permanent_address" name="permanent_address" value="{{ old('permanent_address') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Employment Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="employment_status" class="block text-sm font-medium text-gray-700">Employment Status</label>
                            <select id="employment_status" name="employment_status" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Status</option>
                                <option value="employed" {{ old('employment_status') == 'employed' ? 'selected' : '' }}>Employed</option>
                                <option value="self-employed" {{ old('employment_status') == 'self-employed' ? 'selected' : '' }}>Self-Employed</option>
                                <option value="business-owner" {{ old('employment_status') == 'business-owner' ? 'selected' : '' }}>Business Owner</option>
                            </select>
                        </div>
                        <div>
                            <label for="years_employed" class="block text-sm font-medium text-gray-700">Years Employed</label>
                            <input type="number" id="years_employed" name="years_employed" value="{{ old('years_employed') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="monthly_income" class="block text-sm font-medium text-gray-700">Monthly Income</label>
                            <input type="number" id="monthly_income" name="monthly_income" value="{{ old('monthly_income') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="company_address" class="block text-sm font-medium text-gray-700">Company Address</label>
                            <input type="text" id="company_address" name="company_address" value="{{ old('company_address') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="company_phone" class="block text-sm font-medium text-gray-700">Company Phone</label>
                            <input type="text" id="company_phone" name="company_phone" value="{{ old('company_phone') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Required Documents -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Required Documents</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="valid_id_type" class="block text-sm font-medium text-gray-700">Valid ID Type</label>
                            <select id="valid_id_type" name="valid_id_type" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select ID Type</option>
                                <option value="passport" {{ old('valid_id_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                                <option value="drivers_license" {{ old('valid_id_type') == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                                <option value="sss" {{ old('valid_id_type') == 'sss' ? 'selected' : '' }}>SSS ID</option>
                                <option value="national_id" {{ old('valid_id_type') == 'national_id' ? 'selected' : '' }}>National ID</option>
                                <option value="voters_id" {{ old('valid_id_type') == 'voters_id' ? 'selected' : '' }}>Voter's ID</option>
                            </select>
                        </div>
                        <div>
                            <label for="valid_id_number" class="block text-sm font-medium text-gray-700">Valid ID Number</label>
                            <input type="text" id="valid_id_number" name="valid_id_number" value="{{ old('valid_id_number') }}" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="valid_id_front" class="block text-sm font-medium text-gray-700">Valid ID (Front)</label>
                                <input type="file" id="valid_id_front" name="valid_id_front" accept="image/*" required
                                    class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100">
                            </div>
                            <div>
                                <label for="valid_id_back" class="block text-sm font-medium text-gray-700">Valid ID (Back)</label>
                                <input type="file" id="valid_id_back" name="valid_id_back" accept="image/*" required
                                    class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="proof_of_income" class="block text-sm font-medium text-gray-700">Proof of Income</label>
                                <input type="file" id="proof_of_income" name="proof_of_income" accept=".pdf,image/*" required
                                    class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100">
                                <p class="mt-1 text-sm text-gray-500">Upload latest payslip or income statement (PDF or Image)</p>
                            </div>
                            <div>
                                <label for="proof_of_billing" class="block text-sm font-medium text-gray-700">Proof of Billing</label>
                                <input type="file" id="proof_of_billing" name="proof_of_billing" accept=".pdf,image/*" required
                                    class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100">
                                <p class="mt-1 text-sm text-gray-500">Upload recent utility bill (PDF or Image)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="history.back()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-500 transition">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection