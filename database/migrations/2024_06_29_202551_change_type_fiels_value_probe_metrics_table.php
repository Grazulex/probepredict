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
        //change column type from string to float
        Schema::table('probe_metrics', function (Blueprint $table): void {
            $table->float('value')->change();
        });
    }

    public function down(): void
    {
        //change column type from float to string
        Schema::table('probe_metrics', function (Blueprint $table): void {
            $table->string('value')->change();
        });
    }
};
