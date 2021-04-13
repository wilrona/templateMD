<?php


use TypeRocket\Register\BaseWidget;

class MultiCategory_Widget extends BaseWidget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct( 'MultiCategory_Widget', 'Double Categorie', [
            'classname' => 'MultiCategory_Widget',
            'description' => 'Afficher les articles de deux categories maximums.'
        ] );
    }

    public function backend($fields)
    {
        // TODO: Implement backend() method.

        echo $this->form->repeater('categories_doubles')->appendField(
            $this->form->search('categorie')->setLabel('Catégorie')
                ->setTaxonomy('category')
        )->setLabel('Categories concernées')->setLimit(2);

    }

    public function frontend($args, $fields)
    {
        // TODO: Implement frontend() method.

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 7,
        );

        $cat_post = [];

        if($fields['categories_doubles'] && count($fields['categories_doubles'])){

            foreach ($fields['categories_doubles'] as $cat):

                $argsCat = $args;
                $argsCat['cat'] = array($cat['categorie']);

                $catPost = get_posts($argsCat);
                $currentCat = get_term($cat['categorie']);

                $cat_post[] = array(
                    'category' => $currentCat,
                    'post' => $catPost
                );

                wp_reset_query();

            endforeach;


        }

        echo $args['before_widget'];

        ?>

        <!-- Posts from categories -->
        <section class="section mb-0">
            <div class="row">

                <?php

                    foreach ($cat_post as $cat):

                    $key = 1;
                ?>
                    <div class="col-md-6">
                    <div class="title-wrap title-wrap--line">
                        <h3 class="section-title"><?= $cat['category']->name ?></h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <article class="entry thumb thumb--size-3">
                                <div class="entry__img-holder thumb__img-holder" style="background-image: url('<?= get_the_post_thumbnail_url($cat['post'][0]->ID) ? get_the_post_thumbnail_url($cat['post'][0]->ID) : get_template_directory_uri().'/img/image-not-found.png' ?>');">
                                    <div class="bottom-gradient"></div>
                                    <div class="thumb-text-holder thumb-text-holder--1">
                                        <h2 class="thumb-entry-title">
                                            <a href="<?= get_permalink($cat['post'][0]->ID) ?>"><?= $cat['post'][0]->post_title ?></a>
                                        </h2>
                                        <ul class="entry__meta">
<!--                                            <li class="entry__meta-author">-->
<!--                                                <span>by</span>-->
<!--                                                <a href="#">DeoThemes</a>-->
<!--                                            </li>-->
                                            <li class="entry__meta-date">
                                                Publié le <?= get_the_date('j F Y', $cat['post'][0]->ID) ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <a href="<?= get_permalink($cat['post'][0]->ID) ?>" class="thumb-url"></a>
                                </div>
                            </article>
                        </div>
                        <div class="col-lg-6">
                            <ul class="post-list-small post-list-small--dividers post-list-small--arrows mb-24">
                                <?php foreach ($cat['post'] as $post): ?>

                                    <?php if($key > 1) : ?>

                                        <li class="post-list-small__item">
                                            <article class="post-list-small__entry">
                                                <div class="post-list-small__body">
                                                    <h3 class="post-list-small__entry-title crop-text-2">
                                                        <a href="<?= get_permalink($post->ID) ?>"><?= $post->post_title ?></a>
                                                    </h3>
                                                </div>
                                            </article>
                                        </li>

                                    <?php endif; ?>

                                <?php $key++; endforeach; ?>
                            </ul>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </section> <!-- end posts from categories -->


        <?php


        echo $args['after_widget'];

    }

    public function save($new_fields, $old_fields)
    {
        // TODO: Implement save() method.
        return $new_fields;
    }
}
