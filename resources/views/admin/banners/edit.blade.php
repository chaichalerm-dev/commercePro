<x-admin-layout :title="__('admin/banners.page.edit_title', ['title' => $banner->title])">
    @include('admin.banners._form')
</x-admin-layout>
