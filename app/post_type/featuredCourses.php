<?php

$post_type = tr_post_type('Course featured', 'Courses Featured');
$post_type->setIcon('diamond');
$post_type->setArgument('supports', ['title']);
$post_type->setArgument('show_in_rest', true);
$post_type->setArgument('show_in_graphql', true);
$post_type->setArgument('graphql_single_name', 'courseFeatured');
$post_type->setArgument('graphql_plural_name', 'coursesFeatured');
$post_type->setAdminOnly();

$box1 = tr_meta_box('feature_course_data')->setLabel('Information of course featured');
$box1->addPostType( $post_type->getId() );
$box1->setCallback(function(){

    $form = tr_form();

    $type = function() use ($form) {

        $options = [
            'Select the type of feature' => '0',
            'Show in search result' => 'search',
            'Show in homepage' => 'home',
        ];

        echo $form->select('type')->setLabel('Type of feature')->setOptions($options)->setSetting('default', '0');

        echo $form->row(
            $form->date('start_date')->setLabel('Start date')->setAttribute('id', 'from_date')->setAttribute('data-format', 'yy-mm-dd'),
            $form->date('end_date')->setLabel('End date')->setHelp('Leave blank if you don\'t want the broadcast to stop')->setAttribute('id', 'to_date')->setAttribute('data-format', 'yy-mm-dd')
        );


    };

    $featured = function() use ($form){

        $searchField = new \App\Fields\CustomSearch('id', $form);

        $repeater = $form->repeater('featured_list')->setFields([
            $searchField->setLabel('Course')->setPostType('course')->setSearchAttribut('placeholder','Search Courses')
                ->setSearchAttribut('class', 'tr-link-course-search-input'),
            $form->text('priority')->setType('number')->setAttribute('min', 1)->setDefault(1)->setLabel('Display priority in search results')->setHelp('Taken into account when the type of featured is <b>"Show in search result"</b>')
        ])->setLabel('List of courses featured')->setLimit(12);

        echo $repeater;

    };

    $tabs = tr_tabs();

    $tabs->addTab('Type of feature', $type);
    $tabs->addTab('Featured', $featured);

    $tabs->render('box');


});
