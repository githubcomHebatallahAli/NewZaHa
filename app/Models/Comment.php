<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'comment',
        'user_id',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bestComment()
    {
        return $this->hasOne(BestComment::class);
    }

}
