<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. First add the column without constraints
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        // 2. Get the first admin user (or any user) to assign to orphaned orders
        $defaultUser = User::where('role', 'admin')->first();
        
        if (!$defaultUser) {
            // If no admin, use any user
            $defaultUser = User::first();
        }
        
        // If there are any users in the system, use their ID
        if ($defaultUser) {
            $defaultUserId = $defaultUser->id;
            
            // 3. Update all existing orders to have this user_id
            DB::table('orders')->whereNull('user_id')->update(['user_id' => $defaultUserId]);
        }

        // 4. Now make the column non-nullable
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });

        // 5. Add the foreign key constraint
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};