<?php

$box1 = tr_meta_box('a_la_une')->setLabel('Information complementaire');
$box1->addPostType('post');
$box1->setCallback(function(){

    $form = tr_form();

    echo $form->file('article_pdf')->setLabel('Articles en PDF (Pour les abonnées)')->setHelp('Ce fichier sera téléchargeable pour les clients abonnées.');

    echo "<hr />";

    echo $form->search('article_pdf_sell')->setLabel('Articles en PDF (Achat unique)')->setPostType("isell-product")->setHelp('Ce fichier sera téléchargeable pour la vente unique.');

    echo "<hr />";

    echo  $form->toggle('flash')->setLabel('Flash infos')->setHelp('Cet article sera affiche comme flash information sur la page d\'accueil de la version web et de la version Android et IOS.');

    echo "<hr />";

    echo  $form->toggle('une')->setLabel('Article à la une')->setHelp('Cet article sera affiche à la une sur la page d\'accueil');

});
