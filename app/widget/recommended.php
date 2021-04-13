<?php


use TypeRocket\Register\BaseWidget;

class Recommended_Widget extends BaseWidget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct( 'Recommended_Widget', 'Article Recommendé d\'une categorie', [
            'classname' => 'Recommended_Widget',
            'description' => 'Affichage vertical des articles d\'une categorie.'
        ] );
    }

    public function backend($fields)
    {
        // TODO: Implement backend() method.

        echo $this->form->text('title')->setLabel('Titre');

        echo $this->form->text('posts_per_page')->setType('number')->setLabel('Nombre d\'article')
            ->setAttribute('min', 4)->setDefault(4);

        echo $this->form->search('categorie_recommended')->setLabel('Article de la catégorie à afficher')
            ->setTaxonomy('category');

    }

    public function frontend($args, $fields)
    {
        // TODO: Implement frontend() method.

        $title = apply_filters( 'widget_title', $fields['title'] );

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($fields['posts_per_page']),
        );

        $args['cat'] = array($fields['categorie_recommended']);

        $posts = get_posts($args);

        echo $args['before_widget'];

        ?>

        <!-- Widget Recommended (Rating) -->
        <aside class="widget widget-rating-posts">

            <h4 class="widget-title"><?= $title ?></h4>
            <?php foreach ($posts as $post): ?>
                <article class="entry">
                    <div class="entry__img-holder">
                        <a href="<?= get_permalink($post->ID) ?>">
                            <div class="thumb-container thumb-60">
                                <img data-src="<?= get_the_post_thumbnail_url($post->ID) ?>" src="<?= get_template_directory_uri() ?>/img/image-not-found.png" class="entry__img lazyload" alt="">
                            </div>
                        </a>
                    </div>

                    <div class="entry__body">
                        <div class="entry__header">

                            <h2 class="entry__title">
                                <a href="<?= get_permalink($post->ID) ?>"><?= $post->post_title ?></a>
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
                    </div>
                </article>
            <?php endforeach; ?>
        </aside> <!-- end widget recommended (rating) -->




        <?php


        echo $args['after_widget'];

    }

    public function save($new_fields, $old_fields)
    {
        // TODO: Implement save() method.
        return $new_fields;
    }
}
