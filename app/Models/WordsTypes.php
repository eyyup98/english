<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordsTypes extends Model
{
    use HasFactory;

    protected $table = 'words_types';
    protected $primaryKey = 'word_type_id';

    protected $guarded = [];
}
