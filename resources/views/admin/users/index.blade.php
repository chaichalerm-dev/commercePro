<x-admin-layout title="ผู้ใช้งาน">
    <form method="GET" class="flex flex-wrap items-center gap-2 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="ค้นหาชื่อ หรืออีเมล..."
               class="w-64 rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
        <select name="role" class="rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            <option value="">ทุกบทบาท</option>
            @foreach ($roles as $role)
                <option value="{{ $role->value }}" @selected(request('role') == $role->value)>{{ $role->label() }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">กรอง</button>
    </form>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">ผู้ใช้</th>
                        <th class="px-5 py-3 font-medium">บทบาท</th>
                        <th class="px-5 py-3 font-medium">สถานะ</th>
                        <th class="px-5 py-3 font-medium">สมัครเมื่อ</th>
                        <th class="px-5 py-3 text-right font-medium">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-5 py-3">
                                <p class="font-medium text-gray-800">{{ $user->name }} @if($user->is(auth()->user()))<span class="text-xs text-primary-500">(คุณ)</span>@endif</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </td>
                            <td class="px-5 py-3">
                                @if ($user->is(auth()->user()))
                                    <span class="rounded-full bg-violet-50 px-2.5 py-1 text-xs font-medium text-violet-600">{{ $user->role_id->label() }}</span>
                                @else
                                    <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                        @csrf @method('PATCH')
                                        <select name="role_id" onchange="this.form.submit()"
                                                class="rounded-lg border-gray-200 py-1 text-xs focus:border-primary-400 focus:ring-primary-400">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->value }}" @selected($user->role_id === $role)>{{ $role->label() }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $user->isBanned() ? 'bg-red-50 text-red-500' : 'bg-emerald-50 text-emerald-600' }}">
                                    {{ $user->status->label() }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-400">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-right">
                                @unless ($user->is(auth()->user()))
                                    <form method="POST" action="{{ route('admin.users.toggle-ban', $user) }}" class="inline"
                                          onsubmit="return confirm('{{ $user->isBanned() ? 'ปลดระงับบัญชีนี้?' : 'ระงับบัญชีนี้?' }}')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ $user->isBanned() ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-red-50 text-red-500 hover:bg-red-100' }}">
                                            {{ $user->isBanned() ? 'ปลดระงับ' : 'ระงับบัญชี' }}
                                        </button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">ไม่พบผู้ใช้</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-50 px-5 py-4">{{ $users->links() }}</div>
    </div>
</x-admin-layout>
