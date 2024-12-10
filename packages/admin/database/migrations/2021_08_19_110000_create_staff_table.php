<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Payflow\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'staff', function (Blueprint $table) {
            $table->id();
            $table->boolean('admin')->default(true)->index();
            $table->string('firstname')->nullable()->index(); // Made nullable
            $table->string('lastname')->nullable()->index();  // Made nullable
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'staff');
    }
};
