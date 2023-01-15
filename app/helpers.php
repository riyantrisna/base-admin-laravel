<?php

use App\Models\User;
use App\Models\Translation;

if (!function_exists('echo_menu'))
{

    function echo_menu($parent_id = 0)
    {
        $menu_array = User::getMenu($parent_id)->get()->toArray();

        foreach($menu_array as $menu) {

            $menu_child = User::getMenu($menu['menu_id'])->get()->toArray();

            $mca = [];
            if(!empty($menu_child) && count($menu_child) > 0){
                foreach ($menu_child as $k => $v) {
                    array_push($mca, $v['menu_url']);
                }
            }

            $menu_open = "";
            $menu_active = "";
            if(in_array(request()->path(), $mca)){
                $menu_open = " menu-open";
                $menu_active = "active";
            }

            echo '<li class="nav-item'.$menu_open.'">
                        <a href="'.(!empty($menu['menu_url']) ? url($menu['menu_url']) : "#").'" class="nav-link '.(request()->path() == $menu['menu_url'] ? "active" : $menu_active).'">
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

if (!function_exists('multi_lang'))
{
    function multi_lang($key)
    {
        $lang = !empty(auth()->user()->lang_code) ? auth()->user()->lang_code : env('LANG_DEFAULT');

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
