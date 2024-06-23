<?php

use App\Models\Project;
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
        $saleType =Project::saleType;
        Schema::create('projects', function (Blueprint $table) use($saleType){
            $table->id();
            $table->string('nameProject')->nullable();
            $table->text('skills')->nullable();
            $table->text('description')->nullable();
            $table->integer('price')->nullable();
            $table->enum('saleType', $saleType)->nullable()->default($saleType[0]);
            $table->string('urlProject')->nullable();
            $table->string('imgProject')->nullable();
            $table->date('startingDate')->nullable();
            $table->date('endingDate')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
