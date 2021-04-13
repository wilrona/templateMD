<?php


use TypeRocket\Register\BaseWidget;

class PostPopular_Widget extends BaseWidget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct( 'PostPopular_Widget', 'Article populaire', [
            'classname' => 'PostPopular_Widget',
            'description' => 'Affiche les articles populaires dans le design du theme.'
        ] );
    }

    public function backend($fields)
    {
        // TODO: Implement backend() method.

        echo $this->form->text('title')->setLabel('Titre');

        echo $this->form->text('posts_per_page')->setType('number')->setLabel('Nombre d\'article')
            ->setAttribute('min', 4)->setDefault(4);

    }

    public function frontend($args, $fields)
    {
        // TODO: Implement frontend() method.

        $title = apply_filters( 'widget_title', $fields['title'] );

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($fields['posts_per_page']),
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        );

        $posts = get_posts($args);

        if (!count($posts)){

            $args = array(
                'post_type' => 'post',
                'posts_per_page' => intval($fields['posts_per_page']),
            );

            $posts = get_posts($args);
        }

        echo $args['before_widget'];

        ?>

        <!-- Widget Popular Posts -->
        <aside class="widget widget-popular-posts">
            <h4 class="widget-title"><?= $title ?></h4>
            <ul class="post-list-small">
                <?php foreach ($posts as $post): ?>
                <li class="post-list-small__item">
                    <article class="post-list-small__entry clearfix">
                        <div class="post-list-small__img-holder">
                            <div class="thumb-container thumb-100">
                                <a href="<?= get_permalink($post->ID) ?>">
                                    <?php
                                    $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID),'full');
                                    $image   = aq_resize( $img_url, 72, 72, true );
                                    ?>
                                    <img data-src="<?= $image ?>" src="<?= get_template_directory_uri().'/img/image-not-found.png' ?>" alt="" class=" lazyload">
                                </a>
                            </div>
                        </div>
                        <div class="post-list-small__body">
                            <h3 class="post-list-small__entry-title">
                                <a href="<?= get_permalink($post->ID) ?>"><?= $post->post_title ?></a>
                            </h3>
                            <ul class="entry__meta">
<!--                                <li class="entry__meta-author">-->
<!--                                    <span>by</span>-->
<!--                                    <a href="#">DeoThemes</a>-->
<!--                                </li>-->
                                <li class="entry__meta-date">
                                    Publié le <?= get_the_date('j F Y', $post->ID) ?>
                                </li>
                            </ul>
                        </div>
                    </article>
                </li>
                <?php endforeach; ?>
            </ul>
        </aside> <!-- end widget popular posts -->

        <?php

        echo $args['after_widget'];

    }

    public function save($new_fields, $old_fields)
    {
        // TODO: Implement save() method.
        return $new_fields;
    }
}
