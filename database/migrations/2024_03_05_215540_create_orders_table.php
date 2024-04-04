<?php

use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $condition =Order::condition;
        Schema::create('orders', function (Blueprint $table) use ($condition) {
            $table->id();
            $table->string('phoneNumber');
            $table->string('nameProject');
            $table->integer('price')->nullable();
            $table->enum('condition',$condition)->default($condition[0])->nullable();
            $table->text('description')->nullable();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->softDeletes();
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
