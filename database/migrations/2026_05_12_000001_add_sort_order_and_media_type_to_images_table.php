<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::table('images', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('filename');
            $table->string('media_type', 20)->default('image')->after('sort_order');
        });

        $productIds = DB::table('images')->distinct()->pluck('product_id');
        foreach ($productIds as $productId) {
            $rows = DB::table('images')->where('product_id', $productId)->orderBy('id')->get();
            $i = 0;
            foreach ($rows as $row) {
                DB::table('images')->where('id', $row->id)->update([
                    'sort_order' => $i++,
                    'media_type' => 'image',
                ]);
            }
        }

        Schema::table('images', function (Blueprint $table) {
            $table->index(['product_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'sort_order']);
            $table->dropColumn(['sort_order', 'media_type']);
        });
    }
};
