<x-admin-layout :title="__('admin/products.page.edit_title', ['name' => $product->name])">
    @include('admin.products._form')
</x-admin-layout>
