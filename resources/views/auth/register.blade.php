<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 overflow-hidden">
        <x-validation-errors class="mb-4" />
        <div class="flex bg-white rounded-2xl shadow-2xl overflow-hidden max-w-4xl w-full h-[650px]">

            <div class="hidden md:flex w-1/2">
                <img src="{{ asset('image/logo.png') }}" alt="Bank Background" class="w-full h-full object-cover">
            </div>

            <div class="flex flex-col justify-center items-center w-full md:w-1/2 p-6 text-center">
                <h2 class="text-lg font-semibold text-blue-900 mb-4">CREATE YOUR ACCOUNT</h2>

                <form method="POST" action="{{ route('register') }}" class="w-full max-w-xs space-y-3">
                    @csrf

                    <div class="relative">
                        <input type="text" name="name" placeholder="Full Name" :value="old('name')" required autofocus autocomplete="name"
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>


                    <div class="relative">
                        <input type="tel" name="phone" id="phone" :value="old('phone')" placeholder="9xx xxx xxx" required maxlength="12"
                            pattern="[0-9]{3} [0-9]{3} [0-9]{4}"
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <i class="fas fa-id-card"></i>
                        </span>
                    </div>
    <script>
        // Phone input mask: 912 345 5678
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function (e) {
                    let value = phoneInput.value.replace(/\D/g, '');
                    if (value.length > 3 && value.length <= 6) {
                        value = value.replace(/(\d{3})(\d+)/, '$1 $2');
                    } else if (value.length > 6) {
                        value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1 $2 $3');
                    }
                    phoneInput.value = value;
                });
            }
        });
    </script>

                    <div class="relative">
                        <input type="text" name="address" placeholder="Address" :value="old('address')" required
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <i class="fas fa-id-card"></i>
                        </span>
                    </div>

                    <div class="relative">
                        <input type="email" name="email" placeholder="Email Address" :value="old('email')" required autofocus autocomplete="username"
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>

                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Password" required autocomplete="new-password"
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 cursor-pointer" onclick="togglePassword()">
                            <i id="eye-icon" class="fas fa-eye"></i>
                        </span>
                    </div>

                    <div class="relative">
                        <input type="password" name="password_confirmation" id="confirm_password" placeholder="Confirm Password" required autocomplete="new-password"
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 cursor-pointer" onclick="toggleConfirmPassword()">
                            <i id="eye-icon-confirm" class="fas fa-eye"></i>
                        </span>
                    </div>

                    <input type="submit" value="Register" name="register"
                        class="w-full bg-blue-900 text-white py-2 rounded-lg shadow-md hover:bg-blue-800 transition">

                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
                    </p>

                    <div class="flex items-center my-3">
                        <hr class="flex-grow border-gray-300">
                        <span class="mx-2 text-gray-500 text-sm">or</span>
                        <hr class="flex-grow border-gray-300">
                    </div>

                    <div class="space-y-2">
                        <a href="#"
                            class="flex items-center justify-center gap-2 w-full bg-white border border-gray-300 text-gray-700 py-3 rounded-lg shadow-sm hover:bg-gray-100 transition">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                            <span>Sign in with Google</span>
                        </a>

                        <a href="#"
                            class="flex items-center justify-center gap-2 w-full bg-blue-600 text-white py-3 rounded-lg shadow-md hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                            <span>Sign in with Facebook</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function toggleConfirmPassword() {
            const password = document.getElementById('confirm_password');
            const eyeIcon = document.getElementById('eye-icon-confirm');
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</x-guest-layout>