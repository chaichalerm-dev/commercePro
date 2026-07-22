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

    public function test_inactive_coupon_is_not_redeemable(): void
    {
        $coupon = new Coupon([
            'type' => CouponType::Fixed,
            'value' => 100,
            'is_active' => false,
        ]);

        $this->assertFalse($coupon->isRedeemable(500.0));
    }

    public function test_coupon_not_yet_started_is_not_redeemable(): void
    {
        $coupon = new Coupon([
            'type' => CouponType::Fixed,
            'value' => 100,
            'is_active' => true,
            'starts_at' => now()->addDay(),
        ]);

        $this->assertFalse($coupon->isRedeemable(500.0));
    }

    public function test_coupon_at_max_uses_is_not_redeemable(): void
    {
        $coupon = new Coupon([
            'type' => CouponType::Fixed,
            'value' => 100,
            'is_active' => true,
            'max_uses' => 5,
            'used_count' => 5,
        ]);

        $this->assertFalse($coupon->isRedeemable(500.0));
    }

    public function test_coupon_below_min_order_is_not_redeemable(): void
    {
        $coupon = new Coupon([
            'type' => CouponType::Fixed,
            'value' => 100,
            'is_active' => true,
            'min_order' => 1000,
        ]);

        $this->assertFalse($coupon->isRedeemable(500.0));
    }

    public function test_coupon_meeting_every_condition_is_redeemable(): void
    {
        $coupon = new Coupon([
            'type' => CouponType::Fixed,
            'value' => 100,
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDay(),
            'max_uses' => 5,
            'used_count' => 4,
            'min_order' => 100,
        ]);

        $this->assertTrue($coupon->isRedeemable(500.0));
    }
}
