<?php
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

// Setup Form
$form = tr_form()->useJson()->setGroup($this->getName());

?>

<h1>Theme Options</h1>
<div class="typerocket-container">
    <?php
    echo $form->open();

    $branding = function () use ($form) {

        echo '<h2 class="uk-padding-remove-bottom uk-text-center uk-margin-top">Logo and branding</h2>';

        echo $form->image('icon')->setLabel('Icone du site');
        echo $form->image('logo')->setLabel('Logo du site');
        echo $form->editor('aboutFooter')->setLabel('Description de l\'entreprise en pied de page');
        echo $form->image('imageFooter')->setLabel('Image de fond pour le formulaire du footer');
        echo $form->text('form_footer')->setLabel('Formulaire du footer')->setHelp('Inserer un shortcode de contact form 7 pour le formulaire de contact');

    };

    $social = function () use ($form) {

        echo '<h2 class="uk-padding-remove-bottom uk-text-center uk-margin-top">Contact et Reseau Social </h2>';

        echo $form->text('facebook')->setLabel('Lien vers la page Facebook');
        echo $form->text('linkedin')->setLabel('Lien vers le compte Linkedin');
        echo $form->text('instagram')->setLabel('Lien vers le compte Instagram');

        echo '<hr />';

        echo $form->text('phone')->setLabel('Numero de telephone');
        echo $form->text('email')->setLabel('Adresse Email');
        echo $form->text('location')->setLabel('Localisation ou Adresse');
    };

    // Save
    $save = $form->submit('Enregistrement');

    // Layout
    tr_tabs()->setSidebar($save)
        ->addTab('Logo and branding', $branding)
        ->addTab('Contact et RS', $social)
        ->render();
    echo $form->close();
    ?>

</div>
