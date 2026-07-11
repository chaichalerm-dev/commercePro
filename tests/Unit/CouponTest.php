<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\CouponType;
use App\Models\Coupon;
use Tests\TestCase;

class CouponTest extends TestCase
{
    public function test_percent_coupon_discount_is_calculated(): void
    {
        $coupon = new Coupon(['type' => CouponType::Percent, 'value' => 10]);

        $this->assertSame(150.0, $coupon->discountFor(1500.0));
    }

    public function test_fixed_coupon_discount_never_exceeds_subtotal(): void
    {
        $coupon = new Coupon(['type' => CouponType::Fixed, 'value' => 100]);

        $this->assertSame(100.0, $coupon->discountFor(1500.0));
        $this->assertSame(80.0, $coupon->discountFor(80.0));
    }

    public function test_expired_coupon_is_not_redeemable(): void
    {
        $coupon = new Coupon([
            'type' => CouponType::Fixed,
            'value' => 100,
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);

        $this->assertFalse($coupon->isRedeemable(500.0));
    }
}
