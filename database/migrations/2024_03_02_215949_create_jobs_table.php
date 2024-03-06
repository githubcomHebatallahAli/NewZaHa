<?php

use App\Models\Job;
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
        $job =Job::job;
        Schema::create('jobs', function (Blueprint $table) use ($job){
            $table->id();
            $table->string('address');
            $table->string('phoneNumber');
            $table->string('qualification');
            $table->enum('job',$job)->default($job[0]);
            $table->string('yearsOfExperience');
            $table->text('skills');
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
        Schema::dropIfExists('jobs');
    }
};
