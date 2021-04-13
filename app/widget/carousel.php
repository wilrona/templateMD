<?php


use TypeRocket\Register\BaseWidget;

class Carousel_Widget extends BaseWidget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct( 'Carousel_Widget', 'Carousel des articles', [
            'classname' => 'Carousel_Widget',
            'description' => 'Afficher les articles en carousel.'
        ] );
    }

    public function backend($fields)
    {
        // TODO: Implement backend() method.

        echo $this->form->text('posts_per_page')->setType('number')->setLabel('Nombre Flash Infos')
            ->setAttribute('min', 4)->setDefault(4);

        echo $this->form->search('categorie_carousel')->setLabel('Catégorie à afficher')
            ->setTaxonomy('category');

    }

    public function frontend($args, $fields)
    {
        // TODO: Implement frontend() method.

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($fields['posts_per_page']),
        );

        $args['cat'] = array($fields['categorie_carousel']);

        $currentCat = get_term($fields['categorie_carousel']);

        $posts = get_posts($args);

        echo $args['before_widget'];

        ?>

        <section class="section mb-0">
            <div class="title-wrap title-wrap--line title-wrap--pr">
                <h3 class="section-title"><?= $currentCat->name ?></h3>
            </div>

            <!-- Slider -->
            <div id="owl-posts" class="owl-carousel owl-theme owl-carousel--arrows-outside">
                <?php foreach ($posts as $post): ?>
                    <article class="entry thumb thumb--size-1">
                        <div class="entry__img-holder thumb__img-holder" style="background-image: url('<?= get_the_post_thumbnail_url($post->ID) ? get_the_post_thumbnail_url($post->ID) : get_template_directory_uri().'/img/image-not-found.png' ?>');">
                            <div class="bottom-gradient"></div>
                            <div class="thumb-text-holder">
                                <h2 class="thumb-entry-title">
                                    <a href="<?= get_permalink($post->ID) ?>"><?= $post->post_title ?></a>
                                </h2>
                            </div>
                            <a href="<?= get_permalink($post->ID) ?>" class="thumb-url"></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div> <!-- end slider -->

        </section>


        <?php


        echo $args['after_widget'];

    }

    public function save($new_fields, $old_fields)
    {
        // TODO: Implement save() method.
        return $new_fields;
    }
}
