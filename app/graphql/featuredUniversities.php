<?php

use WPGraphQL\Data\Connection\PostObjectConnectionResolver;

add_action( 'graphql_register_types', function() {

    register_graphql_field('universityFeatured', 'type', [
        'type' => 'String',
        'resolve' => function ($post) {
            return get_post_meta($post->ID, 'type', true);
        }
    ]);

    register_graphql_field('RootQueryToUniversityFeaturedConnectionWhereArgs', 'type', [
        'type' => 'String',
    ]);

    register_graphql_field('universityFeatured', 'start_date', [
        'type' => 'String',
        'resolve' => function ($post) {
            return get_post_meta($post->ID, 'start_date', true);
        }
    ]);

    register_graphql_field('RootQueryToUniversityFeaturedConnectionWhereArgs', 'start_date', [
        'type' => 'String',
    ]);

    register_graphql_field('universityFeatured', 'end_date', [
        'type' => 'String',
        'resolve' => function ($post) {
            return get_post_meta($post->ID, 'end_date', true);
        }
    ]);

    register_graphql_field('RootQueryToUniversityFeaturedConnectionWhereArgs', 'end_date', [
        'type' => 'String',
    ]);

    register_graphql_field('RootQueryToUniversityFeaturedConnectionWhereArgs', 'random', [
        'type' => 'Boolean',
    ]);

    register_graphql_field('universityFeatured', 'featured_data', [
        'type' => ['list_of' => 'List'],
        'resolve' => function ($post) {

            $lists = array();
            foreach (get_post_meta($post->ID, 'featured_list', false)[0] as $list):
                $value = [
                    'id' => $list['id'],
                    'image' => wp_get_attachment_image_url($list['image'], 'full')
                ];
                array_push($lists, $value) ;
            endforeach;
            return $lists;
        }
    ]);

    register_graphql_connection(
        [
            'fromType' => 'universityFeatured',
            'toType' => 'university',
            'fromFieldName' => 'featured_list',
            'resolve' => function($source, $args, $context, $info){
                $connexion = new PostObjectConnectionResolver($source, $args, $context, $info, 'university');

                $featured = get_post_meta($source->ID, 'featured_list', false)[0];

                $list_array = array();
                foreach ($featured as $item):
                    array_push($list_array, $item['id']);
                endforeach;
                $connexion->set_query_arg('post__in', $list_array);

                return $connexion->get_connection();
            }
        ]
    );

});

add_filter('graphql_post_object_connection_query_args', function ($query_args, $source, $args, $context, $info) {

    if(in_array('university_featured', $query_args['post_type'])):

        $post_where_type = $args['where']['type'];

        $post_where_start_date = $args['where']['start_date'];
        $post_where_end_date = $args['where']['end_date'];

        $post_where_random = $args['where']['random'];

        if(isset($post_where_type) || isset($post_where_start_date) || isset($post_where_end_date)):

            $query_args['meta_query'] = [
                'relation' => 'AND'
            ];

            if (isset($post_where_type)) {
                $query_post_type = [
                    [
                        'key' => 'type',
                        'value' => $post_where_type,
                        'compare' => '='
                    ]
                ];

                array_push($query_args['meta_query'], $query_post_type);
            }

            if (isset($post_where_start_date)){

                $query_post_start_date = [
                    'key' => 'start_date',
                    'value' => $post_where_start_date,
                    'compare' => '<=',
                    'type' => 'DATE'
                ];

                array_push($query_args['meta_query'], $query_post_start_date);
            }

            if (isset($post_where_end_date)){

                $query_where_end_date = [
                    'key' => 'end_date',
                    'value' => $post_where_end_date,
                    'compare' => '>=',
                    'type' => 'DATE'
                ];

                array_push($query_args['meta_query'], $query_where_end_date);
            }

        endif;

        if(isset($post_where_random)){
            $query_args['orderby'] = 'rand';
        }

    endif;

    return $query_args;
}, 10, 5);


