<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model implements HasMedia
{
    use HasFactory, SoftDeletes,InteractsWithMedia;

    protected $fillable = [
        'phoneNumber',
        'nameProject',
        'price',
        'condition',
        'description',
        'client_id'
    ];

    const condition = ['Pending','Approved','In Progress','Completed','Cancelled'];


    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
