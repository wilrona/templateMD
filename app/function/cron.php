<?php

use App\Models\Currency;

function update_currency_value () {

    $currency = new Currency;
    $currency = $currency->whereMeta('default_currency', '=', 1)->first();

    $source_currency = tr_posts_field('currency', $currency->ID);

    $currencies = new Currency;
    $currencies = $currencies->where('post_status','publish')->where('ID', '!=', $currency->ID)->get();

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

    endif;

}

add_action('update_currency', 'update_currency_value');

if(!wp_next_scheduled('update_currency')):
    wp_schedule_event(time(), 'hourly', 'update_currency');
endif;