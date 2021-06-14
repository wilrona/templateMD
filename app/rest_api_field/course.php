<?php

register_rest_field(array('course'), 'gb_university', array('get_callback' => 'gb_get_university'));

function gb_get_university($object, $field_name, $request) {
//    rcp_user_can_access(get_current_user_id(), $object['id']); si l'utilisateur a accès au post
//    rcp_get_customer_by_user_id(get_current_user_id()) les informations de l'utilisateur entant que customer
//    rcp_get_memberships(array(
//        "customer_id" => $object['id']
//    )); les souscriptions que les utilisateurs a actuellement.

    if($object['id']):
        $university_id = get_post_meta( $object['id'], 'university', true );
        $university_data = get_post($university_id);
        return $university_data->post_title;
    else:
        return;
    endif;

}
