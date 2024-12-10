<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Payflow\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->index();
            $table->timestamps();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'tags');
    }
};
