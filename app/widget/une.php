<?php


use TypeRocket\Register\BaseWidget;

class BestNews_Widget extends BaseWidget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct( 'BestNews_Widget', 'A la une', [
            'classname' => 'BestNews_Widget',
            'description' => 'Les articles des categories a la une. Nous conseillons de creer 
            une categorie "a la une" et de l\'associer.'
        ] );
    }

    public function backend($fields)
    {
        // TODO: Implement backend() method.
        echo $this->form->repeater('categories_bestnews')->appendField(
            $this->form->search('categorie')->setLabel('Categorie')
                ->setTaxonomy('category')
        )->setLabel('Categories concernées');
    }

    public function frontend($args, $fields)
    {
        // TODO: Implement frontend() method.

        $title = apply_filters( 'widget_title', $fields['title'] );

        $posts_per_page = 4;
        $posts_with_une = [];

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query' => array(
                array(
                    'key'     => 'une',
                    'compare' => '==',
                    'value'   => true
                ),
                array(
                    'key' => 'une',
                    'compare' => 'EXISTS'
                ),
            ),
        );

        $categories = [];

        if($fields['categories_bestnews'] && count($fields['categories_bestnews'])){
            foreach ($fields['categories_bestnews'] as $cat):
                array_push($categories, intval($cat['categorie']));
            endforeach;


            $args['cat'] = $categories;
        }

        $posts_with_une = get_posts($args);

        $posts_without_une = [];

        if(count($posts_with_une) < $posts_per_page){

            $args = array(
                'fields' => 'ids',
                'post_type' => 'post',
                'posts_per_page' => $posts_per_page,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'meta_query' => array(
                    array(
                        'key'     => 'une',
                        'compare' => '==',
                        'value'   => true
                    ),
                    array(
                        'key' => 'une',
                        'compare' => 'EXISTS'
                    ),
                ),
            );

            $ids_with_me = get_posts($args);

            $args = array(
                'post_type' => 'post',
                'posts_per_page' => ($posts_per_page - count($posts_with_une)),
                'post__not_in' => $ids_with_me,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );

            $categories = [];

            if($fields['categories_bestnews'] && count($fields['categories_bestnews'])){
                foreach ($fields['categories_bestnews'] as $cat):
                    array_push($categories, intval($cat['categorie']));
                endforeach;
    
    
                $args['cat'] = $categories;
            }

            $posts_without_une = get_posts($args);

        }

        $posts = array_merge($posts_with_une, $posts_without_une);
        $key = 0;

        echo $args['before_widget'];

        ?>

        <?php foreach ($posts as $post): ?>

            <?php if ($key === 0) : ?> <div class="col-lg-6"> <?php endif; ?>

            <?php if($key % 2 === 0) : ?>

            <!-- Small post -->
                <div class="featured-posts-grid__item featured-posts-grid__item--sm">
                <article class="entry card post-list featured-posts-grid__entry">
                    <div class="entry__img-holder post-list__img-holder card__img-holder" style="background-image: url(<?= get_the_post_thumbnail_url($post->ID) ? get_the_post_thumbnail_url($post->ID) : get_template_directory_uri().'/img/image-not-found.png' ?>)">
                        <a href="<?= get_permalink($post->ID) ?>" class="thumb-url"></a>
                        <?php if (get_post_thumbnail_id($post->ID)) :
                            $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full');
                            $image   = aq_resize( $img_url, 300, 200, true );
                            ?>
                            <img src="<?= $image ?>" alt="" class="entry__img d-none">
                        <?php else:  ?>
                            <img src="<?= get_template_directory_uri().'/img/image-not-found.png' ?>" alt="" class="entry__img d-none">
                        <?php endif; ?>
                        <span class="entry__meta-category entry__meta-category--label entry__meta-category--align-in-corner entry__meta-category--red">
                            <?= wp_list_pluck(get_the_terms( $post->ID, 'category' ), 'name' )[0] ?>
                        </span>
                    </div>

                    <div class="entry__body post-list__body card__body">
                        <h2 class="entry__title">
                            <a href="<?= get_permalink($post->ID) ?>"><?= $post->post_title ?></a>
                        </h2>
                        <ul class="entry__meta">
