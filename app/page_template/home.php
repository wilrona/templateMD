<?php

$box1 = tr_meta_box('hero')->setLabel('Heros');
$box1->addScreen('page'); // updated
$box1->setCallback(function(){
    $form = tr_form();
    echo $form->text('heroTextOne')->setLabel('Text un');
    echo $form->text('heroTextTwo')->setLabel('Text deux');
    echo $form->search('linkContact')->setLabel('Lien vers la page de contact')->setPostType('page');
    echo "<hr/>";
    echo $form->text('linkVideo')->setLabel('Lien vers la video')->setHelp('Lien d\'une video en ligne');

});

$box2 = tr_meta_box('presentation')->setLabel('Presentation');
$box2->addScreen('page'); // updated
$box2->setCallback(function(){
    $form = tr_form();
    echo $form->checkbox('show_presentation')->setLabel('Afficher le block');
    echo "<hr/>";
    echo $form->image('image_presentation')->setLabel('Image de presentation');
    echo $form->text('titre_one_presentation')->setLabel('Titre de debut');
    echo $form->text('titre_two_presentation')->setLabel('Titre de fin');
    echo $form->editor('content_presentation')->setLabel('Contenu de la presentation');
});


$box3 = tr_meta_box('specialisation')->setLabel('Section de la specialisation');
$box3->addScreen('page'); // updated
$box3->setCallback(function(){
    $form = tr_form();

    echo $form->checkbox('show_specialisation')->setLabel('Afficher le block');
    echo "<hr/>";

    echo $form->text('titre_one_specialisation')->setLabel('Titre de debut');
    echo $form->text('titre_two_specialisation')->setLabel('Titre de fin');

    echo $form->repeater('list_specialisation')->setFields([
        $form->text('title')->setLabel('Titre'),
        $form->text('link_video')->setLabel('Lien vers une video en ligne')
    ])->setLabel('Liste des specialisation');
});

$box4 = tr_meta_box('professionnel')->setLabel('Informations sur les professionnels');
$box4->addScreen('page'); // updated
$box4->setCallback(function(){
    $form = tr_form();

    echo $form->checkbox('show_professionnel')->setLabel('Afficher le block');
    echo "<hr/>";

    echo $form->image('image_professionnel')->setLabel('Image de fond du block');

    echo $form->text('titre_one_professionnel')->setLabel('Titre de debut');
    echo $form->text('titre_two_professionnel')->setLabel('Titre de fin');

    echo $form->repeater('list_professionnel')->setFields([
        $form->text('name')->setLabel('Nom du professionne'),
        $form->text('poste')->setLabel('Poste du professionne'),
        $form->image('image')->setLabel('Photo du professionne'),
    ])->setLabel('Liste des professionnels');
});

$box5 = tr_meta_box('client')->setLabel('Client satisfait');
$box5->addScreen('page'); // updated
$box5->setCallback(function(){
    $form = tr_form();

    echo $form->checkbox('show_client')->setLabel('Afficher le block');
    echo "<hr/>";

    echo $form->text('titre_one_client')->setLabel('Titre de debut');
    echo $form->text('titre_two_client')->setLabel('Titre de fin');

    echo $form->repeater('list_client')->setFields([
        $form->editor('content')->setLabel('Description de l\'experience')
    ])->setLabel('Liste des clients');

});


add_action('admin_head', function () use ($box1, $box2, $box3, $box4, $box5) {
    if(get_page_template_slug(get_the_ID()) !== 'home.php'):
        remove_meta_box( $box1->getId(), 'page', 'normal');
        remove_meta_box( $box2->getId(), 'page', 'normal');
        remove_meta_box( $box3->getId(), 'page', 'normal');
        remove_meta_box( $box4->getId(), 'page', 'normal');
        remove_meta_box( $box5->getId(), 'page', 'normal');
//        remove_post_type_support('page', 'editor');
    else:
        remove_post_type_support('page', 'editor');
        remove_post_type_support('page', 'thumbnail');
    endif;
});
