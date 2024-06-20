<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    use HasFactory;
    const storageFolder= 'Projects';
    protected $fillable = [
        'user_id',
        'project_id',
        'numberSales',
        'price',
        'urlProject',
        'imgProject',
        'startingDate',
        'endingDate',
        'nameOfTeam'
    ];

    public function getPhotosAttribute($value)
    {
        return json_decode($value, true);
    }

}
