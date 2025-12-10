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
        if(Schema::hasTable('images')){
            Schema::dropIfExists('images');
        }
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->text('path');
            $table->string('imageable_type', 255);
            $table->unsignedBigInteger('imageable_id');
            $table->timestamps();

            $table->index(['imageable_id']);
            $table->fullText('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
