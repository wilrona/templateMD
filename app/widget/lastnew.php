<?php


use TypeRocket\Register\BaseWidget;

class LastNew_Widget extends BaseWidget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct( 'LastNew_Widget', 'Last News', [
            'classname' => 'LastNew_Widget',
            'description' => 'Ce widget affiche les derniers articles d\'un groupe de categorie.'
        ] );
    }

    public function backend($fields)
    {
        // TODO: Implement backend() method.
        echo $this->form->text('Title')->setLabel('Titre');
        echo $this->form->text('posts_per_page')->setType('number')->setLabel('Nombre Flash Infos')
            ->setAttribute('min', 4)->setDefault(4);
        echo $this->form->repeater('categories_news')->appendField(
            $this->form->search('categorie')->setLabel('Categorie')
                ->setTaxonomy('category')
        )->setLabel('Categories concernées');
    }

    public function frontend($args, $fields)
    {
        // TODO: Implement frontend() method.

        $title = apply_filters( 'widget_title', $fields['title'] );

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($fields['posts_per_page']),
        );

        $categories = [];
        $cat_post = [];

        if($fields['categories_news'] && count($fields['categories_news'])){
            foreach ($fields['categories_news'] as $cat):
                array_push($categories, intval($cat['categorie']));

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


            $args['cat'] = $categories;
        }

        $posts = get_posts($args);

        echo $args['before_widget'];

        ?>

        <!-- Latest News -->
        <div class="section tab-post mb-16">
            <div class="title-wrap title-wrap--line">
                <h3 class="section-title"><?= $title ?></h3>

                <div class="tabs tab-post__tabs">
                    <ul class="tabs__list">
                        <li class="tabs__item tabs__item--active">
                            <a href="#tab-all" class="tabs__trigger">Tous</a>
                        </li>
                        <?php foreach ($cat_post as $cat): ?>
                            <li class="tabs__item">
                                <a href="#tab-<?= $cat['category']->slug ?>" class="tabs__trigger"><?= $cat['category']->name ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul> <!-- end tabs -->
                </div>
            </div>

            <!-- tab content -->
            <div class="tabs__content tabs__content-trigger tab-post__tabs-content">

                <div class="tabs__content-pane tabs__content-pane--active" id="tab-all">
                    <div class="row card-row">
                        <?php foreach ($posts as $post): ?>
                            <div class="col-md-6">
                                <article class="entry card">
                                    <div class="entry__img-holder card__img-holder">
                                        <a href="<?= get_permalink($post->ID) ?>">
                                            <div class="thumb-container thumb-70">
                                                <img data-src="<?= get_the_post_thumbnail_url($post->ID) ?>" src="<?= get_template_directory_uri() ?>/img/image-not-found.png" class="entry__img lazyload" alt="" />
                                            </div>
                                        </a>
                                        <span class="entry__meta-category entry__meta-category--label entry__meta-category--align-in-corner entry__meta-category--red">
                                            <?= wp_list_pluck(get_the_terms( $post->ID, 'category' ), 'name' )[0] ?>
                                        </span>
                                    </div>

                                    <div class="entry__body card__body">
                                        <div class="entry__header">

                                            <h2 class="entry__title crop-text-2">
                                                <a href="<?= get_permalink($post->ID) ?>"><?= $post->post_title ?></a>
                                            </h2>
                                            <ul class="entry__meta">
<!--                                                <li class="entry__meta-author">-->
<!--                                                    <span>by</span>-->
<!--                                                    <a href="#">DeoThemes</a>-->
<!--                                                </li>-->
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
                                                <?= wp_trim_words( $content, 30, '...' ) ?>
                                            </p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div> <!-- end pane 1 -->

                <?php foreach ($cat_post as $cat): ?>
                    <div class="tabs__content-pane" id="tab-<?= $cat['category']->slug ?>">
                        <div class="row card-row">
                            <?php foreach ($cat['post'] as $post): ?>
                                <div class="col-md-6">
                                    <article class="entry card">
                                        <div class="entry__img-holder card__img-holder">
                                            <a href="<?= get_permalink($post->ID) ?>">
                                                <div class="thumb-container thumb-70">
                                                    <img data-src="<?= get_the_post_thumbnail_url($post->ID) ?>" src="<?= get_template_directory_uri() ?>/img/image-not-found.png" class="entry__img lazyload" alt="" />
                                                </div>
                                            </a>
                                            <span class="entry__meta-category entry__meta-category--label entry__meta-category--align-in-corner entry__meta-category--red">
                                            <?= wp_list_pluck(get_the_terms( $post->ID, 'category' ), 'name' )[0] ?>
                                        </span>
                                        </div>

                                        <div class="entry__body card__body">
                                            <div class="entry__header">

                                                <h2 class="entry__title crop-text-2">
                                                    <a href="<?= get_permalink($post->ID) ?>"><?= $post->post_title ?></a>
                                                </h2>
                                                <ul class="entry__meta">
                                                    <!--                                                <li class="entry__meta-author">-->
                                                    <!--                                                    <span>by</span>-->
                                                    <!--                                                    <a href="#">DeoThemes</a>-->
                                                    <!--                                                </li>-->
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
                                                    <?= wp_trim_words( $content, 30, '...' ) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div> <!-- end pane 2 -->
                <?php endforeach; ?>

            </div> <!-- end tab content -->

        </div> <!-- end latest news -->

        <?php


        echo $args['after_widget'];

    }

    public function save($new_fields, $old_fields)
    {
        // TODO: Implement save() method.
        return $new_fields;
    }
}
