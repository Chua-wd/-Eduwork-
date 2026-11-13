<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Three roles instead of two:
     *   user         -> shop + profile (was "customer")
     *   admin        -> + dashboard, products, product categories
     *   system_admin -> + user management
     * The oldest admin account becomes the first system admin, so someone can open User management.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('user')->change();
        });

        DB::table('users')->where('role', 'customer')->update(['role' => 'user']);

        if (! DB::table('users')->where('role', 'system_admin')->exists()) {
            $firstAdmin = DB::table('users')->where('role', 'admin')->orderBy('id')->value('id');
            if ($firstAdmin) {
                DB::table('users')->where('id', $firstAdmin)->update(['role' => 'system_admin']);
            }
        }
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'system_admin')->update(['role' => 'admin']);
        DB::table('users')->where('role', 'user')->update(['role' => 'customer']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('customer')->change();
        });
    }
};
