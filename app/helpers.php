<?php

use App\Models\User;
use App\Models\Translation;

if (!function_exists('echo_menu'))
{

    function echo_menu($parent_id = 0)
    {
        $menu_array = User::getMenu($parent_id)->where('menu_status', 1)->get()->toArray();

        foreach($menu_array as $menu) {

            $menu_child = User::getMenu($menu['menu_id'])->where('menu_status', 1)->get()->toArray();

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

if (!function_exists('echo_menu_access'))
{

    function echo_menu_access($parent_id = 0)
    {
        $menu_array = User::getMenu($parent_id)->where('menu_status', 1)->get()->toArray();

        foreach($menu_array as $menu) {

            $menu_child = User::getMenu($menu['menu_id'])->where('menu_status', 1)->get()->toArray();

            if(!empty($menu_child) && count($menu_child) > 0) {
                echo '<div class="col-sm-12 pt-1 pb-1 text-bold">'.$menu['menuname_name'].'</div>';
                echo_menu_access($menu['menu_id']);
            }else{
                if($parent_id == 0){
                    echo '<div class="col-sm-9 pt-1 pb-1" id="menu_name_'.$menu['menu_id'].'">'.$menu['menuname_name'].'</div>';
                }else{
                    echo '<div class="col-sm-9 pt-1 pb-1" id="menu_name_'.$menu['menu_id'].'">&nbsp;&nbsp;&nbsp;'.$menu['menuname_name'].'</div>';
                }
                echo '
                    <div class="custom-control custom-switch col-sm-3 pt-1 pb-1">
                        <input type="checkbox" class="custom-control-input" id="menu_'.$menu['menu_id'].'" onclick="switch_menu_access(\''.$menu['menu_id'].'\')" name="menu['.$menu['menu_id'].']" value="'.$menu['menu_id'].'">
                        <label class="custom-control-label font-weight-normal" for="menu_'.$menu['menu_id'].'" id="menu_lable_'.$menu['menu_id'].'">'.multi_lang('no').'</label>
                    </div>
                ';
            }
        }
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
