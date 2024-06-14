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
        Schema::create('probe_rules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('probe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('metric_type_id')->constrained()->cascadeOnDelete();
            $table->enum('operator', ['=', '>', '<'])->default('=');
            $table->double('condition');
            $table->dateTime('estimation')->nullable(true);
            $table->timestamps();

            $table->unique(['probe_id', 'metric_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('probe_rules');
    }
};
