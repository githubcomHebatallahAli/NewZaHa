<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'phoneNumber',
        'nameProject',
        'price',
        'condition',
        'description',
        'user_id'
    ];

    const condition = ['Pending','Approved','In Progress','Completed','Cancelled'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
