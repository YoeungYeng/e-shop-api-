<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->decimal("price", 10, 2)->nullable();
            $table->string("image")->nullable();
            $table->integer("stock")->nullable();
            $table->enum("status", ["active", "inactive"]);
            // category_id foregin key
            $table->foreignId("category_id")->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
