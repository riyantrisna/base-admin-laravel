<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuGroup extends Model
{
    protected $table = 'menu_group';
    protected $primaryKey = 'menugroup_id';

    public $timestamps = false;
}
