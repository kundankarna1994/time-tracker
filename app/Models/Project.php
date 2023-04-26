<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $table = "projects";

    protected $fillable = [
        'name',
        'client_id',
        'billable',
        'status'
    ];

    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class,'client_id');
    }
}
