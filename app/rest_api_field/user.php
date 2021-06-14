<?php

register_rest_field(array('user'), 'customer_id', array('get_callback' => 'get_customer_id'));


function get_customer_id($object, $field_name, $request){
    return rcp_get_customer_by_user_id($object['id']);
}

register_rest_field(array('user'), 'user_email', array('get_callback' => 'get_email'));

function get_email($object, $field_name, $request){
    $userData = get_user_by('id', $object['id']);
    return $userData->user_email;
}

register_rest_field(array('user'), 'display_name', array('get_callback' => 'get_display_name'));

function get_display_name($object, $field_name, $request){
    $userData = get_userdata($object['id']);
    return $userData->display_name;
}


register_rest_field(array('user'), 'nicename', array('get_callback' => 'get_nicename'));

function get_nicename($object, $field_name, $request){
    $userData = get_userdata($object['id']);
    return $userData->user_login;
}
