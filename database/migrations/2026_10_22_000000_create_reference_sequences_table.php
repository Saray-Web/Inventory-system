<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reference_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('type', 2);
            $table->date('reference_date');
            $table->unsignedInteger('last_value')->default(0);
            $table->timestamps();

            $table->unique(['type', 'reference_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reference_sequences');
    }
};
