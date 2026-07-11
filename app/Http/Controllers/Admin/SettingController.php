<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Editable settings grouped for the form; anything not listed here
     * cannot be written through this screen.
     */
    private const GROUPS = [
        'general' => ['site_name', 'tagline'],
        'contact' => ['contact_email', 'contact_phone', 'contact_address'],
        'social' => ['social_facebook', 'social_instagram', 'social_line', 'social_youtube'],
        'shop' => ['free_shipping_min', 'shipping_fee', 'currency'],
    ];

    private const FILE_KEYS = ['logo', 'favicon'];

    public function index(): View
    {
        $settings = Setting::query()->get()->keyBy('key');

        return view('admin.settings.index', [
            'groups' => self::GROUPS,
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_line' => ['nullable', 'url', 'max:255'],
            'social_youtube' => ['nullable', 'url', 'max:255'],
            'free_shipping_min' => ['required', 'numeric', 'min:0'],
            'shipping_fee' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico,svg', 'max:512'],
        ]);

        foreach (self::GROUPS as $group => $keys) {
            foreach ($keys as $key) {
                Setting::set($key, isset($validated[$key]) ? (string) $validated[$key] : null, $group);
            }
        }

        foreach (self::FILE_KEYS as $key) {
            if ($request->hasFile($key)) {
                $old = Setting::get($key);

                if (filled($old) && ! Str::startsWith((string) $old, ['http://', 'https://'])) {
                    Storage::disk('public')->delete((string) $old);
                }

                Setting::set($key, $request->file($key)->store('settings', 'public'), 'general');
            }
        }

        ActivityLog::record('settings.updated', null, ['keys' => array_keys($validated)]);

        return back()->with('success', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
    }
}
