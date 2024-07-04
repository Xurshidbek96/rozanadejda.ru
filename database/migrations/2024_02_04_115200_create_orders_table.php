<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('user_phone');
            $table->string('user_email')->nullable();
            $table->string('user_address')->nullable();
            $table->string('street')->nullable();
            $table->string('home_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('status')->default('waiting')->comment('waiting, accept, reject');
            $table->float('full_price', 14, 2)->nullable();
            $table->integer('adminShow')->default(0)->comment('0 - not show, 1 - show');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
