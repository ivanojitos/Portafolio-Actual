<?php

namespace App\Models;

use App\Enums\ContactMessageStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'company',
        'subject',
        'message',
        'status',
        'ip_hash',
        'user_agent',
        'read_at',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ContactMessageStatus::class,
            'read_at' => 'datetime',
            'replied_at' => 'datetime',
        ];
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where(
            'status',
            ContactMessageStatus::Pending
        );
    }

    public function markAsRead(): void
    {
        if ($this->status !== ContactMessageStatus::Pending) {
            return;
        }

        $this->update([
            'status' => ContactMessageStatus::Read,
            'read_at' => now(),
        ]);
    }

    public function markAsReplied(): void
    {
        $this->update([
            'status' => ContactMessageStatus::Replied,
            'read_at' => $this->read_at ?? now(),
            'replied_at' => now(),
        ]);
    }

    public function markAsSpam(): void
    {
        $this->update([
            'status' => ContactMessageStatus::Spam,
        ]);
    }
}
