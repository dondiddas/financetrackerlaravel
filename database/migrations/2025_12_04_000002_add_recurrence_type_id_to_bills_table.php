<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            // Add nullable FK column
            $table->unsignedBigInteger('recurrence_type_id')->nullable()->after('is_recurring');

            // If the recurrence_types table exists, add foreign key constraint
            if (Schema::hasTable('recurrence_types')) {
                $table->foreign('recurrence_type_id')->references('id')->on('recurrence_types')->onDelete('set null');
            }

            // Remove old recurrence_interval column if exists
            if (Schema::hasColumn('bills', 'recurrence_interval')) {
                $table->dropColumn('recurrence_interval');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            // Recreate recurrence_interval
            if (! Schema::hasColumn('bills', 'recurrence_interval')) {
                $table->string('recurrence_interval')->nullable()->after('is_recurring');
            }

            // Drop foreign key and column if exists
            if (Schema::hasColumn('bills', 'recurrence_type_id')) {
                // drop foreign if exists
                try {
                    $table->dropForeign(['recurrence_type_id']);
                } catch (\Exception $e) {
                    // ignore if constraint not present
                }
                $table->dropColumn('recurrence_type_id');
            }
        });
    }
};
