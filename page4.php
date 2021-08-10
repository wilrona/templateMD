<?php

/* Template Name: Page Blog */

global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}

$context = Timber::get_context();

$context['posts'] = Timber::get_posts(
    array(
        'post_type' => 'post',
        'posts_per_page' => 6,
        'paged' => $paged,
        'lang'  => pll_current_language()
    )
);
$post = new TimberPost();
$context['post'] = $post;
$context['pagination'] = Timber::get_pagination();

Timber::render( array( 'page-blog.twig' ), $context );
