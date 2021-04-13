<?php


use TypeRocket\Register\BaseWidget;

class FromCategory_Widget extends BaseWidget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct( 'FromCategory_Widget', 'Article from categorie', [
            'classname' => 'FromCategory_Widget',
            'description' => 'Affichage horizontal les articles d\'une categorie.'
        ] );
    }

    public function backend($fields)
    {
        // TODO: Implement backend() method.

        echo $this->form->text('posts_per_page')->setType('number')->setLabel('Nombre d\'article')
            ->setAttribute('min', 4)->setDefault(4);

        echo $this->form->search('categorie_fromCat')->setLabel('Article de la catégorie à afficher')
            ->setTaxonomy('category');

    }

    public function frontend($args, $fields)
    {
        // TODO: Implement frontend() method.

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($fields['posts_per_page']),
        );

        $args['cat'] = array($fields['categorie_fromcat']);

        $currentCat = get_term($fields['categorie_fromcat']);

        $posts = get_posts($args);

        echo $args['before_widget'];

        ?>

        <!-- Worldwide News -->
        <section class="section">
            <div class="title-wrap title-wrap--line">
                <h3 class="section-title"><?= $currentCat->name ?></h3>
                <a href="<?= get_term_link($currentCat->term_id, 'category') ?>" class="all-posts-url">Voir tous les articles</a>
            </div>

            <?php foreach ($posts as $post) : ?>

            <article class="entry card post-list">
                <div class="entry__img-holder post-list__img-holder card__img-holder" style="background-image: url(<?= get_the_post_thumbnail_url($post->ID) ? get_the_post_thumbnail_url($post->ID) : get_template_directory_uri().'/img/image-not-found.png' ?>)">
                    <a href="<?= get_permalink($post->ID) ?>" class="thumb-url"></a>
                    <?php if (get_post_thumbnail_id($post->ID)) :
                        $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID),'full');
                        $image   = aq_resize( $img_url, 400, 240, true );
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
                    <div class="entry__header">
                        <h2 class="entry__title">
                            <a href="<?= get_permalink($post->ID) ?>">T<?= $post->post_title ?></a>
                        </h2>
                        <ul class="entry__meta">
<!--                            <li class="entry__meta-author">-->
<!--                                <span>by</span>-->
<!--                                <a href="#">DeoThemes</a>-->
<!--                            </li>-->
                            <li class="entry__meta-date">
                                Publié le <?= get_the_date('j F Y', $post->ID) ?>
                            </li>
                        </ul>
                    </div>
                    <div class="entry__excerpt">
                        <p>
                            <?php
                            $content = $post->post_content;
                            $content = preg_replace("/<img[^>]+\>/i", " ", $content);
                            $content = apply_filters('the_content', $content);
                            $content = str_replace(']]>', ']]>', $content);
                            ?>
                            <?= wp_trim_words( $content, 10, '...' ) ?>
                        </p>
                    </div>
                </div>
            </article>

            <?php endforeach; ?>

        </section> <!-- end worldwide news -->



        <?php


        echo $args['after_widget'];

    }

    public function save($new_fields, $old_fields)
    {
        // TODO: Implement save() method.
        return $new_fields;
    }
}
