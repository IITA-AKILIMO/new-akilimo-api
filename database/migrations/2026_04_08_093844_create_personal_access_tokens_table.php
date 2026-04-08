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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');         // tokenable_type + tokenable_id (polymorphic — supports User and future models)
            $table->string('name');              // human label, e.g. "Login token"
            $table->string('token', 64)->unique(); // SHA-256 hex of the raw token
            $table->text('abilities')->nullable(); // JSON array of scopes, null = unrestricted
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // null = session-lived (cleared on logout)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
