<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'nameProject',
        'imgProject',
        'url',
        'skills',
        // 'numberSales',
        // 'price',
        'description'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'user_projects')->withPivot('numberSales', 'price');
    }
}

