<x-guest-layout>
    <h1 class="text-center text-lg font-bold text-gray-900">สมัครสมาชิก</h1>
    <p class="mt-1 text-center text-sm text-gray-400">สมัครฟรี รับส่วนลดสมาชิกใหม่ทันที</p>

    <form method="POST" action="{{ route('register') }}" class="mt-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">ชื่อ-นามสกุล</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   placeholder="กรอกชื่อของคุณ"
                   class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   placeholder="กรอกอีเมล"
                   class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   placeholder="อย่างน้อย 8 ตัวอักษร"
                   class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">ยืนยันรหัสผ่าน</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   placeholder="กรอกรหัสผ่านอีกครั้ง"
                   class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
                class="mt-6 w-full rounded-xl bg-primary-500 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">
            สมัครสมาชิก
        </button>

        <p class="mt-4 text-center text-sm text-gray-500">
            มีบัญชีอยู่แล้ว?
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:underline">เข้าสู่ระบบ</a>
        </p>
    </form>
</x-guest-layout>
