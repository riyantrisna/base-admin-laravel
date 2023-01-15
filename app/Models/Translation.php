<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'core_key';
    protected $primaryKey = 'key_id';

    public $timestamps = false;
}
