<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex bg-white rounded-2xl shadow-2xl overflow-hidden max-w-5xl w-full h-[650px]">

        <div class="hidden md:flex w-1/2">
            <img src="{{ asset('image/logo.png') }}" alt="Bank Background" class="w-full h-full object-cover" />
        </div>

        <div class="flex flex-col justify-center items-center w-full md:w-1/2 p-8 text-center">
            <h2 class="text-xl font-semibold text-blue-900 mb-6">WELCOME BACK</h2>

            <form method="POST" action="{{ route('login') }}" class="w-full max-w-xs space-y-4">
                @csrf
                <input type="hidden" name="action" value="login">

                <div class="relative">
                    <input type="email" name="email" placeholder="Email" required
                        class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                        <i class="fas fa-envelope"></i>
                    </span>
                </div>

                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="Password" required
                        class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 cursor-pointer" onclick="togglePassword()">
                        <i id="eye-icon" class="fas fa-eye"></i>
                    </span>
                </div>

                <input type="submit" name="login" value="Login"
                    class="w-full bg-blue-900 text-white py-3 rounded-lg shadow-md hover:bg-blue-800 transition">

                <div class="flex justify-end mt-2 text-sm">
                    <a href="{{ route('password.request' )}}" class="text-blue-600 hover:underline">Forgot Password?</a>
                </div>
                <div class="flex mt-2 text-sm justify-center">
                    Doesn't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline ml-1">Sign in</a>
                </div>

                <div class="flex items-center my-4">
                    <hr class="flex-grow border-gray-300">
                    <span class="mx-2 text-gray-500 text-sm">or</span>
                    <hr class="flex-grow border-gray-300">
                </div>

                <div class="space-y-3">
                    <a href="{{ route('auth.redirect', 'google') }}"
                        class="flex items-center justify-center gap-2 w-full bg-white border border-gray-300 text-gray-700 py-3 rounded-lg shadow-sm hover:bg-gray-100 transition">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                        <span>Sign in with Google</span>
                    </a>

                    <a href="{{ route('auth.redirect', 'facebook') }}"
                        class="flex items-center justify-center gap-2 w-full bg-blue-600 text-white py-3 rounded-lg shadow-md hover:bg-blue-700 transition">
                        <i class="fab fa-facebook-f"></i>
                        <span>Sign in with Facebook</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</x-guest-layout>