<x-admin-layout :title="__('admin/categories.page.edit_title', ['name' => $category->name])">
    @include('admin.categories._form')
</x-admin-layout>
