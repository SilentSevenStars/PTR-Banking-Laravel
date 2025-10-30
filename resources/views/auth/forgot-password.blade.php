<x-guest-layout>
    <div class="flex bg-white rounded-2xl shadow-2xl overflow-hidden max-w-5xl w-full h-[600px]">

    <div class="hidden md:flex w-1/2">
      <img src="{{ asset('image/logo.png') }}" alt="Bank Background" class="w-full h-full object-cover">
    </div>

    <div class="flex flex-col justify-center items-center w-full md:w-1/2 p-8 text-center">
      <img src="{{ asset('image/logo.png') }}" alt="Bank Logo" class="w-20 mb-4">
      <h2 class="text-xl font-semibold text-blue-900 mb-6">Forgot your password</h2>

      <form method="POST" action="{{ route('password.email') }}" class="w-full max-w-xs space-y-4">

        <div class="relative">
          <input type="email" name="email" placeholder="Enter your email address" required
                 class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
          <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
            <i class="fas fa-envelope"></i>
          </span>
        </div>

        <input type="submit" value="Send Email" name="forget-password"  class="w-full bg-blue-900 text-white py-3 rounded-lg shadow-md hover:bg-blue-800 transition">

        <p class="text-sm text-gray-600 mt-2">
          Already have an account? 
          <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
        </p>
      </form>
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const icon = document.getElementById('eye-icon');
      input.type = input.type === "password" ? "text" : "password";
      icon.classList.toggle("fa-eye-slash");
      icon.classList.toggle("fa-eye");
    }

    function toggleConfirmPassword() {
      const input = document.getElementById('confirm_password');
      const icon = document.getElementById('eye-icon-confirm');
      input.type = input.type === "password" ? "text" : "password";
      icon.classList.toggle("fa-eye-slash");
      icon.classList.toggle("fa-eye");
    }
  </script>
</x-guest-layout>
