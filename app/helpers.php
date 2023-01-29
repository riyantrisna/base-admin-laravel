<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Translation;

if (!function_exists('echo_menu'))
{

    function echo_menu($parent_id = 0)
    {
        $menu_array = User::getMenu($parent_id)->get()->toArray();

        foreach($menu_array as $menu) {

            $menu_child = User::getMenu($menu['menu_id'])->get()->toArray();

            $menu_active_arr = echo_menu_parent_arr();

            $menu_open = "";
            $menu_active = "";
            if(in_array($menu['menu_id'], $menu_active_arr)){
                $menu_open = " menu-open";
                $menu_active = "active";
            }

            echo '<li class="nav-item'.$menu_open.'">
                        <a href="'.(!empty($menu['menu_url']) ? url($menu['menu_url']) : "#").'" class="nav-link '.$menu_active.'">
                            <i class="'.$menu['menu_icon'].'"></i>
                            <p>
                            '.$menu['menuname_name'].'
                            '.(!empty($menu_child)  && count($menu_child) > 0 ? '<i class="right fas fa-angle-left"></i>' : '').'
                            </p>
                        </a>';
            if(!empty($menu_child) && count($menu_child) > 0) {
                echo '<ul class="nav nav-treeview">';
                echo_menu($menu['menu_id']);
                echo '</ul>';
            }
            echo '</li>';
        }
    }
}

if (!function_exists('echo_menu_parent'))
{
    function echo_menu_parent($parent_id = "", $text = "")
    {
        $lang = !empty(auth()->user()->lang_code) ? auth()->user()->lang_code : env('LANG_DEFAULT');

        $menu = Menu::select(
            'menu_id',
            'menu_parent_id',
            'menu_url',
            'menu_icon',
            'mn.menuname_name AS menu_name'
        )
        ->leftJoin('menu_name AS mn', 'mn.menuname_menu_id', 'menu.menu_id')
        ->where('menu_status', 1)
        ->where('mn.menuname_lang_code', $lang);
        if($parent_id == ""){
            $menu = $menu->where('menu_url', request()->path());
        }else{
            $menu = $menu->where('menu_id', $parent_id);
        }
        $menu = $menu->first();

        $text_new = "";
        if(!empty($menu) && $menu->menu_parent_id > 0){
            if($parent_id == ""){
                $text_new.= $menu->menu_id.',';
            }else{
                $text_new.= $menu->menu_id.',';
            }

            $text_new.= echo_menu_parent($menu->menu_parent_id, $text.$text_new);
        }else{
            $text_new.= $menu->menu_id.',';
        }
        return $text_new;
    }
}

if (!function_exists('echo_menu_parent_arr'))
{
    function echo_menu_parent_arr()
    {
        $parent_data = echo_menu_parent();
        $parent_arr = explode(",", $parent_data);

        return $parent_arr;
    }
}

if (!function_exists('echo_menu_access'))
{

    function echo_menu_access($parent_id = 0, $level = 0, $parent = "")
    {
        $menu_array = User::getMenu($parent_id)->get()->toArray();

        $indent = str_repeat("&nbsp;&nbsp;", $level * 2);

        foreach($menu_array as $menu) {

            $menu_child = User::getMenu($menu['menu_id'])->get()->toArray();

            $menu_child_str = get_child($menu['menu_id']);
            $menu_child_str = substr($menu_child_str, 0, -1);

            echo '
                <div id="menu_name_'.$menu['menu_id'].'" class="col-sm-9 mt-2" style="opacity: 0.5;">'
                    .$indent.'<i class="'.$menu['menu_icon'].'"></i>&nbsp;'.$menu['menuname_name'].
                '</div>
                <div class="custom-control custom-switch col-sm-3 mt-2">
                    <input type="checkbox" class="custom-control-input menu_cb" id="menu_'.$menu['menu_id'].'" onclick="switch_menu_access(\''.$menu['menu_id'].'\',\''.$parent.'\',\''.$menu_child_str.'\')" name="menu['.$menu['menu_id'].']" value="'.$menu['menu_id'].'">
                    <label class="custom-control-label font-weight-normal" for="menu_'.$menu['menu_id'].'" id="menu_lable_'.$menu['menu_id'].'">'.multi_lang('no').'</label>
                </div>
            ';

            if(!empty($menu_child) && count($menu_child) > 0) {
                $parent = !empty($parent) ? $parent."," : $parent;
                echo_menu_access($menu['menu_id'], $level + 1, $parent.$menu['menu_id']);
            }
        }
    }
}

if (!function_exists('get_child'))
{
    function get_child($parent_id)
    {
        $menu = User::getMenu($parent_id)->get()->toArray();

        $menu_str = "";
        foreach ($menu as $key => $value) {
           $menu_str.= $value['menu_id'].",";

           $menu_str.=get_child($value['menu_id']);
        }

        return $menu_str;
    }
}

if (!function_exists('breadcrumb_menu'))
{
    function breadcrumb_menu($parent_id = "", $text = "")
    {
        $lang = !empty(auth()->user()->lang_code) ? auth()->user()->lang_code : env('LANG_DEFAULT');

        $menu = Menu::select(
            'menu_id',
            'menu_parent_id',
            'menu_url',
            'menu_icon',
            'mn.menuname_name AS menu_name'
        )
        ->leftJoin('menu_name AS mn', 'mn.menuname_menu_id', 'menu.menu_id')
        ->where('menu_status', 1)
        ->where('mn.menuname_lang_code', $lang);
        if($parent_id == ""){
            $menu = $menu->where('menu_url', request()->path());
        }else{
            $menu = $menu->where('menu_id', $parent_id);
        }
        $menu = $menu->first();

        $text_new = "";
        if(!empty($menu) && $menu->menu_parent_id > 0){
            if($parent_id == ""){
                $text_new.= '<li class="breadcrumb-item active">'.$menu->menu_name.'</li>|';
            }else{
                $text_new.= '<li class="breadcrumb-item">'.$menu->menu_name.'</li>|';
            }

            $text_new.= breadcrumb_menu($menu->menu_parent_id, $text.$text_new);
        }else{
            $text_new.= '<li class="breadcrumb-item">'.$menu->menu_name.'</li>|';
        }
        return $text_new;
    }
}

if (!function_exists('breadcrumb'))
{
    function breadcrumb()
    {
        $breadcrumb_menu = breadcrumb_menu();
        $breadcrumb_menu = substr($breadcrumb_menu, 0, -1);

        $breadcrumb_menu_arr = explode("|", $breadcrumb_menu);

        $total_arr = count($breadcrumb_menu_arr) - 1;

        $text = "";
        for($i = $total_arr; $i >= 0; $i--){
            $text.= $breadcrumb_menu_arr[$i];
        }

        echo $text;
    }
}

if (!function_exists('multi_lang'))
{
    function multi_lang($key, $lang = '')
    {
        if(empty($lang)){
            $lang = !empty(auth()->user()->lang_code) ? auth()->user()->lang_code : env('LANG_DEFAULT');
        }

        $translation = Translation::select(
                            'ckt.keytext_text'
                        )
                        ->leftJoin("core_key_text AS ckt", "ckt.keytext_key_id" , "core_key.key_id")
                        ->where('ckt.keytext_lang_code', $lang)
                        ->where('core_key.key_code', $key)
                        ->first();
        if(!empty($translation)){
            return $translation->keytext_text;
        }else{
            return "[".$key."]";
        }
    }
}
