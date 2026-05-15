<?php

use App\Models\Ticket;
use App\Models\User;
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
        Schema::create('ticket_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ticket::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->unsignedTinyInteger('score');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['ticket_id', 'user_id']);
            $table->index('score');
        });

        DB::statement('ALTER TABLE ticket_ratings ADD CONSTRAINT score_check CHECK (score >= 1 AND score <= 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_ratings');
    }
};
