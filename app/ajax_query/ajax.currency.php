<?php

use App\Models\Currency;
use GuzzleHttp\Client;

add_action('wp_ajax_load_set_default_currency', 'load_set_default_currency');
add_action('wp_ajax_nopriv_load_set_default_currency', 'load_set_default_currency');


function load_set_default_currency(){

    check_ajax_referer('load_set_default_currency_security', 'security');

    $item = $_POST['item'];

    $currencies = new Currency;
    $currencies = $currencies->where('post_status','publish')->where('ID', '!=', $item)->get();

    $source_currency = tr_posts_field('currency', $item);

    $key = tr_options_field('options.currency_key');
    $apiUrl = tr_options_field('options.currency_api');

    if($key && $apiUrl):

        foreach ($currencies as $current):

            $default = tr_posts_field('default_currency', $current->ID);

            if($default):
                $current->default_currency = false;
            endif;

            $destination_currency = tr_posts_field('currency', $current->ID);
            $url = $apiUrl.'?access_key='.$key.'&source='.$source_currency.'&currencies='.$destination_currency;
            $response = wp_remote_get($url);

            $data = load_request($response);
            $code = $source_currency.$destination_currency;

            $current->value_currency = $data->quotes->$code;

            $current->update();

        endforeach;

        $currency = new Currency();
        $currency->findById($item);
        $currency->default_currency = true;
        $currency->value_currency = 1;
        $currency->update();

    endif;

    wp_die();
}