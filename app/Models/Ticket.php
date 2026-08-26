<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public const PRIORITIES = ['low', 'medium', 'high', 'urgent'];

    public const STATUSES = ['open', 'in_progress', 'waiting_user', 'resolved', 'closed'];

    protected $fillable = [
        'reference',
        'user_id',
        'category_id',
        'assigned_to',
        'subject',
        'description',
        'priority',
        'status',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return ['resolved_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket): void {
            if ($ticket->reference) {
                return;
            }

            do {
                $reference = 'HD-'.now()->format('ymd').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            } while (static::where('reference', $reference)->exists());

            $ticket->reference = $reference;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function updates()
    {
        return $this->hasMany(TicketUpdate::class)->latest();
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('reference', 'like', "%{$term}%")
                    ->orWhere('subject', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        });
    }

    public function isEditableByUser(): bool
    {
        return $this->status === 'open' && $this->assigned_to === null;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'in_progress' => 'In progress',
            'waiting_user' => 'Waiting for user',
            default => ucfirst($this->status),
        };
    }
}
