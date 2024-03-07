<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'address',
        'phoneNumber',
        'qualification',
        'job',
        'yearsOfExperience',
        'skills',
        'user_id'
    ];
    const job = [
        'Ui&Ux Designer',
        'FrontEnd Developer',
        'BackEnd Developer',
        'FullStack Developer',
        'Mobile Application Developer',
        'Marketing',
        'SEO'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
