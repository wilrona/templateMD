<?php

add_action( 'graphql_register_types', function() {

    register_graphql_field('specialisation', 'logo', [
        'type' => 'String',
        'resolve' => function ($post) {
            $image = wp_get_attachment_image_url(get_term_meta($post->term_id, 'logo', true), 'full');
            return $image;
        }
    ]);

    register_graphql_field( 'specialisation', 'university_count', [
        'type' => 'Integer',
        'resolve' => function( $post ) {
            //return $count;

            $args = array(
                'post_type'     => 'course', //post type, I used 'product'
                'post_status'   => 'publish', // just tried to find all published post
                'posts_per_page' => -1,  //show all
                'tax_query' => array(
                    array (
                        'taxonomy' => 'specialisation',
                        'field' => 'slug',
                        'terms' => $post->slug,
                    )
                )
            );

            $query = get_posts( $args);
            $ids = [];

            foreach ($query as $univ){
                $univ_id = get_post_meta($univ->ID, 'university', true);
                if(!in_array($univ_id, $ids)){
                    array_push($ids, $univ_id);
                }
            }

            return (int)count($ids);
        }
    ]);

});
