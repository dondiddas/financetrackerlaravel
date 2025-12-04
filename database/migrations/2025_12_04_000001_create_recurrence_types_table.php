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
        Schema::create('recurrence_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Seed common recurrence types
        if (Schema::hasTable('recurrence_types')) {
            \DB::table('recurrence_types')->insertOrIgnore([
                ['name' => 'daily', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'weekly', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'monthly', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'yearly', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurrence_types');
    }
};
