<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
        {
            Schema::create('pembelian_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pembelian_id');
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('updated_by')->nullable(); 
                $table->timestamps();

                $table->foreign('pembelian_id')->references('id')->on('pembelian')->onDelete('cascade');
            });
        }

        public function down()
        {
            Schema::dropIfExists('pembelian_logs');
        }
    };
