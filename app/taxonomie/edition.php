<?php

$tax = tr_taxonomy('Magazine', 'Magazines');
$tax->setHierarchical();
$tax->setArgument('show_in_rest', true);
$tax->addPostType('post');
$tax->setMainForm(function(){
    $form = tr_form();
    echo $form->wpEditor('editorial')->setLabel('Editotial')->setSetting('options', ['media_buttons' => false, 'editor_class' => 'textareaH']);
    echo $form->image('image')->setLabel('Image');
    echo $form->toggle('show_home')->setLabel('Mettre en avant');
});

