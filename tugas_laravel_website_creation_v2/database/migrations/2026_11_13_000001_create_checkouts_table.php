<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per "Place order": who ordered, where to ship, the totals and the
     * payment confirmation message. The products themselves are rows in "orders"
     * (one per product), linked with orders.checkout_id.
     */
    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();        // e.g. NVR-7KQ2M9XA, shown to the customer
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('recipient_name', 100);
            $table->string('phone', 30);
            $table->string('email');
            $table->text('address');
            $table->string('note')->nullable();
            $table->integer('subtotal');
            $table->integer('shipping');
            $table->integer('total');
            $table->text('payment_message')->nullable(); // filled when the customer sends the payment form
            $table->timestamp('payment_sent_at')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('checkout_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('checkout_id');
        });

        Schema::dropIfExists('checkouts');
    }
};
