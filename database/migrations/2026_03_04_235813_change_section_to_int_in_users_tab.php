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
       
            Schema::table('users', function (Blueprint $table) {
            // 1) Add new FK column
            $table->foreignId('section_id')
                  ->nullable()
                  ->after('name')
                  ->constrained('services')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // 2) (Optional) Drop old string column if no longer needed
            //    Do this only after you’ve migrated data (see note below)
            $table->dropColumn('section');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
Schema::table('users', function (Blueprint $table) {
            // Restore old column
            $table->string('section', 100)->nullable();

            // Drop FK + column
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
        });

    }
};
