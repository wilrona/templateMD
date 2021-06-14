<?php

$post_type = tr_post_type('magazine archive', 'magazines archives');
$post_type->setTitlePlaceholder('Titre du magazine (Facultatif)');
$post_type->setArgument('supports', ['title', 'thumbnail']);
$post_type->setArgument('show_in_rest', true);
$post_type->setIcon('books');


$box1 = tr_meta_box('more_data_editions')->setLabel('Ajouter le magazine');
$box1->addPostType('magazine_archive');
$box1->setCallback(function(){

    $form = tr_form();

    echo $form->search('magazine')->setLabel('Magazine associé')->setTaxonomy('magazine');

    echo "<hr />";

});
