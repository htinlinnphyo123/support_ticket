<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->text('description');
            $table->string('status')->default(TicketStatus::Open->value)->index();
            $table->string('file_type')->nullable();
            $table->string('link')->nullable();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->string('priority')->default(TicketPriority::Medium->value)->index();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
