<?php

use Timber\Timber;
use Timber\PostQuery;
use Timber\Term;

global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}

$context = Timber::get_context();

$tax = get_queried_object();
$context['tax'] = new Term($tax->term_id, $tax->taxonomy);
//$context['tax'] = $tax;

$args = array(
    'posts_per_page' => 6,
    'paged' => $paged,
    'post_type' => 'post',
    'tax_query' => array(
        array(
            'taxonomy' => 'magazine',
            'terms' => array($tax->term_id),
            'id' => 'term_id'
        )
    )
);

$allArgs = $args;
unset($allArgs['paged']);
$allArgs['posts_per_page'] = -1;

$posts = get_posts($allArgs);

$user = get_current_user_id();
$can_download = false;

foreach ($posts as $post):

    $can_read = false;

    if($user):
        $can_read = rcp_user_can_access($user, $post->ID);
    else:
        $can_read = !rcp_is_restricted_content($post->ID);
    endif;

    if($can_read) {
        $can_download = true;
        break;
    }
endforeach;


$context['posts'] = new PostQuery($args);
$context['paged'] = $paged;
$context['can_download'] = $can_download;

$download_id = false;
$product_price = false;

if(get_term_meta($tax->term_id, 'magazine_pdf_sell')){
    $download_id = get_term_meta($tax->term_id, 'magazine_pdf_sell', true);
    $product_price = get_post_meta($download_id, 'product_price', true);
}

$context['download_id'] = $download_id;
$context['product_price'] = $product_price;

Timber::render( array( 'taxonomy-' . $tax->taxonomy. '.twig', 'taxonomy.twig' ), $context );
