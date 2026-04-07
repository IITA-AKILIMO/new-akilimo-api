<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fertilizers', function (Blueprint $table) {
            $table->boolean('cim')->default(true)->after('use_case')->index();
            $table->boolean('cis')->default(true)->after('use_case')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fertilizers', function (Blueprint $table) {
            $table->dropColumn('cim');
            $table->dropColumn('cis');
        });
    }
};
