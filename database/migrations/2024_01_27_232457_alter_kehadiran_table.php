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
        Schema::table('kehadiran', function (Blueprint $table) {
            
            // Drop columns if they exist
            if (Schema::hasColumn('kehadiran', 'koordinat_id')) {
                $table->dropForeign(['koordinat_id']);
                $table->dropColumn('koordinat_id');
            }
            if (Schema::hasColumn('kehadiran', 'gate_id')) {
                $table->dropForeign(['gate_id']);
                $table->dropColumn('gate_id');
            }
            if (!Schema::hasColumn('kehadiran', 'latitude')) {
                $table->string('latitude')->nullable()->after('status');
            }
            if (!Schema::hasColumn('kehadiran', 'longitude')) {
                $table->string('longitude')->nullable()->after('longitude');
            }
            $table->string('jam_keluar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->string('jam_keluar')->nullable(false)->change();
        });
    }
};
