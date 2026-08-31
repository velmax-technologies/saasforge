<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_invitations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('invited_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->restrictOnDelete();

            $table->string('email');

            /*
             * Store only the hash of the invitation token.
             * The raw token is sent to the invitee.
             */
            $table->string('token_hash', 64)->unique();

            $table->string('status')->default('pending');

            $table->timestamp('expires_at');

            $table->timestamp('accepted_at')->nullable();

            $table->timestamp('revoked_at')->nullable();

            $table->timestamps();

            $table->index([
                'organization_id',
                'email',
            ]);

            $table->index([
                'organization_id',
                'status',
            ]);

            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_invitations');
    }
};
