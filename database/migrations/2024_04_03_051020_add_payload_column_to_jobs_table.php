<?php


use Illuminate\Support\Facades\DB;
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
        DB::table('jobs')->update(['payload' => json_encode([])]);

        // Modify the payload column
        Schema::table('jobs', function (Blueprint $table) {
            $table->json('payload')->default(json_encode([]))->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // $table->text('payload')->change();
        });
    }
};
