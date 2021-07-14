<?php

$box1 = tr_meta_box('hero_page2')->setLabel('Heros');
$box1->addScreen('page'); // updated
$box1->setCallback(function(){
    $form = tr_form();
    echo $form->text('hero_text_one')->setLabel('Text un');
    echo $form->text('hero_text_two')->setLabel('Text deux');
    echo "<hr/>";
    echo $form->text('contact_form')->setLabel('Formulaire de contact')->setHelp('Utilisez un shortcode (exple contact form 7)');

});

$box2 = tr_meta_box('more')->setLabel('Plus d\'information');
$box2->addScreen('page'); // updated
$box2->setCallback(function(){
    $form = tr_form();

    echo $form->checkbox('show_more')->setLabel('Afficher le block');
    echo "<hr/>";

    echo $form->editor('more_content')->setLabel('Contenu');
    echo $form->image('more_image')->setLabel('Image de garde');

    echo $form->repeater('more_images')->setFields([
        $form->text('title')->setLabel('Titre'),
        $form->image('image')->setLabel('Image')
    ])->setLabel('Autres images');


});

$box3 = tr_meta_box('accordions')->setLabel('Information dans un accordion');
$box3->addScreen('page'); // updated
$box3->setCallback(function(){
    $form = tr_form();

    echo $form->checkbox('show_accordions')->setLabel('Afficher le block');
    echo "<hr/>";

    echo $form->text('titre_accordion')->setLabel('Titre de l\'accordion');
    echo $form->repeater('accordions')->setFields([
        $form->text('title_group')->setLabel('Titre du groupe'),
        $form->repeater('elements')->setFields([
            $form->text('title')->setLabel('Titre'),
            $form->editor('content')->setLabel('Contenu')
        ])
    ])->setLabel('Ajouter vos informations');

});

$box4 = tr_meta_box('service')->setLabel('Information dans des sections');
$box4->addScreen('page'); // updated
$box4->setCallback(function(){
    $form = tr_form();

    $options = [
        'Model 1 (image a gauche)' => 'img_left_one',
        'Model 1 (image a droite)' => 'img_right_one',
        'Model 2 (image a gauche)' => 'img_left_two',
        'Model 2 (image a droite)' => 'img_right_two',
    ];

    echo $form->repeater('sections')->setFields([
        $form->color('fond_color')->setLabel('Couleur de fond'),
        $form->color('color_title')->setLabel('Couleur du titre'),
        $form->color('color_content')->setLabel('Couleur du contenu'),
        $form->select('type')->setLabel('Type de block')->setOptions($options),
        $form->text('title')->setLabel('Titre'),
        $form->editor('content')->setLabel('Contenu'),
        $form->image('image')->setLabel('Image')
    ])->setLabel('Liste des sections');

});

add_action('admin_head', function () use ($box1, $box2, $box3, $box4) {
    if(get_page_template_slug(get_the_ID()) !== 'page2.php' && get_page_template_slug(get_the_ID()) !== 'page3.php'):
        remove_meta_box( $box1->getId(), 'page', 'normal');
        remove_meta_box( $box2->getId(), 'page', 'normal');
        remove_meta_box( $box3->getId(), 'page', 'normal');
        remove_meta_box( $box4->getId(), 'page', 'normal');
    endif;
});

//add_filter( 'use_block_editor_for_post_type', 'my_disable_gutenberg', 10, 2 );
//function my_disable_gutenberg( $current_status, $post_type ) {
//
//    // Disabled post types
//    $disabled_post_types = array( 'page' );
//
//    // Change $can_edit to false for any post types in the disabled post types array
//    if ( in_array( $post_type, $disabled_post_types, true ) && get_page_template_slug(get_the_ID()) === 'page.php' ) {
//        $current_status = false;
//    }
//
//    return $current_status;
//}
