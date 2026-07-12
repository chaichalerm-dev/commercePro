<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Lang;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    /**
     * Write an audit entry for the current request.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function record(string $action, ?Model $subject = null, array $properties = []): self
    {
        return self::query()->create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'properties' => $properties !== [] ? $properties : null,
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Plain-language label for the "Action" column — falls back to a
     * humanized version of the raw action string if a translation is
     * ever missing, so the admin never sees a bare dotted code.
     */
    public function actionLabel(): string
    {
        $key = 'admin/logs.actions.'.$this->action;

        if (Lang::has($key)) {
            return __($key);
        }

        return str(str_replace(['.', '_'], ' ', $this->action))->title()->toString();
    }

    /**
     * Plain-language sentence for the "Details" column, built from the
     * structured properties recorded alongside the action — never raw JSON.
     */
    public function description(): string
    {
        $p = $this->properties ?? [];

        return match ($this->action) {
            'banner.created', 'banner.updated', 'banner.deleted' => __('admin/logs.details.title', ['title' => $p['title'] ?? '']),

            'category.created', 'category.updated', 'category.deleted',
            'product.created', 'product.deleted', 'product.restored' => __('admin/logs.details.name', ['name' => $p['name'] ?? '']),

            'coupon.created', 'coupon.updated', 'coupon.deleted' => __('admin/logs.details.code', ['code' => $p['code'] ?? '']),

            'review.moderated' => ($p['approved'] ?? false)
                ? __('admin/logs.details.review_approved', ['product' => $p['product'] ?? ''])
                : __('admin/logs.details.review_hidden', ['product' => $p['product'] ?? '']),

            'review.deleted' => __('admin/logs.details.review_deleted', ['product' => $p['product'] ?? '']),

            'settings.updated' => __('admin/logs.details.settings_updated', ['count' => count($p['keys'] ?? [])]),

            'user.role_changed' => __('admin/logs.details.role_changed', [
                'name' => $p['name'] ?? '',
                'role' => __('enums.user_role.'.strtolower((string) ($p['role'] ?? ''))),
            ]),

            'user.status_changed' => __('admin/logs.details.'.(($p['status'] ?? '') === 'banned' ? 'banned' : 'unbanned'), [
                'name' => $p['name'] ?? '',
            ]),

            'order.placed' => __('admin/logs.details.order_placed', [
                'number' => $p['order_number'] ?? '',
                'total' => money((float) ($p['grand_total'] ?? 0)),
            ]),

            'order.cancelled' => __('admin/logs.details.order_cancelled', ['number' => $p['order_number'] ?? '']),

            'order.status_changed' => __('admin/logs.details.order_status_changed', [
                'number' => $p['order_number'] ?? '',
                'status' => __('enums.order_status.'.($p['to'] ?? '')),
            ]),

            'order.payment_changed' => __('admin/logs.details.order_payment_changed', [
                'number' => $p['order_number'] ?? '',
                'status' => __('enums.payment_status.'.($p['to'] ?? '')),
            ]),

            'product.updated' => __('admin/logs.details.product_updated', [
                'name' => $p['name'] ?? '',
                'fields' => $this->friendlyFieldNames($p['changes'] ?? []),
            ]),

            'product.featured_toggled' => ($p['featured'] ?? false)
                ? __('admin/logs.details.featured_on')
                : __('admin/logs.details.featured_off'),

            default => $p !== [] ? json_encode($p, JSON_UNESCAPED_UNICODE) : __('admin/logs.details.none'),
        };
    }

    /**
     * @param  list<string>  $columns
     */
    private function friendlyFieldNames(array $columns): string
    {
        $labels = collect($columns)
            ->reject(fn (string $column) => in_array($column, ['created_at', 'updated_at'], true))
            ->map(function (string $column): string {
                $key = "admin/products.fields.{$column}";

                return Lang::has($key) ? __($key) : $column;
            });

        return $labels->isNotEmpty() ? $labels->implode(', ') : __('admin/logs.details.none');
    }
}
