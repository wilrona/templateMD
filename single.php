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

Timber::render( array( 'single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig' ), $context );

