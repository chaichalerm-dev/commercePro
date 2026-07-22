<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\BannerPosition;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\ActivityLog;
use App\Models\Banner;
use App\Support\HomeCache;
use App\Support\ImageOptimizer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BannerController extends Controller
{
    public function index(): View
    {
        $positions = BannerPosition::cases();

        return view('admin.banners.index', [
            'positions' => $positions,
            'bannersByPosition' => collect($positions)->mapWithKeys(
                fn (BannerPosition $position) => [$position->value => Banner::query()->position($position)->get()],
            ),
        ]);
    }

    public function create(): View
    {
        return view('admin.banners.create', ['positions' => BannerPosition::cases()]);
    }

    public function store(BannerRequest $request): RedirectResponse
    {
        $banner = Banner::query()->create($this->payload($request));

        ActivityLog::record('banner.created', $banner, ['title' => $banner->title]);
        HomeCache::forget();

        return redirect()->route('admin.banners.index')->with('success', __('admin/banners.flash.created', ['title' => $banner->title]));
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', ['banner' => $banner, 'positions' => BannerPosition::cases()]);
    }

    public function update(BannerRequest $request, Banner $banner): RedirectResponse
    {
        $banner->update($this->payload($request, $banner));

        ActivityLog::record('banner.updated', $banner, ['title' => $banner->title]);
        HomeCache::forget();

        return redirect()->route('admin.banners.index')->with('success', __('admin/banners.flash.updated', ['title' => $banner->title]));
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        ImageOptimizer::delete($banner->image, config('filesystems.default'));
        $banner->delete();

        ActivityLog::record('banner.deleted', $banner, ['title' => $banner->title]);
        HomeCache::forget();

        return back()->with('success', __('admin/banners.flash.deleted', ['title' => $banner->title]));
    }

    /**
     * @return array<string, mixed>
     */
    protected function payload(BannerRequest $request, ?Banner $banner = null): array
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = ImageOptimizer::store($request->file('image'), 'banners', config('filesystems.default'), maxWidth: 1920, maxHeight: 800, replacing: $banner?->image);
        }

        return $data;
    }
}
