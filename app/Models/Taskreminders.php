<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taskreminders extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function medicaltask()
    {
        return $this->belongsTo(Medicaltask::class,"medical_task_id");
    }
}
