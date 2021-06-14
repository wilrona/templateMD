<?php

$tax = tr_taxonomy('Magazine', 'Magazines');
$tax->setHierarchical();
$tax->setArgument('show_in_rest', true);
$tax->setArgument('show_in_graphql', true);
$tax->setArgument('graphql_single_name', 'magazine');
$tax->setArgument('graphql_plural_name', 'magazines');
$tax->addPostType('post');
$tax->setMainForm(function(){
    $form = tr_form();
    echo $form->text('numero')->setLabel('Numero du magazine')->setHelp('Ce champ indique le numero de parution du magazine');
    echo $form->image('image')->setLabel('Image');
    echo $form->file('magazine_pdf')->setLabel('Magazine complet en PDF (Pour les abonnées)')->setHelp('Ce fichier sera telechargeable a partir de la page du magazine');

    echo $form->search('magazine_pdf_sell')->setLabel('Magazine complet en PDF (Achat unique)')->setPostType("isell-product")->setHelp('Ce fichier sera téléchargeable pour la vente unique.');

    echo $form->toggle('show_home')->setLabel("Mettre en avant à l'accueil");
    echo $form->toggle('online')->setLabel('Publier le magazine en ligne');


});

