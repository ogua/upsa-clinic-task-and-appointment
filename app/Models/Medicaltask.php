<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicaltask extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function patient()
    {
        return $this->belongsTo(Patients::class,"patient_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class,"user_id");
    }

    public function doctor()
    {
        return $this->belongsTo(Doctors::class,"doctor_id");
    }

    public function reminder()
    {
        return $this->hasOne(Taskreminders::class,"medical_task_id");
    }
}
