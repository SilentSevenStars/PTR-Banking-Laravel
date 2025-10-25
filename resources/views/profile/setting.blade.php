@extends('layouts.app')

@section('page-content')
    @if(auth()->user()->role === 'admin')
        @include('layouts.admin-nav')
    @else
        @include('layouts.navigation')
    @endif

    <main class="flex-1 p-6 overflow-y-auto">
        <h2 class="text-2xl font-bold mb-6">Account Settings</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Change Email --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border">
                <h3 class="text-xl font-semibold mb-4">Change Email</h3>
                <form id="changeEmailForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-gray-700">Current Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled class="w-full p-2 border rounded bg-gray-100">
                    </div>
                    <div>
                        <label class="block text-gray-700">New Email</label>
                        <input type="email" name="email" id="newEmail" required class="w-full p-2 border rounded">
                        <span id="errorEmail" class="text-red-700 text-sm"></span>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">
                        Update Email
                    </button>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border">
                <h3 class="text-xl font-semibold mb-4">Change Password</h3>
                <form id="changePasswordForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-gray-700">Old Password</label>
                        <input type="password" name="old_password" id="oldPassword" required class="w-full p-2 border rounded">
                        <span id="errorOldPassword" class="text-red-700 text-sm"></span>
                    </div>
                    <div>
                        <label class="block text-gray-700">New Password</label>
                        <input type="password" name="new_password" id="newPassword" required class="w-full p-2 border rounded">
                        <span id="errorNewPassword" class="text-red-700 text-sm"></span>
                    </div>
                    <div>
                        <label class="block text-gray-700">Confirm New Password</label>
                        <input type="password" name="confirm_password" id="confirmPassword" required class="w-full p-2 border rounded">
                        <span id="errorConfirmPassword" class="text-red-700 text-sm"></span>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
                        Update Password
                    </button>
                </form>
            </div>
        </div>

        {{-- Social Connections --}}
        <div class="bg-white rounded-xl shadow-lg p-6 border mt-6">
            <h3 class="text-xl font-semibold mb-4">Social Accounts</h3>

            <div class="flex flex-col md:flex-row gap-4">
                {{-- Google --}}
                <div class="flex items-center justify-between bg-gray-50 border rounded p-4 w-full md:w-1/2">
                    <div class="flex items-center gap-3">
                        <i class="fab fa-google text-red-500 text-2xl"></i>
                        <span class="text-gray-800 font-medium">Google</span>
                    </div>
                    @if(auth()->user()->google_id)
                        <button id="disconnectGoogle" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500">
                            Disconnect
                        </button>
                    @else
                        <a href="{{ route('auth.redirect', 'google') }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500">
                            Connect
                        </a>
                    @endif
                </div>

                {{-- Facebook --}}
                <div class="flex items-center justify-between bg-gray-50 border rounded p-4 w-full md:w-1/2">
                    <div class="flex items-center gap-3">
                        <i class="fab fa-facebook text-blue-600 text-2xl"></i>
                        <span class="text-gray-800 font-medium">Facebook</span>
                    </div>
                    @if(auth()->user()->facebook_id)
                        <button id="disconnectFacebook" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500">
                            Disconnect
                        </button>
                    @else
                        <a href="{{ route('auth.redirect', 'facebook') }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500">
                            Connect
                        </a>
                    @endif
                </div>
            </div>
        </div></div>
    </main>

    {{-- Success Modal --}}
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 text-center">
            <h2 class="text-xl font-bold text-green-600 mb-4">✅ Success</h2>
            <p class="text-gray-700 mb-6" id="successMessage"></p>
            <button id="closeModal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">OK</button>
        </div>
    </div>

    <script type="module">
        $(document).ready(function() {

            // Change Email
            $("#changeEmailForm").on("submit", function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/settings/email",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $("#successMessage").text("Email updated successfully!");
                            $("#successModal").removeClass("hidden");
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors || {};
                        $("#errorEmail").text(errors.email ? errors.email[0] : "");
                    },
                });
            });

            $("#changePasswordForm").on("submit", function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/settings/password",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $("#successMessage").text("Password changed successfully!");
                            $("#successModal").removeClass("hidden");
                            $("#changePasswordForm")[0].reset();
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors || {};
                        $("#errorOldPassword").text(errors.old_password ? errors.old_password[0] : "");
                        $("#errorNewPassword").text(errors.new_password ? errors.new_password[0] : "");
                        $("#errorConfirmPassword").text(errors.confirm_password ? errors.confirm_password[0] : "");
                    },
                });
            });

            $("#disconnectGoogle").on("click", function() {
                $.ajax({
                    url: "/settings/disconnect/google",
                    method: "POST",
                    data: {_token: "{{ csrf_token() }}"},
                    success: function(res) {
                        location.reload();
                    }
                });
            });

            $("#disconnectFacebook").on("click", function() {
                $.ajax({
                    url: "/settings/disconnect/facebook",
                    method: "POST",
                    data: {_token: "{{ csrf_token() }}"},
                    success: function(res) {
                        location.reload();
                    }
                });
            });

            $("#closeModal").on("click", function() {
                $("#successModal").addClass("hidden");
            });
        });
    </script>
@endsection
