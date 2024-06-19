<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model implements HasMedia
{
    use HasFactory,SoftDeletes,InteractsWithMedia;
    protected $fillable = [
        'nameProject',
        'skills',
        'description',
        'urlProject',
        'startingDate',
        'endingDate',
        'nameOfTeam'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'user_projects')->withPivot('numberSales','price');

    }
}

