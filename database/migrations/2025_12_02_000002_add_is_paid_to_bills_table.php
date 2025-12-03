<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            if (!Schema::hasColumn('bills', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('description');
            }
        });

        // Backfill from status enum if present
        try {
            DB::table('bills')->where('status', 'paid')->update(['is_paid' => 1]);
        } catch (\Exception $e) {
            // ignore if status column doesn't exist or other issue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            if (Schema::hasColumn('bills', 'is_paid')) {
                $table->dropColumn('is_paid');
            }
        });
    }
};
