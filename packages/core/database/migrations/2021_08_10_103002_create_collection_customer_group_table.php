<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Payflow\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'collection_customer_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained($this->prefix.'collections');
            $table->foreignId('customer_group_id')->constrained($this->prefix.'customer_groups');
            $table->scheduling();
            $table->boolean('visible')->default(true)->index();
            $table->timestamps();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'collection_customer_group');
    }
};
