<?php

namespace App\Models;

use App\Enums\SlaStatus;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
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
            'status' => TicketStatus::class,
            'priority' => TicketPriority::class,
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

    public function getSlaStatusAttribute(): SlaStatus
    {
        if (in_array($this->status, [TicketStatus::Resolved, TicketStatus::Closed])) {
            return SlaStatus::Completed;
        }

        if (! $this->due_date) {
            return SlaStatus::OnTrack;
        }

        if (now()->greaterThan($this->due_date)) {
            return SlaStatus::Overdue;
        }

        $hoursRemaining = now()->diffInHours($this->due_date, false);

        $isDueSoon = match ($this->priority) {
            TicketPriority::High => $hoursRemaining < 1,
            TicketPriority::Medium => $hoursRemaining < 4,
            TicketPriority::Low => $hoursRemaining < 12,
            default => false,
        };

        return $isDueSoon ? SlaStatus::DueSoon : SlaStatus::OnTrack;
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new UpdatedAtDescScope);
    }
}
