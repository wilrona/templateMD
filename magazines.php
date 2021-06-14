<?php
/* Template Name: Magazine page */

use Timber\Timber;

global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

$per_page = 6;
$number_of_series = count( get_terms( "magazine",array("hide_empty"=> "1") ) );
$offset      = $per_page * ( $paged - 1);

$args = array(
    "offset"       => $offset,
    "number"       => $per_page,
    "hide_empty"   => "1",
    "fields" => 'all'
);

$terms_found = get_terms('magazine', $args);

$posts = [];

foreach ($terms_found as $term):
    $current_term = new \Timber\Term($term->term_id);
    if(get_term_meta($term->term_id, 'online', true)) array_push($posts, $current_term);
endforeach;

$context['posts'] = $posts;

Timber::render(  'page-magazine.twig' , $context );
