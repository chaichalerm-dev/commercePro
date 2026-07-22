<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Support\ImageOptimizer;
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
        'security' => ['show_demo_credentials'],
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
        $request->merge(['show_demo_credentials' => $request->boolean('show_demo_credentials')]);

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
            // No bare "image" rule here: Laravel's image rule only recognizes
            // jpg/jpeg/png/gif/bmp/webp and never ico, so it silently rejected
            // svg/ico uploads even though mimes explicitly allowed them.
            'logo' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'mimes:png,ico,svg', 'max:512'],
            'show_demo_credentials' => ['boolean'],
        ]);

        $validated['show_demo_credentials'] = $validated['show_demo_credentials'] ? '1' : '0';

        foreach (self::GROUPS as $group => $keys) {
            foreach ($keys as $key) {
                Setting::set($key, isset($validated[$key]) ? (string) $validated[$key] : null, $group);
            }
        }

        foreach (self::FILE_KEYS as $key) {
            if ($request->hasFile($key)) {
                $old = (string) Setting::get($key);
                $file = $request->file($key);
                $disk = config('filesystems.default');

                if (strtolower((string) $file->getClientOriginalExtension()) === 'svg') {
                    ImageOptimizer::delete($old, $disk);
                    $path = 'settings/'.Str::uuid()->toString().'.svg';
                    Storage::disk($disk)->put($path, self::sanitizeSvg((string) file_get_contents($file->getRealPath())), [
                        'CacheControl' => 'public, max-age=31536000, immutable',
                    ]);
                    Setting::set($key, $path, 'general');
                } elseif ($key === 'logo') {
                    Setting::set($key, ImageOptimizer::store($file, 'settings', $disk, maxWidth: 512, maxHeight: 512, quality: 90, replacing: $old), 'general');
                } else {
                    // Favicon is left untouched: it's already capped at 512KB
                    // by validation, and .ico isn't something GD can decode
                    // anyway, so there's nothing worth optimizing there.
                    ImageOptimizer::delete($old, $disk);
                    Setting::set($key, $file->store('settings', ['disk' => $disk, 'CacheControl' => 'public, max-age=31536000, immutable']), 'general');
                }
            }
        }

        ActivityLog::record('settings.updated', null, ['keys' => array_keys($validated)]);

        return back()->with('success', __('admin/settings.flash.updated'));
    }

    /**
     * Strips script-execution vectors from an uploaded SVG before it's
     * stored: SVG is XML, so a malicious upload can carry a <script>,
     * event-handler attributes (onload, onerror, ...), or a javascript:
     * URI, any of which can run when the file is opened directly in a
     * browser tab (SVGs served from local disk here bypass this app's
     * CSP entirely, since Apache serves /storage without going through
     * Laravel middleware).
     */
    private static function sanitizeSvg(string $svg): string
    {
        $svg = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $svg) ?? $svg;
        $svg = preg_replace('#<foreignObject\b[^>]*>.*?</foreignObject>#is', '', $svg) ?? $svg;
        $svg = preg_replace('#<(iframe|embed|object)\b[^>]*>.*?</\1>#is', '', $svg) ?? $svg;
        $svg = preg_replace('#\son\w+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)#i', '', $svg) ?? $svg;
        $svg = preg_replace('#(href|xlink:href)\s*=\s*(["\'])\s*javascript:.*?\2#i', '', $svg) ?? $svg;

        return $svg;
    }
}
