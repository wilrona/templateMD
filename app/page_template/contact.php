<?php

$box1 = tr_meta_box('hero_page3')->setLabel('Heros');
$box1->addScreen('page'); // updated
$box1->setCallback(function(){
    $form = tr_form();
    echo $form->text('hero_text_one')->setLabel('Text un');
    echo $form->text('hero_text_two')->setLabel('Text deux');
    echo "<hr/>";
    echo $form->text('contact_form')->setLabel('Formulaire de contact')->setHelp('Utilisez un shortcode (exple contact form 7)');

});



add_action('admin_head', function () use ($box1) {
    if(get_page_template_slug(get_the_ID()) !== 'contact.php'):
        remove_meta_box( $box1->getId(), 'page', 'normal');
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
