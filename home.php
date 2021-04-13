<?php
/* Template Name: Home page */

use Timber\Timber;

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 4,
//    'meta_query' => array(
//        array(
//            'key'     => 'une',
//            'compare' => '==',
//            'value'   => true
//        ),
//        array(
//            'key' => 'une',
//            'compare' => 'EXISTS'
//        ),
//    ),
);

$context['post_une'] = Timber::get_posts($args);


Timber::render(  'page-home.twig' , $context );
