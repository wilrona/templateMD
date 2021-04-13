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

    };

    $social = function () use ($form) {

        echo '<h2 class="uk-padding-remove-bottom uk-text-center uk-margin-top">Social </h2>';

        echo $form->text('facebook')->setLabel('Lien vers la page Facebook');
        echo $form->text('twitter')->setLabel('Lien vers le compte Twitter');
        echo $form->text('instagram')->setLabel('Lien vers le compte Instagram');
        echo $form->text('youtube')->setLabel('Lien vers la chaine Youtube');
    };

    $page = function () use ($form) {

        echo '<h2 class="uk-padding-remove-bottom uk-text-center uk-margin-top">Définition des pages </h2>';

        echo $form->search('login_url')->setLabel('Lien de page de login')->setPostType('page');
        echo $form->checkbox('active_login_url')->setLabel('Activer la page de login');
    };

    // Save
    $save = $form->submit('Enregistrement');

    // Layout
    tr_tabs()->setSidebar($save)
        ->addTab('Logo and branding', $branding)
        ->addTab('Social Media', $social)
        ->addTab('Pages', $page)
        ->render();
    echo $form->close();
    ?>

</div>
