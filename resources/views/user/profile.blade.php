@extends('layouts.app')

@section('page-content')
@include('layouts.navigation')
<main class="flex-1 p-6 overflow-y-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">My Profile</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6 border lg:col-span-1">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-2xl font-semibold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div id="display_name" class="text-xl font-semibold text-gray-900">{{ $user->name }}</div>
                    <div id="display_email" class="text-sm text-gray-500">{{ $user->email }}</div>
                    <div class="text-xs mt-1 inline-block px-2 py-0.5 rounded bg-gray-100 text-gray-700">Role: {{ ucfirst($user->role) }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Account Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-gray-500">Full Name</div>
                    <div id="display_name" class="text-base font-medium text-gray-900">{{ $user->name }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Email</div>
                    <div id="display_email" class="text-base font-medium text-gray-900 break-all">{{ $user->email }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Phone</div>
                    <div id="display_phone" class="text-base font-medium text-gray-900">{{ $user->phone ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Address</div>
                    <div id="display_address" class="text-base font-medium text-gray-900">{{ $user->address ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Current Balance</div>
                    <div class="text-base font-semibold text-indigo-700">₱{{ number_format((float)($user->balance ?? 0), 2) }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Member Since</div>
                    <div class="text-base font-medium text-gray-900">{{ optional($user->created_at)->format('M d, Y') }}</div>
                </div>
            </div>

            <div class="mt-6">
                <button data-modal-target="edit-profile" data-modal-toggle="edit-profile" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500">Edit Profile</button>
            </div>
        </div>
    </div>
</main>

<!-- Edit modal -->
<div id="edit-profile" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Edit Profile
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-profile">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" name="name" id="edit_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Full Name" required>
                    </div>
                    <div class="col-span-2">
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                        <input type="text"
                            name="phone"
                            id="edit_phone"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            placeholder="912 123 1234"
                            maxlength="12"
                            pattern="^[9][0-9]{2}\s[0-9]{3}\s[0-9]{4}$"
                            required>
                    </div>
                    <div class="col-span-2">
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                        <input type="text" name="address" id="edit_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Address" required>
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Update Profile
                </button>
            </form>
        </div>
    </div>
</div>

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
    const serverUser = {
        name: @json($user->name),
        email: @json($user->email),
        phone: @json($user->phone),
        address: @json($user->address)
    };

    function fillProfileModal() {
        $("#edit_name").val(serverUser.name ?? "");
        $("#edit_phone").val(serverUser.phone ?? "");
        $("#edit_address").val(serverUser.address ?? "");
    }

    fillProfileModal();

    $(document).on("click", '[data-modal-toggle="edit-profile"]', function(e) {
        fillProfileModal();
    });

    // Format phone number
    $(document).on("input", "#edit_phone", function() {
        let val = $(this).val().replace(/\D/g, "");
        if (val.length > 0 && val[0] !== "9") val = "9" + val.slice(1);
        if (val.length > 3 && val.length <= 6)
            val = val.slice(0, 3) + " " + val.slice(3);
        else if (val.length > 6)
            val = val.slice(0, 3) + " " + val.slice(3, 6) + " " + val.slice(6, 10);
        $(this).val(val);
    });

    // Submit update
    $(document).on("submit", "#edit-profile form", function(e) {
        e.preventDefault();

        const phone = $("#edit_phone").val();
        const phonePattern = /^[9][0-9]{2}\s[0-9]{3}\s[0-9]{4}$/;
        if (!phonePattern.test(phone)) {
            alert("Invalid phone format. Use: 912 123 1234");
            return;
        }

        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('user.update') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                _method: "PUT",
                name: $("#edit_name").val(),
                phone: phone,
                address: $("#edit_address").val()
            },
            success: function(res) {
                $btn.prop('disabled', false);
                if (res.success) {
                    $("#edit-profile").addClass("hidden").removeClass("flex");
                    $("body").removeClass("overflow-hidden");

                    $("#successModal").removeClass("hidden").addClass("flex");

                    $("#display_name").text($("#edit_name").val());
                    $("#display_email").text(serverUser.email ?? "");
                    $("#display_phone").text($("#edit_phone").val());
                    $("#display_address").text($("#edit_address").val());

                    Object.assign(serverUser, {
                        name: $("#edit_name").val(),
                        phone: $("#edit_phone").val(),
                        address: $("#edit_address").val()
                    });
                } else {
                    alert("Update failed");
                }
            },
            error: function() {
                $btn.prop('disabled', false);
                alert("Something went wrong.");
            }
        });
    });

    // 🧹 Remove leftover modal backgrounds
    function removeBackdrops() {
        $(".modal-backdrop").remove();
        $("body > div.fixed.inset-0.z-40").remove();
        $("body").removeClass("overflow-hidden");
    }

    // Close success modal
    $("#closeModal").on("click", function() {
        $("#successModal").addClass("hidden").removeClass("flex");
        $("#edit-profile").addClass("hidden").removeClass("flex");
        removeBackdrops(); // clean dark overlay
    });
});
</script>

@endsection