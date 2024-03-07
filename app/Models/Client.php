<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model implements HasMedia
{
    use HasFactory, SoftDeletes,InteractsWithMedia;
    protected $fillable = [
        'phoneNumber',
        'projectName',
        'description',
        // 'imgClient',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }




}
