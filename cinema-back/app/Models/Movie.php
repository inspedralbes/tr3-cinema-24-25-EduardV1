<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'poster_url', 'duration', 'rating'];

    public function sessions()
    {
        return $this->hasMany(MovieSession::class);
    }
}

