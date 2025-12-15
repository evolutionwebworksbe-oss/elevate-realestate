<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4">
        
        <!-- Logo -->
        <div class="mb-6">
            <img src="{{ asset('portal/img/logo.png') }}" alt="Company Logo" class="h-20 mx-auto">
        </div>

        <!-- Heading -->
        <h2 class="text-2xl font-bold text-gray-800">Welcome Back</h2>
        <p class="text-gray-500 text-sm mb-8">Sign in to continue to your account</p>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required autofocus autocomplete="username"
                       class="mt-1 w-full rounded-md border-2 border-[#0074A8] focus:ring-[#8DC63F] focus:border-[#8DC63F] p-3">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" 
                       type="password" 
                       name="password" 
                       required autocomplete="current-password"
                       class="mt-1 w-full rounded-md border-2 border-[#0074A8] focus:ring-[#8DC63F] focus:border-[#8DC63F] p-3">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" 
                       type="checkbox" 
                       name="remember"
                       class="rounded border-gray-300 text-[#0074A8] focus:ring-[#8DC63F]">
                <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit" 
                        class="w-full bg-[#0074A8] hover:bg-[#005f87] text-white py-3 rounded-md font-medium transition">
                    Log in
                </button>
            </div>
        </form>

    </div>
</x-guest-layout>
