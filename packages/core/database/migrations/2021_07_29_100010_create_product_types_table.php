<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Payflow\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'product_types');
    }
};
