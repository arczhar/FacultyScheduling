<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamRoom;

class ExamRoomSeeder extends Seeder
{
    public function run()
    {
        ExamRoom::create(['room_name' => 'Exam Room 101']);
        ExamRoom::create(['room_name' => 'Exam Room 102']);
    }
}
