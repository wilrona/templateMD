<?php

// helper function to find a menu item in an array of items
function wpd_get_menu_item( $field, $object_id, $items ){
    foreach( $items as $item ){
        if( $item->$field == $object_id ) return $item;
    }
    return false;
}

function wpd_nav_menu_breadcrumbs( $menu ){
    // get menu items by menu id, slug, name, or object
    $items = wp_get_nav_menu_items( $menu );
    if( false === $items ){
        echo 'Menu not found';
        return;
    }
    // get the menu item for the current page
    $item = wpd_get_menu_item( 'object_id', get_queried_object_id(), $items );
    if( false === $item ){
        return;
    }
    // start an array of objects for the crumbs
    $menu_item_objects = array( $item );
    // loop over menu items to get the menu item parents
    while( 0 != $item->menu_item_parent ){
        $item = wpd_get_menu_item( 'ID', $item->menu_item_parent, $items );
        array_unshift( $menu_item_objects, $item );
    }
    // output crumbs
//    $crumbs = array();
//    foreach( $menu_item_objects as $menu_item ){
//        $link = '<a href="%s">%s</a>';
//        $crumbs[] = sprintf( $link, $menu_item->url, $menu_item->title );
//    }

    return $menu_item_objects;
}
