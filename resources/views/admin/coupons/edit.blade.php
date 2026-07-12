<x-admin-layout :title="__('admin/coupons.page.edit_title', ['code' => $coupon->code])">
    @include('admin.coupons._form')
</x-admin-layout>
