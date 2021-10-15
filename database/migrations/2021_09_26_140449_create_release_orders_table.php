<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_orders', function (Blueprint $table) {
            $table->id();
            $table->string('released_to');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->constrained('users')->nullable();
            $table->foreignId('released_by')->constrained('users')->nullable();
            $table->foreignId('status_id')->constrained('statuses')->nullable();
            $table->string('ro_num');
            $table->string('memo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('release_orders');
    }
}
