<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('probes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained();
            $table->foreignId('probe_type_id')->constrained();
            $table->string('name');
            $table->string('description');
            $table->integer('stats_ongoing')->default(0);
            $table->timestamps();

            $table->unique(['team_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('probes');
    }
};
