<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $table = 'list';

    protected $fillable = ['name', 'students_answers', 'correction', 'updated_at', 'created_at'];
}
