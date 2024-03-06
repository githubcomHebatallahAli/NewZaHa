<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'imgTeam',
        'job',
        'skills',
        'numProject',
        'imgIDCard',
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

