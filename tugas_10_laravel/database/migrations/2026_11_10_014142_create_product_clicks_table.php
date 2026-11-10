<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row = one visit to a product page ("product click").
     * Counting rows gives "number of product clicks" for the dashboard.
     */
    public function up(): void
    {
        Schema::create('product_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // Who clicked, if they were logged in (stays as history if the user is deleted)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent()->index(); // indexed: the dashboard filters by date
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_clicks');
    }
};
