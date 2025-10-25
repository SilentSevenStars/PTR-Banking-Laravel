@extends('layouts.app')

@section('page-content')
    @if(auth()->user()->role === 'admin')
        @include('layouts.admin-nav')
    @else
        @include('layouts.navigation')
    @endif

    <main class="flex-1 p-6 overflow-y-auto">
        <h2 class="text-2xl font-bold mb-6">My Profile</h2>

        <div class="bg-white rounded-xl shadow-lg p-6 border">
            <form method="POST" id="updateProfileForm" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name" class="w-full p-2 border rounded" required>
                    <span id="errorName" class="text-red-800 text-sm"></span>
                </div>

                <div>
                    <label class="block text-gray-700">Email (readonly)</label>
                    <input type="email" id="email" class="w-full p-2 border rounded bg-gray-100" disabled>
                </div>

                <div>
                    <label class="block text-gray-700">Phone</label>
                     <input type="tel" name="phone" id="phone" :value="old('phone')" placeholder="9xx xxx xxx" required maxlength="12"pattern="[0-9]{3} [0-9]{3} [0-9]{4}"class="w-full p-2 border rounded" />
                    <span id="errorPhone" class="text-red-800 text-sm"></span>
                </div>

                <div>
                    <label class="block text-gray-700">Address</label>
                    <input type="text" name="address" id="address" class="w-full p-2 border rounded">
                    <span id="errorAddress" class="text-red-800 text-sm"></span>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold">Current Balance:</label>
                    <p class="text-xl text-green-600 font-semibold" id="balance">₱0.00</p>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">
                    Update Profile
                </button>
            </form>
        </div>
    </main>

    <!-- Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 text-center">
            <h2 class="text-xl font-bold text-green-600 mb-4">✅ Profile Updated</h2>
            <p class="text-gray-700 mb-6">Your profile has been updated successfully!</p>
            <button id="closeModal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">
                OK
            </button>
        </div>
    </div>

    <script type="module">
        $(document).ready(function() {
            loadProfile();

            function loadProfile() {
                $.ajax({
                    url: "/profile",
                    method: "GET",
                    success: function(data) {
                        $("#name").val(data.name);
                        $("#email").val(data.email);
                        $("#phone").val(data.phone);
                        $("#address").val(data.address);
                        $("#balance").text("₱" + parseFloat(data.balance).toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                        }));
                    },
                    error: function(xhr) {
                        console.error("Profile load error:", xhr.responseText);
                        alert("Error loading profile data.");
                    },
                });
            }

            $("#phone").on("input", function() {
                let val = $(this).val().replace(/\D/g, ""); // remove non-numeric
                if (!val.startsWith("9")) {
                    val = "9" + val.replace(/^9*/, ""); // always start with 9
                }
                if (val.length > 10) val = val.substring(0, 10); // max 10 digits
                let formatted = val
                    .replace(/^(\d{3})(\d{0,3})(\d{0,4}).*/, function(_, a, b, c) {
                        return [a, b, c].filter(Boolean).join(" ");
                    });
                $(this).val(formatted);
            });


            $("#updateProfileForm").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "/profile",
                    method: "PUT",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $("#successModal").removeClass("hidden");
                            loadProfile();
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.error || {};
                        $("#errorName").text(errors.name ? errors.name[0] : "");
                        $("#errorPhone").text(errors.phone ? errors.phone[0] : "");
                        $("#errorAddress").text(errors.address ? errors.address[0] : "");
                    },
                });
            });

            $("#closeModal").on("click", function() {
                $("#successModal").addClass("hidden");
            });
        });
    </script>
@endsection
