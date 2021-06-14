<?php


use TypeRocket\Register\BaseWidget;

class Magazine_Widget extends BaseWidget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct( 'Magazine_Widget', 'Last Magazine', [
            'classname' => 'Magazine_Widget',
            'description' => 'Affiche le dernier magazine publié.'
        ] );
    }

    public function backend($fields)
    {
        // TODO: Implement backend() method.

        echo $this->form->text('title')->setLabel('Titre du block');
    }

    public function frontend($args, $fields)
    {
        // TODO: Implement frontend() method.

        $title = apply_filters( 'widget_title', $fields['title'] );

        $edition = get_terms( array(
            'taxonomy' => 'magazine',
            'orderby' => 'none',
            'order' => 'DESC',
            'parent'   => 0,
            'hide_empty' => false
        ) );
        $latest_edition = $edition[0];

        if(get_term_meta($latest_edition->term_id, 'show_home', true) && get_term_meta($latest_edition->term_id, 'online', true)) :

        echo $args['before_widget'];

        ?>

        <aside class="widget widget-popular-posts">
            <h4 class="widget-title"><?= $title ?></h4>
            <article class="entry featured-posts-grid__entry">
                <div class="entry__img-holder card__img-holder">
                    <a href="<?= get_term_link($latest_edition->term_id, 'magazine') ?>">
                        <div class="">
                            <img data-src="<?= wp_get_attachment_image_url(get_term_meta($latest_edition->term_id, 'image', true), 'full') ?>" src="<?= get_template_directory_uri() ?>/img/image-not-found.png" class="entry__img lazyload" alt="">
                        </div>
                    </a>
                </div>

                <div class="entry__body mt-32">
                    <div class="entry__header">
                        <h2 class="entry__title crop-text-2">
                            <a href="<?= get_term_link($latest_edition->term_id, 'magazine') ?>"><?= $latest_edition->name ?></a>
                        </h2>
<!--                        <ul class="entry__meta">-->
<!--                            <li class="entry__meta-date">-->
<!--                                Publié le Jan 21, 2018-->
<!--                            </li>-->
<!--                        </ul>-->
                    </div>
                </div>
            </article>
        </aside>

        <?php


        echo $args['after_widget'];

        endif;

    }

    public function save($new_fields, $old_fields)
    {
        // TODO: Implement save() method.
        return $new_fields;
    }
}
