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
        Schema::create('sub_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_menu') // link to menus table
                ->constrained('menus')
                ->onDelete('cascade'); // delete submenus if parent menu is deleted
            $table->string('icon')->nullable(); // e.g., 'fa fa-file'
            $table->string('nama');             // Sub menu name
            $table->string('link');             // Route or URL
            $table->boolean('status')->default(1); // 1 = show, 0 = hide
            $table->integer('position')->default(1); // Order within the parent
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_menus');
    }
};
