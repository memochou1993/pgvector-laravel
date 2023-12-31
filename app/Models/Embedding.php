<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\Vector;

class Embedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'embedding',
        'metadata',
    ];

    protected $casts = [
        'embedding' => Vector::class,
        'metadata' => 'array',
    ];
}
