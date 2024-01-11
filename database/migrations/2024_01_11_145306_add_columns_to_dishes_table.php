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
        Schema::table('dishes', function (Blueprint $table) {
            $table->double('protein_breakfast')->after('image_breakfast')->nullable()->constrained()->cascadeOnDelete();
            $table->double('protein_lunch')->after('image_lunch')->nullable()->constrained()->cascadeOnDelete();
            $table->double('protein_dinner')->after('image_dinner')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dishes', function (Blueprint $table) {
            $table->dropColumn(['protein_breakfast']);
            $table->dropColumn(['protein_lunch']);
            $table->dropColumn(['protein_dinner']);
        });
    }
};
