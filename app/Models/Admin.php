<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Admin extends Model implements HasMedia
{

    use HasFactory, SoftDeletes,InteractsWithMedia;

    protected $fillable = [
       'job',
        'user_id'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
