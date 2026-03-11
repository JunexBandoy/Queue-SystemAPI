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
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->string('first_name', 100)
                  ->nullable()
                  ->after('queue_number'); // change or remove if 'queue_number' doesn't exist

            $table->string('middle_initial', 1)
                  ->nullable()
                  ->after('first_name');

            $table->string('last_name', 100)
                  ->nullable()
                  ->after('middle_initial');

            // Storing phone numbers as strings preserves leading zeros and allows +63 formats
            $table->string('contact_number', 20)
                  ->nullable()
                  ->after('last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            // Always drop the columns you added so rollback works
            $table->dropColumn(['first_name', 'middle_initial', 'last_name', 'contact_number']);
        });
    }
};