<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // public function patient()
    // {
    //     return $this->hasOne(Patients::class,"user_id","user_id");
    // }

    public function patient()
    {
        return $this->hasOne(Patients::class,"email","email");
    }

    public function user()
    {
        return $this->belongsTo(User::class,"user_id");
    }
}
