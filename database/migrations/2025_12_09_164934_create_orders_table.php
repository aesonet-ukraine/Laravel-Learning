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
        if(Schema::hasTable('orders')){
            Schema::dropIfExists('orders');
        }
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('status_id')
                ->constrained('order_statuses')
                ->cascadeOnDelete();
            $table->string('name', 30);
            $table->string('surname', 30);
            $table->string('email', 50);
            $table->string('phone', 15);
            $table->string('city', 50);
            $table->string('address', 100);
            $table->double('total')->startingValue(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
