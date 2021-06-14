<?php

register_rest_field('magazine','download_file', array('get_callback' => 'get_download_file_mag'));

function get_download_file_mag($object, $field_name, $request)
{
    if($object['id']):
        $field_data = get_term_meta( $object['id'], 'magazine_pdf', true );
        if($field_data):
            return wp_get_attachment_url($field_data);
        else:
            return '';
        endif;
    else:
        return '';
    endif;

}


register_rest_field('magazine','cover_image', array('get_callback' => 'get_cover_mag'));

function get_cover_mag($object, $field_name, $request)
{
    if($object['id']):
        $field_data = get_term_meta( $object['id'], 'image', true );
        if($field_data):
            return wp_get_attachment_url($field_data);
        else:
            return '';
        endif;
    else:
        return '';
    endif;

}


register_rest_field('magazine','numero_mag', array('get_callback' => 'get_numero_mag'));

function get_numero_mag($object, $field_name, $request)
{
    return get_term_meta( $object['id'], 'numero', true );
}


register_rest_field('magazine','public_mag', array('get_callback' => 'get_public_mag'));

function get_public_mag($object, $field_name, $request)
{
    return boolval(get_term_meta( $object['id'], 'online', true ));
}

register_rest_field('magazine','pdf_sell_data', array('get_callback' => 'get_pdf_mag_sell_data'));
function get_pdf_mag_sell_data($object, $field_name, $request)
{

    $download_id = false;
    $product_price = "";

    if(get_term_meta($object['id'], 'magazine_pdf_sell')){
        $download_id = get_term_meta($object['id'], 'magazine_pdf_sell', true);
        $product_price = get_post_meta($download_id, 'product_price', true);
    }

    $currency = "";

    if(function_exists("isell_get_options")){
        $options = isell_get_options();
        $currency = $options['store']['currency'];
    }

    return array(
        'download_id' => $download_id,
        'product_price' => $product_price ? $product_price." ".$currency : $product_price,
        'download_link' => $download_id ? site_url()."?iproduct=".$download_id : false,
        'abonnement' => site_url().'/membre/votre-abonnement/'
    );

}

register_rest_field('magazine','user_can_read', array('get_callback' => 'get_user_can_read_mag'));
function get_user_can_read_mag($object, $field_name, $request)
{


    $can_download = false;

    if($object['id']):

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'post',
            'tax_query' => array(
                array(
                    'taxonomy' => 'magazine',
                    'terms' => array($object['id']),
                    'id' => 'term_id'
                )
            )
        );

        $posts = get_posts($args);

        $user = get_current_user_id();

        foreach ($posts as $post):

            $can_read = false;

            if($user):
                $can_read = rcp_user_can_access($user, $post->ID);
            else:
                $can_read = !rcp_is_restricted_content($post->ID);
            endif;

            if($can_read) {
                $can_download = true;
                break;
            }
        endforeach;

    endif;

    return $can_download;

}

