<?php

declare(strict_types=1);

use App\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('sku', 40)->unique();
            $table->string('slug', 220)->unique();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('thumbnail')->nullable();
            $table->string('status', 20)->default(ProductStatus::Draft->value);
            $table->boolean('featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'featured']);
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
