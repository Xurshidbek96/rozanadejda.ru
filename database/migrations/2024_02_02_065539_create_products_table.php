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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_uz')->nullable();
            $table->string('name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->string('slug')->nullable();
            $table->integer('year')->nullable();
            $table->string('breeder')->nullable();
            $table->string('latest')->nullable();
            $table->string('color')->nullable();
            $table->string('petal')->nullable();
            $table->string('shape')->nullable();
            $table->string('height')->nullable();
            $table->string('smell')->nullable();
            $table->float('price', 14, 2)->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->string('yesorno')->nullable();
            $table->text('about')->nullable();

            $table->string('seo_tag')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
