<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Team extends Model implements HasMedia
{
    use HasFactory, SoftDeletes,InteractsWithMedia;
    protected $fillable = [
        // 'imgTeam',
        'job',
        'skills',
        'numProject',
        // 'imgIDCard',
        'address',
        'phoneNumber',
        'qualification',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

