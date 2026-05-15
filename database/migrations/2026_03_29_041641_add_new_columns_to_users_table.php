<?php

use App\Enums\ActiveStatus;
use App\Enums\UserType;
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('type')->default(UserType::Employee->value)->index();
            $table->string('status')->default(ActiveStatus::Active->value)->index();
            $table->foreignId('organisation_id')->nullable()->constrained('organisations')->nullOnDelete();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organisation_id']);
            $table->dropColumn(['phone', 'type', 'status', 'organisation_id']);
            $table->dropSoftDeletes();
        });
    }
};
