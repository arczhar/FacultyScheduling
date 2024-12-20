<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    use HasFactory;
    protected $fillable = ['room_name', 'exam_date', 'start_time', 'end_time', 'details'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

}
