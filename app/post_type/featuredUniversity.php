<?php

$post_type = tr_post_type('University Featured', 'Universities Featured');
$post_type->setIcon('diamond');
$post_type->setArgument('supports', ['title']);
$post_type->setArgument('show_in_rest', true);
$post_type->setArgument('show_in_graphql', true);
$post_type->setArgument('graphql_single_name', 'universityFeatured');
$post_type->setArgument('graphql_plural_name', 'universitiesFeatured');
$post_type->setAdminOnly();

$box1 = tr_meta_box('feature_university_data')->setLabel('Information of university featured');
$box1->addPostType( $post_type->getId() );
$box1->setCallback(function(){

    $form = tr_form();

    $type = function() use ($form) {

        $options = [
            'Select the type of feature' => '0',
            'Show in slider' => 'slider',
            'Show in homepage' => 'home',
            'Show in section other university in university page' => 'otherUniv',
            'Show in country page' => 'country',
        ];

        echo $form->select('type')->setLabel('Type of feature')->setOptions($options)->setSetting('default', '0');

        echo $form->row(
            $form->date('start_date')->setLabel('Start date')->setAttribute('id', 'from_date')->setAttribute('data-format', 'yy-mm-dd'),
            $form->date('end_date')->setLabel('End date')->setHelp('Leave blank if you don\'t want the broadcast to stop')->setAttribute('id', 'to_date')->setAttribute('data-format', 'yy-mm-dd')
        );

        echo "<small style='color:red'><b>Dates do not apply with featured slider mode</b></small>";

    };

    $featured = function() use ($form){

        $repeater = $form->repeater('featured_list')->setFields([
            $form->search('id')->setLabel('Search university')->setPostType('university'),
            $form->image('image')->setLabel('image of feature')->setHelp('Visible only if the featured model is <b>"Show in slider"</b>')
        ])->setLabel('List of unviersities featured')->setLimit(12);

        echo $repeater;

    };


    $tabs = tr_tabs();

    $tabs->addTab('Type of featured', $type);
    $tabs->addTab('Featured', $featured);

    $tabs->render('box');


});


