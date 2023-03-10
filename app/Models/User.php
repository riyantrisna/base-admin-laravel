<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Menu;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'last_login',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function isAllowMenu($path_url)
    {
        $menu_id = Self::select('mg.menugroup_menu_id')
                    ->join('menu_group AS mg', 'mg.menugroup_id', 'users.role')
                    ->where('users.id', auth()->user()->id)->first();

        if(!empty($menu_id)){
            $menu = Menu::select('menu_id')
                    ->whereIn('menu_id', explode(',', $menu_id->menugroup_menu_id))
                    ->where('menu_url', $path_url)
                    ->where('menu_status', 1)
                    ->first();

            if(!empty($menu)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function getMenu($parent_id)
    {
        $lang = !empty(auth()->user()->lang_code) ? auth()->user()->lang_code : env('LANG_DEFAULT');

        $menu_id = Self::select('mg.menugroup_menu_id')
                    ->join('menu_group AS mg', 'mg.menugroup_id', 'users.role')
                    ->where('users.id', auth()->user()->id)->first();

        if(!empty($menu_id)){
            $menu = Menu::select(
                        'menu_id',
                        'menu_parent_id',
                        'menu_url',
                        'menu_icon',
                        'mn.menuname_name'
                    )
                    ->leftJoin('menu_name AS mn', 'mn.menuname_menu_id', 'menu.menu_id')
                    ->whereIn('menu_id', explode(',', $menu_id->menugroup_menu_id))
                    ->where('menu_status', 1)
                    ->where('menu_parent_id', $parent_id)
                    ->where('mn.menuname_lang_code', $lang)
                    ->orderBy('menu_order', 'asc');

            if(!empty($menu)){
                return $menu;
            }else{
                return "";
            }
        }else{
            return "";
        }
    }

    public function getMenuDefault()
    {
        $menu_url = Self::select('m.menu_url')
                    ->join('menu_group AS mg', 'mg.menugroup_id', 'users.role')
                    ->leftJoin('menu AS m', 'm.menu_id', 'mg.menugroup_home_menu_id')
                    ->where('users.id', auth()->user()->id)
                    ->first();

        if(!empty($menu_url)){
            return $menu_url->menu_url;
        }else{
            return "";
        }
    }

}
