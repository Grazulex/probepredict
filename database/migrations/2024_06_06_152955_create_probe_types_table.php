<?php

declare(strict_types=1);

use App\Enums\ProbeTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('probe_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->string('enum')->default(ProbeTypeEnum::ENVIRONMENT->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('probe_types');
    }
};
