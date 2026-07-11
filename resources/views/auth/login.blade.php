<x-guest-layout>
    <div x-data="{ tab: 'user' }">
        <h1 class="text-center text-lg font-bold text-gray-900">เข้าสู่ระบบ</h1>

        {{-- User / Admin tabs (both submit the same endpoint; the server
             redirects by role — the tabs mirror the mockup and prefill the
             matching demo account) --}}
        <div class="mt-4 grid grid-cols-2 border-b border-gray-100 text-sm font-medium">
            <button type="button" @click="tab = 'user'"
                    :class="tab === 'user' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-400 hover:text-gray-600'"
                    class="flex items-center justify-center gap-2 border-b-2 pb-3 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                User Login
            </button>
            <button type="button" @click="tab = 'admin'"
                    :class="tab === 'admin' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-400 hover:text-gray-600'"
                    class="flex items-center justify-center gap-2 border-b-2 pb-3 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                Admin Login
            </button>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mt-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       :placeholder="tab === 'admin' ? 'admin@example.com' : 'user@example.com'"
                       class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4" x-data="{ show: false }">
                <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                <div class="relative mt-1.5">
                    <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                           placeholder="กรอกรหัสผ่าน"
                           class="block w-full rounded-xl border-gray-200 pr-10 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600" aria-label="แสดงรหัสผ่าน">
                        <svg class="h-4.5 w-4.5" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4 flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="rounded border-gray-300 text-primary-500 shadow-sm focus:ring-primary-400">
                    <span class="ms-2 text-sm text-gray-600">จดจำฉัน</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 hover:underline">ลืมรหัสผ่าน?</a>
                @endif
            </div>

            <button type="submit"
                    class="mt-5 w-full rounded-xl bg-primary-500 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">
                เข้าสู่ระบบ
            </button>
        </form>

        <div class="mt-5 flex items-center gap-3 text-xs text-gray-400">
            <span class="h-px flex-1 bg-gray-100"></span>หรือ<span class="h-px flex-1 bg-gray-100"></span>
        </div>

        <a href="{{ route('register') }}"
           class="mt-4 block w-full rounded-xl border border-primary-500 py-2.5 text-center text-sm font-semibold text-primary-600 transition hover:bg-primary-50">
            สมัครสมาชิก
        </a>

        <p class="mt-5 rounded-xl bg-gray-50 p-3 text-center text-xs leading-relaxed text-gray-500">
            บัญชีทดลอง — User: user@example.com<br>Admin: admin@example.com (รหัสผ่าน: password)
        </p>
    </div>
</x-guest-layout>
