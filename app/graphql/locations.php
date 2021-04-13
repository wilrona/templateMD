<?php

add_action( 'graphql_register_types', function() {

    register_graphql_field('location', 'flag', [
        'type' => 'String',
        'resolve' => function ($post) {
            $image = wp_get_attachment_image_url(get_term_meta($post->term_id, 'flag', true), 'full');
            return $image;
        }
    ]);

    register_graphql_field('location', 'video', [
        'type' => 'String',
        'resolve' => function ($post) {
            return get_term_meta($post->term_id, 'video', true);
        }
    ]);

    register_graphql_field('location', 'image', [
        'type' => 'String',
        'resolve' => function ($post) {
            $image = wp_get_attachment_image_url(get_term_meta($post->term_id, 'image', true), 'full');
            return $image;
        }
    ]);

    register_graphql_field('location', 'academic_year', [
        'type' => 'String',
        'resolve' => function ($post) {
            return get_term_meta($post->term_id, 'academic_year', true);
        }
    ]);

    register_graphql_field('location', 'is_country', [
        'type' => 'Boolean',
        'resolve' => function ($post) {
            $taxonomy_name = 'location';
            $terms = get_term_children( $post->term_id, $taxonomy_name );

            if(!empty( $terms ) && !is_wp_error( $terms )):
                $result = true;
            else:
                $result = false;
            endif;

            return $result;
        }
    ]);

    register_graphql_field('location', 'total_courses', [
        'type' => 'Integer',
        'resolve' => function ($post) {
            $taxonomy_name = 'location';
            $terms = get_term_children( $post->term_id, $taxonomy_name );

            $count = 0;

            if(!empty( $terms ) && !is_wp_error( $terms )):

                foreach ($terms as $term):

                    $args = array(
                        'post_type'     => 'course', //post type, I used 'product'
                        'post_status'   => 'publish', // just tried to find all published post
                        'posts_per_page' => -1,  //show all
                        'tax_query' => array(
                            'relation' => 'AND',
                            array(
                                'taxonomy' => 'location',  //taxonomy name  here, I used 'product_cat'
                                'field' => 'id',
                                'terms' => array( $term )
                            )
                        )
                    );

                    $query = new WP_Query( $args);

                    $count += (int)$query->post_count;

                endforeach;
            else:

                $args = array(
                    'post_type'     => 'course', //post type, I used 'product'
                    'post_status'   => 'publish', // just tried to find all published post
                    'posts_per_page' => -1,  //show all
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'location',  //taxonomy name  here, I used 'product_cat'
                            'field' => 'id',
                            'terms' => array( $post->term_id )
                        )
                    )
                );

                $query = new WP_Query( $args);

                $count += (int)$query->post_count;

            endif;

            return $count;
        }
    ]);

    register_graphql_field('location', 'total_universities', [
        'type' => 'Integer',
        'resolve' => function ($post) {
            $taxonomy_name = 'location';
            $terms = get_term_children( $post->term_id, $taxonomy_name );

            $count = 0;

            if(!empty( $terms ) && !is_wp_error( $terms )):

                foreach ($terms as $term):

                    $args = array(
                        'post_type'     => 'university', //post type, I used 'product'
                        'post_status'   => 'publish', // just tried to find all published post
                        'posts_per_page' => -1,  //show all
                        'tax_query' => array(
                            'relation' => 'AND',
                            array(
                                'taxonomy' => 'location',  //taxonomy name  here, I used 'product_cat'
                                'field' => 'id',
                                'terms' => array( $term )
                            )
                        )
                    );

                    $query = new WP_Query( $args);

                    $count += (int)$query->post_count;

                endforeach;
            else:

                $args = array(
                    'post_type'     => 'university', //post type, I used 'product'
                    'post_status'   => 'publish', // just tried to find all published post
                    'posts_per_page' => -1,  //show all
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'location',  //taxonomy name  here, I used 'product_cat'
                            'field' => 'id',
                            'terms' => array( $post->term_id )
                        )
                    )
                );

                $query = new WP_Query( $args);

                $count += (int)$query->post_count;

            endif;

            return $count;
        }
    ]);

    register_graphql_field('location', 'studyLevel', [
        'type' => ['list_of' => 'DegreeType'],
        'resolve' => function ($post) {

            $taxonomy_name = 'location';
            $terms = get_term_children( $post->term_id, $taxonomy_name );

            $result = array();

            $virtual_value = array();

            if ( !empty( $terms ) && !is_wp_error( $terms ) ){

                foreach ($terms as $term):

                    $args = array(
                        'post_type'     => 'course', //post type, I used 'product'
                        'post_status'   => 'publish', // just tried to find all published post
                        'posts_per_page' => -1,  //show all
                        'tax_query' => array(
                            'relation' => 'AND',
                            array(
                                'taxonomy' => 'location',  //taxonomy name  here, I used 'product_cat'
                                'field' => 'id',
                                'terms' => array( $term )
                            )
                        )
                    );

                    $datas = get_posts($args);

                    foreach($datas as $item):
                        $tax = wp_get_post_terms($item->ID, 'study_level', array( 'fields' => 'all' ))[0];

                        if($tax):
                            if(in_array($tax->term_id, $virtual_value)):

                                foreach ($result as $key => $res):

                                    if($res['name'] === $tax->name):

                                        $result[$key]['count'] += 1;

                                    endif;

                                endforeach;

                            else:

                                $data = [
                                    'id' => $tax->term_id,
                                    'name' => $tax->name,
                                    'count' => 1
                                ];

                                array_push($result, $data);
                                array_push($virtual_value, $tax->term_id);

                            endif;
                        endif;

                    endforeach;

                endforeach;


            }else{

                $args = array(
                    'post_type'     => 'course', //post type, I used 'product'
                    'post_status'   => 'publish', // just tried to find all published post
                    'posts_per_page' => -1,  //show all
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'location',  //taxonomy name  here, I used 'product_cat'
                            'field' => 'id',
                            'terms' => array( $post->term_id )
                        )
                    )
                );

                $query = get_posts($args);

                foreach ($query as $item):

                    $tax = wp_get_post_terms($item->ID, 'study_level', array( 'fields' => 'all' ))[0];

                    if($tax):

                        if(in_array($tax->term_id, $virtual_value)):

                            foreach ($result as $key => $res):

                                if($res['name'] === $tax->name):

                                    $result[$key]['count'] += 1;

                                endif;

                            endforeach;

                        else:

                            $data = [
                                'id' => $tax->term_id,
                                'name' => $tax->name,
                                'count' => 1
                            ];

                            array_push($result, $data);
                            array_push($virtual_value, $tax->term_id);

                        endif;

                    endif;
                endforeach;

            }
            return $result;
        }
    ]);

});


add_action( 'graphql_register_types', 'register_list_degreType_type' );

function register_list_degreType_type() {

    register_graphql_object_type( 'DegreeType', [
        'fields' => [
            'id' => [
                'type' => 'String',
            ],
            'name' => [
                'type' => 'String',
            ],
            'count' => [
                'type' => 'Integer',
            ]
        ],
    ] );

}
