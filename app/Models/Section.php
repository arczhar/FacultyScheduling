<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['section_name']; // Ensure 'section_name' is fillable

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

}