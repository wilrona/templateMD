<?php

use Timber\Timber;
use Timber\PostQuery;

global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}

$context = Timber::get_context();

$tax = get_queried_object();
$context['tax'] = $tax;

$args = array(
    'posts_per_page' => 6,
    'paged' => $paged,
    'cat' => array($tax->term_id),
    'post_type' => 'post',
);


$context['posts'] = new PostQuery($args);
$context['paged'] = $paged;


Timber::render( array( 'category.twig' ), $context );
