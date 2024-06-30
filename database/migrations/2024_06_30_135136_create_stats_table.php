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
        Schema::create('stats', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('metric_type_id')->constrained();
            $table->foreignId('probe_id')->constrained();

            $table->timestamp('started_at');
            $table->timestamp('ended_at');

            $table->float('avg_increase_minute', 8, 2)->default(0);
            $table->float('avg_decrease_minute', 8, 2)->default(0);
            $table->float('min', 8, 2)->default(0);
            $table->float('max', 8, 2)->default(0);

            $table->timestamps();

            $table->index(['metric_type_id', 'probe_id', 'started_at', 'ended_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
