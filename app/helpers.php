<?php

use App\Models\User;

if (!function_exists('user_menu'))
{
    function user_menu($parent_id)
    {
        $menu = User::getMenu($parent_id)->get()->toArray();

        return $menu;
    }

    function echo_menu($parent_id)
    {
        $menu_array = User::getMenu($parent_id)->get()->toArray();

        //go through each top level menu item
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
                //echo the child menu
                echo_menu($menu['menu_id']);
                echo '</ul>';
            }
            echo '</li>';
        }
    }
}
