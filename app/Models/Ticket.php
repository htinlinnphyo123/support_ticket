<?php

namespace App\Models;

use App\Models\Scopes\UpdatedAtDescScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'file_type',
        'link',
        'creator_id',
        'priority',
        'agent_id',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\TicketStatus::class,
            'priority' => \App\Enums\TicketPriority::class,
            'due_date' => 'datetime',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getSlaStatusAttribute(): \App\Enums\SlaStatus
    {
        if (in_array($this->status, [\App\Enums\TicketStatus::Resolved, \App\Enums\TicketStatus::Closed])) {
            return \App\Enums\SlaStatus::Completed;
        }

        if (!$this->due_date) {
            return \App\Enums\SlaStatus::OnTrack;
        }

        if (now()->greaterThan($this->due_date)) {
            return \App\Enums\SlaStatus::Overdue;
        }

        $hoursRemaining = now()->diffInHours($this->due_date, false);

        $isDueSoon = match ($this->priority) {
            \App\Enums\TicketPriority::High => $hoursRemaining < 1,
            \App\Enums\TicketPriority::Medium => $hoursRemaining < 4,
            \App\Enums\TicketPriority::Low => $hoursRemaining < 12,
            default => false,
        };

        return $isDueSoon ? \App\Enums\SlaStatus::DueSoon : \App\Enums\SlaStatus::OnTrack;
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new UpdatedAtDescScope);
    }    
}
