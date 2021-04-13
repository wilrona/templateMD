<?php

use WPGraphQL\Data\Connection\PostObjectConnectionResolver;
use WPGraphQL\Data\DataSource;

add_action( 'graphql_register_types', function() {

    register_graphql_field('courseFeatured', 'type', [
        'type' => 'String',
        'resolve' => function ($post) {
            return get_post_meta($post->ID, 'type', true);
        }
    ]);

    register_graphql_field('RootQueryToCourseFeaturedConnectionWhereArgs', 'type', [
        'type' => 'String',
    ]);

    register_graphql_field('courseFeatured', 'start_date', [
        'type' => 'String',
        'resolve' => function ($post) {
            return get_post_meta($post->ID, 'start_date', true);
        }
    ]);

    register_graphql_field('RootQueryToCourseFeaturedConnectionWhereArgs', 'start_date', [
        'type' => 'String',
    ]);

    register_graphql_field('RootQueryToCourseFeaturedConnectionWhereArgs', 'random', [
        'type' => 'Boolean',
    ]);

    register_graphql_field('courseFeatured', 'end_date', [
        'type' => 'String',
        'resolve' => function ($post) {
            return get_post_meta($post->ID, 'end_date', true);
        }
    ]);

    register_graphql_field('RootQueryToCourseFeaturedConnectionWhereArgs', 'end_date', [
        'type' => 'String',
    ]);

    register_graphql_field('courseFeatured', 'featured_data', [
        'type' => ['list_of' => 'ListCourse'],
        'resolve' => function ($post) {

            $lists = array();
            foreach (get_post_meta($post->ID, 'featured_list', false)[0] as $list):
                $value = [
                    'id' => $list['id'],
                    'priority' => $list['priority']
                ];
                array_push($lists, $value) ;
            endforeach;
            return $lists;
        }
    ]);

    register_graphql_connection(
        [
            'fromType' => 'courseFeatured',
            'toType' => 'course',
            'fromFieldName' => 'featured_list',
            'resolve' => function($source, $args, $context, $info){

                $connexion = new PostObjectConnectionResolver($source, $args, $context, $info, 'course');

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

//    register_graphql_connection(
//        [
//            'fromType' => 'RootQuery',
//            'toType' => 'courseFeatured',
//            'fromFieldName' => 'coursesFeaturedBy',
//            'resolve' => function($source, $args, $context, $info){
//
//                $meta_query =  array(
//                    array(
//                        'key' => 'type',
//                        'value' => $args['where']['type'],
//                        'compare' => '='
//                    )
//                );
//
//                $connexion = new PostObjectConnectionResolver($source, $args, $context, $info, 'course_featured');
//
//                $connexion->set_query_arg('meta_query', $meta_query);
//
//                return $connexion->get_connection();
//
//            },
//            'connectionArgs' => [
//                'type' => [
//                    'name' => 'type',
//                    'type' => 'String'
//                ],
//                'dateStart' => [
//                    'name' => 'dateStart',
//                    'type' => 'Date'
//                ]
//            ]
//        ]
//    );

});

add_filter('graphql_post_object_connection_query_args', function ($query_args, $source, $args, $context, $info) {

    if(in_array('course_featured', $query_args['post_type'])):

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
