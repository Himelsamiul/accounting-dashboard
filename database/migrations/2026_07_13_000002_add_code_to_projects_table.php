<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
        });

        // Backfill existing projects with a unique tracking code.
        Project::whereNull('code')->get()->each(function (Project $project) {
            do {
                $code = 'PRJ-' . strtoupper(Str::random(8));
            } while (Project::where('code', $code)->exists());

            $project->code = $code;
            $project->saveQuietly();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
