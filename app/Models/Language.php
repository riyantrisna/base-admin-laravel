<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'core_lang';
    protected $primaryKey = 'lang_code';

    public $timestamps = false;
}
