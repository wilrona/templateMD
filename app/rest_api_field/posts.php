<?php

register_rest_field('post','download_file', array('get_callback' => 'get_download_file'));

function get_download_file($object, $field_name, $request)
{
    if($object['id']):
        $field_data = get_post_meta( $object['id'], 'article_pdf', true );
        if($field_data):
            return wp_get_attachment_url($field_data);
        else:
            return '';
        endif;
    else:
        return '';
    endif;

}

register_rest_field('post','user_can_read', array('get_callback' => 'get_user_can_read'));
function get_user_can_read($object, $field_name, $request)
{

    $user = get_current_user_id();

    if($object['id']):

        if($user):
            return rcp_user_can_access($user, $object['id']);
        else:
            return !rcp_is_restricted_content($object['id']);
        endif;

    else:
        return false;
    endif;

}


register_rest_field('post','except', array('get_callback' => 'get_except'));
function get_except($object, $field_name, $request)
{
    $content = get_the_content($object['id']);
    $content = preg_replace("/<img[^>]+\>/i", " ", $content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]>', $content);

    return wp_trim_words ($content, 55, '...' );

}

register_rest_field('post','thumbnail_url', array('get_callback' => 'get_thumbnail_url'));
function get_thumbnail_url($object, $field_name, $request)
{
    return get_the_post_thumbnail_url($object['id']);

}

register_rest_field('post','category_name', array('get_callback' => 'get_category_name'));
function get_category_name($object, $field_name, $request)
{
    $categories = get_the_terms($object['id'], 'category');
    return $categories;

}


register_rest_field('post','pdf_sell_data', array('get_callback' => 'get_pdf_sell_data'));
function get_pdf_sell_data($object, $field_name, $request)
{

    $download_id = false;
    $product_price = "";

    if(get_post_meta($object['id'], 'article_pdf_sell')){
        $download_id = get_post_meta($object['id'], 'article_pdf_sell', true);
        $product_price = get_post_meta($download_id, 'product_price', true);
    }

    $currency = "";

    if(function_exists("isell_get_options")){
        $options = isell_get_options();
        $currency = $options['store']['currency'];
    }

    $data = array(
        'download_id' => $download_id,
        'product_price' => $product_price ? $product_price." ".$currency : $product_price,
        'download_link' => $download_id ? site_url()."?iproduct=".$download_id : false,
        'abonnement' => site_url().'/membre/votre-abonnement/'
    );

    return $data;

}
