<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('task_label')->nullable();
            $table->text('description');
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->boolean('is_running')->default(false);
            $table->json('tags_json')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'start_at']);
            $table->index(['user_id', 'is_running']);
        });

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('CREATE UNIQUE INDEX time_entries_single_running_idx ON time_entries(user_id) WHERE is_running = 1');
        }

        if ($driver === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX time_entries_single_running_idx ON time_entries(user_id) WHERE is_running = true');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['sqlite', 'pgsql'], true)) {
            DB::statement('DROP INDEX IF EXISTS time_entries_single_running_idx');
        }

        Schema::dropIfExists('time_entries');
    }
};
