<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::table('users')->where('role', 'playground')->update(['role' => 'user']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', \App\Enums\EnumUserRole::values())
                ->default(\App\Enums\EnumUserRole::User)->change();
        });
    }

    public function down(): void
    {

        Schema::table('users', function (Blueprint $table) {
            $table->string('role',15)->default('playground')->change();
        });

        DB::table('users')->where('role', 'user')->update(['role' => 'playground']);
    }
};
