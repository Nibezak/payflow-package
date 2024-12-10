<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Payflow\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'brand_discount', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained($this->prefix.'brands')->cascadeOnDelete();
            $table->foreignId('discount_id')->constrained($this->prefix.'discounts')->cascadeOnDelete();
            $table->timestamps();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'brand_discount');
    }
};
