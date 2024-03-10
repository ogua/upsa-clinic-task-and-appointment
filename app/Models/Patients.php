<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name}";
    }
}
