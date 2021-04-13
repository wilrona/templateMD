<?php

$post_type = tr_post_type('Currency', 'Currencies');
$post_type->setIcon('money');
$post_type->setArgument('supports', ['title']);
$post_type->setArgument('show_in_rest', true);
$post_type->setArgument('show_in_graphql', true);
$post_type->setArgument('graphql_single_name', 'currency');
$post_type->setArgument('graphql_plural_name', 'currencies');
$post_type->setAdminOnly();
$post_type->setTitlePlaceholder('Name of currency');

$post_type->removeColumn('date');

$post_type->addColumn('value_currency', false, 'Value', function($value){
    global $post;
    $primary = tr_posts_field('default_currency', $post->ID);
//    var_dump($primary);
    $current_val = $value !== null ? $value : '';
    echo $primary ? 1 : $current_val;
}, 'string');

$post_type->addColumn('default_currency', false, 'Primary Currency', function($value){
    global $post;
    $form = tr_form();
    $checked = false;
    if($value) $checked = true;

    echo $form->toggle('default_currency')
        ->setLabel('')
        ->setAttribute('class', 'default_currency')
        ->setAttribute('id', $post->ID)
        ->setSetting('default', $checked);
}, 'string');

$box1 = tr_meta_box('currency_data')->setLabel('Informations of currency');
$box1->addPostType( $post_type->getId() );
$box1->setCallback(function(){

    $form = tr_form();

    $about_currency = function() use ($form) {

        $options = [
            'Select currency' => null
        ];

        $currencies = devise();
        $available_symboles = available_symbole();

        foreach ($currencies as $key => $currency):
            if(in_array($key, $available_symboles)):
                $options[$key.' - '.$currency] = $key;
            endif;
        endforeach;


        echo $form->select('currency')->setLabel('Currency code')->setOptions($options)->setSetting('default', null);

        $options_symbole = [
            'Select symbole' => null
        ];

        $symboles = symbole();
        foreach($symboles as $key => $symbole):
            if(in_array($key, $available_symboles)):
                $options_symbole[$key.' - '.$symbole] = $key;
            endif;
        endforeach;

        echo $form->select('symbole')->setLabel('Symbole')->setOptions($options_symbole)->setSetting('default', null);

        $options_symbole_pos = [
            'Symbol pos' => null,
            'Left' => 'left_position',
            'Right' => 'right_position',
            'Left Space' => 'left_space_position',
            'Right Space' => 'right_space_position'
        ];

        echo $form->select('position')->setLabel('Symbol Position')->setOptions($options_symbole_pos)->setSetting('default', null);
    };

    $country_currency = function() use ($form){

        $options = [
            'Select country' => null,
        ];

        $countries = country();

        foreach ($countries as $key => $country):
            $options[$country] = $key;
        endforeach;

        $repeater = $form->repeater('countries')->setFields([
            $form->select('attendant')->setLabel('Attendant')->setOptions($options)->setSetting('default', null)
        ])->setLabel('Countries to which to apply this currency')->setHelp('Leave blank if not important');

        echo $repeater;

    };

    $tabs = tr_tabs();

    $tabs->addTab('About', $about_currency);
    $tabs->addTab('GeoIP rules', $country_currency);

    $tabs->render('box');


});


