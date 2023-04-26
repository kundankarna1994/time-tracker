<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;

class Invitation extends Model
{
    use HasFactory,Notifiable;

    protected $table = "invitations";

    protected $casts = [
        'expiration' => 'date'
    ];

    protected $fillable = [
        "invited_by",
        "email",
        "status",
        "token",
        "expiration"
    ];

    public function scopeInActive(Builder $query): void
    {
        $query->where('status', 0);
    }
}
