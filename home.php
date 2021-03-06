<?php
/* Template Name: Home page */

use Timber\Timber;

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;


$related_args = array(
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'lang'             => pll_current_language()
);

$context['related'] = Timber::get_posts($related_args);

Timber::render(  'page-home.twig' , $context );
