<?php


use TypeRocket\Register\BaseWidget;

class FlashInfo_Widget extends BaseWidget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct( 'FlashInfo_Widget', 'Flash Info', [
            'classname' => 'FlashInfo_Widget',
            'description' => 'Ce widget affiche articles en flash infos.'
        ] );
    }

    public function backend($fields)
    {
        // TODO: Implement backend() method.
        echo $this->form->text('Title')->setLabel('Titre');
        echo $this->form->text('posts_per_page')->setType('number')->setLabel('Nombre Flash Infos')
            ->setAttribute('min', 0)->setDefault(0)->setHelp('0 = tous les flash infos');
        echo $this->form->repeater('categories_flash')->appendField(
            $this->form->search('categorie')->setLabel('Categorie')
                ->setTaxonomy('category')
        )->setLabel('Categories concernées')
            ->setHelp('Laisse vide pour définir toutes les categories');
    }

    public function frontend($args, $fields)
    {
        // TODO: Implement frontend() method.

        $title = apply_filters( 'widget_title', $fields['title'] );

// before and after widget arguments are defined by themes

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($fields['posts_per_page']) ? intval($fields['posts_per_page']) : -1,
            'meta_query' => array(
                array(
                    'key'     => 'flash',
                    'compare' => '==',
                    'value'   => true
                ),
                array(
                    'key' => 'flash',
                    'compare' => 'EXISTS'
                ),
            ),
        );

        $categories = [];
        if($fields['categories_flash'] && count($fields['categories_flash'])){
            foreach ($fields['categories_flash'] as $cat):
                array_push($categories, intval($cat['categorie']));
            endforeach;

            $args['cat'] = $categories;
        }


        $posts = get_posts($args);

        echo $args['before_widget'];

        if(count($posts)):

        ?>

        <div class="trending-now">
            <span class="trending-now__label">
              <i class="ui-flash"></i>
              <span class="trending-now__text d-lg-inline-block d-none"><?= !empty( $title ) ? $title : "Flash Infos" ?></span>
            </span>
            <div class="newsticker">
                <ul class="newsticker__list">
                    <?php foreach ($posts as $post): ?>
                        <li class="newsticker__item"><a href="<?= get_post_permalink($post->ID) ?>" class="newsticker__item-url"><?= $post->post_title ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="newsticker-buttons">
                <button class="newsticker-button newsticker-button--prev" id="newsticker-button--prev" aria-label="next article"><i class="ui-arrow-left"></i></button>
                <button class="newsticker-button newsticker-button--next" id="newsticker-button--next" aria-label="previous article"><i class="ui-arrow-right"></i></button>
            </div>
        </div>

        <?php

        endif;

        echo $args['after_widget'];

    }

    public function save($new_fields, $old_fields)
    {
        // TODO: Implement save() method.
        return $new_fields;
    }
}
