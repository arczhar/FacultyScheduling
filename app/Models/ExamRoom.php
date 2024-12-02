<?php

namespace App\Models;
use App\Models\ExamRoom;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
    ];
}