<!--                            {#                                        <li class="entry__meta-author">#}-->
<!--                                {#                                            <span>by</span>#}-->
<!--                                {#                                            <a href="#">DeoThemes</a>#}-->
<!--                                {#                                        </li>#}-->
                            <li class="entry__meta-date">
                                Publié le <?= get_the_date('j F Y', $post->ID) ?>
                            </li>
                        </ul>
                    </div>
                </article>
            </div> <!-- end post -->

            <?php endif; ?>

            <?php if ($key % 2 == 1 && $key == 1) : ?>
                <!-- Small post -->
                <div class="featured-posts-grid__item featured-posts-grid__item--sm">
                    <article class="entry card post-list featured-posts-grid__entry">
                        <div class="entry__img-holder post-list__img-holder card__img-holder" style="background-image: url(<?= get_the_post_thumbnail_url($post->ID) ? get_the_post_thumbnail_url($post->ID) : get_template_directory_uri().'/img/image-not-found.png' ?>)">
                            <a href="<?= get_permalink($post->ID) ?>" class="thumb-url"></a>
                            <?php if (get_post_thumbnail_id($post->ID)) :
                                $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID),'full');
                                $image   = aq_resize( $img_url, 300, 200, true );
                            ?>

                                <img src="<?= $image ?>" alt="" class="entry__img d-none">
                            <?php else:  ?>
                                <img src="<?= get_template_directory_uri().'/img/image-not-found.png' ?>" alt="" class="entry__img d-none">
                            <?php endif; ?>
                            <span class="entry__meta-category entry__meta-category--label entry__meta-category--align-in-corner entry__meta-category--red">
                                <?= wp_list_pluck(get_the_terms( $post->ID, 'category' ), 'name' )[0] ?>
                            </span>
                        </div>

                        <div class="entry__body post-list__body card__body">
                            <h2 class="entry__title">
                                <a href="<?= get_permalink($post->ID) ?>"><?= $post->post_title ?></a>
                            </h2>
                            <ul class="entry__meta">
    <!--                            {#                                        <li class="entry__meta-author">#}-->
    <!--                                {#                                            <span>by</span>#}-->
    <!--                                {#                                            <a href="#">DeoThemes</a>#}-->
    <!--                                {#                                        </li>#}-->
                                <li class="entry__meta-date">
                                    Publié le <?= get_the_date('j F Y', $post->ID) ?>
                                </li>
                            </ul>
                        </div>
                    </article>
                </div>
                <!-- end post -->
            <?php endif ?>

            <?php if ($key == 2): ?> </div> <?php endif; ?>

            <?php if ($key === 3): ?>

                <div class="col-lg-6">

                <!-- Large post -->
                <div class="featured-posts-grid__item featured-posts-grid__item--lg">
                    <article class="entry card featured-posts-grid__entry">
                        <div class="entry__img-holder card__img-holder">
                            <a href="<?= get_permalink($post->ID) ?>">
                                <?php
                                    if (get_post_thumbnail_id($post->ID)) :

                                        $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID),'full');
                                        $image   = aq_resize( $img_url, 604, 356, true );
                                ?>
                                    <img src="<?= $image ? $image : $img_url ?>" alt="" class="entry__img">
                                <?php else:  ?>
                                    <img src="<?= get_template_directory_uri().'/img/image-not-found.png' ?>" alt="" class="entry__img">
                                <?php endif; ?>
                            </a>
                            <span class="entry__meta-category entry__meta-category--label entry__meta-category--align-in-corner entry__meta-category--red">
                                <?= wp_list_pluck(get_the_terms( $post->ID, 'category' ), 'name' )[0] ?>
                            </span>
                        </div>

                        <div class="entry__body card__body">
                            <h2 class="entry__title">
                                <a href="<?= get_permalink($post->ID) ?>"><?= $post->post_title ?></a>
                            </h2>
                            <ul class="entry__meta">
    <!--                            {#                                        <li class="entry__meta-author">#}-->
    <!--                                {#                                            <span>by</span>#}-->
    <!--                                {#                                            <a href="#">DeoThemes</a>#}-->
    <!--                                {#                                        </li>#}-->
                                <li class="entry__meta-date">
                                    Publié le <?= get_the_date('j F Y', $post->ID) ?>
                                </li>
                            </ul>
                        </div>
                    </article>
                </div>
                <!-- end large post -->
            </div>

            <?php endif; ?>


        <?php $key++; endforeach; ?>


        <?php


        echo $args['after_widget'];

    }

    public function save($new_fields, $old_fields)
    {
        // TODO: Implement save() method.
        return $new_fields;
    }
}
