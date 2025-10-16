<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = ['entry_time', 'exit_time', 'status', 'image_path'];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];
}