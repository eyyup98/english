<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordsProgress extends Model
{
    use HasFactory;

    protected $table = 'words_progress';
    protected $primaryKey = 'word_progress_id';

    protected $guarded = [];
}
