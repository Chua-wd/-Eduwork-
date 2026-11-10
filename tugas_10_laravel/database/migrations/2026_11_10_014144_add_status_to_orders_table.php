<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Order status for the dashboard's "Order fulfillment" card:
     * pending -> processing -> shipped -> delivered (or returned).
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 20)->default('pending')->after('total')->index();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};
