<?php

register_rest_field(array('course'), 'gb_university', array('get_callback' => 'gb_get_university'));

function gb_get_university($object, $field_name, $request) {

    if($object['id']):
        $university_id = get_post_meta( $object['id'], 'university', true );
        $university_data = get_post($university_id);
        return $university_data->post_title;
    else:
        return;
    endif;

}