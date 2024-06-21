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
    const storageFolder= 'Projects';
    protected $fillable = [
        'nameProject',
        'skills',
        'description',
        'numberOfSales',
        'urlProject',
        'imgProject',
        'startingDate',
        'endingDate',
        'team',
        'saleType'

    ];

    const saleType = [
        'single selling',
        'multi selling'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'user_projects')
        ->withPivot('price');
    }
}

