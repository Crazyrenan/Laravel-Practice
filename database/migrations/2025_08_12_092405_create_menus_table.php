<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable(); // e.g., 'fa fa-home'
            $table->string('nama_menu');        // Menu display name
            $table->string('link')->nullable(); // Can be null if sub_menu = 1
            $table->boolean('status')->default(1); // 1 = show, 0 = hide
            $table->boolean('sub_menu')->default(0); // 1 = has sub menu, 0 = no sub menu
            $table->integer('position')->default(1); // Sidebar ordering
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }

};
