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
        if (Schema::hasTable('products')) {
            return;
        }
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('SKU', 15)->unique();
            $table->text('description')->nullable();
            $table->float('price')->unsigned()->startingValue(1);
            $table->unsignedTinyInteger('discount')->nullable()->startingValue(0);
            $table->unsignedSmallInteger('quantity')->default(0);
            $table->text('thumbnail')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['slug', 'deleted_at']);
            $table->index(['title', 'deleted_at']);
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
