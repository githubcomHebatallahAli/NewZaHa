<?php

use App\Models\Team;
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
        $boss =Team::Boss;
        Schema::create('teams', function (Blueprint $table)  use ($boss) {
            $table->id();

            $table->string('name');
            $table->enum('Boss',$boss)->default($boss[0])->nullable();
            $table->string('job');
            $table->text('skills')->nullable();
            $table->string('numProject')->nullable();
            $table->text('address')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('qualification')->nullable();
            $table->date('dateOfJoin')->nullable();
            $table->decimal('salary')->nullable();
            $table->string('photo')->nullable();
            $table->string('imgIDCard')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
