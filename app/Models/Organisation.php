<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\UpdatedAtDescScope;

class Organisation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\ActiveStatus::class,
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, User::class, 'organisation_id', 'creator_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new UpdatedAtDescScope);
    }
}
