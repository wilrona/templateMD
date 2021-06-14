<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

use Timber\Timber;

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
$context['post']->download_file = wp_get_attachment_url(tr_posts_field('article_pdf', $post->ID));

$user = get_current_user_id();
$user_can_read = false;

if($user):
    $user_can_read = rcp_user_can_access($user, $post->ID);
else:
    $user_can_read = !rcp_is_restricted_content($post->ID);
endif;

$context['post']->user_can_read = $user_can_read;

$terms = get_the_terms( $post->ID, 'category' );

if ( empty( $terms ) ) $terms = array();

$term_list = wp_list_pluck( $terms, 'slug' );

$related_args = array(
    'post_type' => 'post',
    'posts_per_page' => 6,
    'post_status' => 'publish',
    'post__not_in' => array( $post->ID ),
    'orderby' => 'rand',
    'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => $term_list
        )
    )
);

$context['related'] = Timber::get_posts($related_args);

$download_id = false;
$product_price = false;

if(get_post_meta($post->ID, 'article_pdf_sell')){
    $download_id = get_post_meta($post->ID, 'article_pdf_sell', true);
    $product_price = get_post_meta($download_id, 'product_price', true);
}

$context['download_id'] = $download_id;
$context['product_price'] = $product_price;

Timber::render( array( 'single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig' ), $context );

