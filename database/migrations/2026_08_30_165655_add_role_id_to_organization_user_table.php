<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_user', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->nullable()
                ->after('user_id')
                ->constrained('roles')
                ->restrictOnDelete();

            $table->index('role_id');
        });

        DB::statement("
            UPDATE organization_user ou
            INNER JOIN roles r ON r.slug = ou.role
            SET ou.role_id = r.id
        ");
    }

    public function down(): void
    {
        Schema::table('organization_user', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropIndex(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};