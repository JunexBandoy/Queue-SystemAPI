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
        Schema::create('queues', function (Blueprint $table) {
          $table->id();

    $table->string('queue_number', 10);
    $table->date('queue_date');
    
    $table->text('location', 500);

    $table->foreignId('service_id')->constrained('services');

    $table->enum('priority', ['senior', 'pwd', 'regular'])->default('regular');
    $table->enum('status', ['waiting', 'serving', 'done', 'cancelled'])->default('waiting');

    $table->timestamp('issued_at')->useCurrent();
    $table->timestamp('called_at')->nullable();
    $table->timestamp('completed_at')->nullable();

    $table->unique(['queue_number', 'queue_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queues');
    }
};
