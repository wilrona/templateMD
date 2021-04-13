<?php

add_action( 'graphql_register_types', function() {

    register_graphql_field( 'university', 'address', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'address', true );
        }
    ]);

    register_graphql_field( 'university', 'whatsapp', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'whatsapp_number', true );
        }
    ]);

    register_graphql_field( 'university', 'number_student', [
        'type' => 'Integer',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'number_student', true );
        }
    ]);

    register_graphql_field( 'university', 'website_url', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'website_url', true );
        }
    ]);

    register_graphql_field( 'university', 'ranking', [
        'type' => 'Integer',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'ranking', true );
        }
    ]);

    register_graphql_field( 'university', 'undergraduate_fees_min', [
        'type' => 'Tuition_fees',
        'resolve' => function( $post ) {

            $value = [
                'tuition_fees' => get_post_meta( $post->ID, 'undergraduate_fees_min_amount', true ),
                'currency' => get_post_meta( $post->ID, 'undergraduate_fees_min_currency', true )
            ];

            return $value;
        }
    ]);

    register_graphql_field( 'university', 'undergraduate_fees_max', [
        'type' => 'Tuition_fees',
        'resolve' => function( $post ) {
            $value = [
                'tuition_fees' => get_post_meta( $post->ID, 'undergraduate_fees_max_amount', true ),
                'currency' => get_post_meta( $post->ID, 'undergraduate_fees_max_currency', true )
            ];
            return $value;
        }
    ]);

    register_graphql_field( 'university', 'postgraduate_fees_min', [
        'type' => 'Tuition_fees',
        'resolve' => function( $post ) {

            $value = [
                'tuition_fees' => get_post_meta( $post->ID, 'postgraduate_fees_min_amount', true ),
                'currency' => get_post_meta( $post->ID, 'postgraduate_fees_min_currency', true )
            ];

            return $value;
        }
    ]);

    register_graphql_field( 'university', 'postgraduate_fees_max', [
        'type' => 'Tuition_fees',
        'resolve' => function( $post ) {

            $value = [
                'tuition_fees' => get_post_meta( $post->ID, 'postgraduate_fees_max_amount', true ),
                'currency' => get_post_meta( $post->ID, 'postgraduate_fees_max_currency', true )
            ];

            return $value;
        }
    ]);

    register_graphql_field( 'university', 'logo', [
        'type' => 'String',
        'resolve' => function( $post ) {
            $image = wp_get_attachment_image_url(get_post_meta($post->ID, 'logo', true), 'full');
            return $image;
        }
    ]);

    register_graphql_field( 'university', 'cover', [
        'type' => 'String',
        'resolve' => function( $post ) {
            $image = wp_get_attachment_image_url(get_post_meta($post->ID, 'cover', true), 'full');
            return $image;
        }
    ]);

    register_graphql_field( 'university', 'video_link', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'video_link', true );
        }
    ]);

    register_graphql_field( 'university', 'keyInfo_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'key_info', true );
        }
    ]);

    register_graphql_field( 'university', 'admission_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'admission', true );
        }
    ]);

    register_graphql_field( 'university', 'howApply_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'how_apply', true );
        }
    ]);

    register_graphql_field( 'university', 'foreignStudent_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'foreign_student', true );
        }
    ]);

    register_graphql_field( 'university', 'scholarship_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'scholarship_detail', true );
        }
    ]);

    register_graphql_field( 'university', 'leadership', [
        'type' => 'contentData',
        'resolve' => function( $post ) {

            $content = [
                'title' => get_post_meta( $post->ID, 'title_leader', true ),
                'content' => get_post_meta( $post->ID, 'name_leader', true )
            ];
            return $content;
        }
    ]);

    register_graphql_field( 'university', 'location_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'location', true );
        }
    ]);


    register_graphql_field( 'university', 'gallery', [
        'type' => ['list_of' => 'String'],
        'resolve' => function( $post ) {

            $images = array();

            foreach (get_post_meta( $post->ID, 'gallery', false )[0] as $image):
                array_push($images, wp_get_attachment_image_url($image, 'full'));
            endforeach;

            return $images;
        }
    ]);

    register_graphql_field( 'university', 'whyStudy', [
        'type' => ['list_of' => 'contentData'],
        'resolve' => function( $post ) {

            $datas = get_post_meta( $post->ID, 'whyStudy', false )[0];
            $result = array();

            foreach ($datas as $data):

                $content = [
                    'title' => $data['title'],
                    'content' => $data['content']
                ];

                array_push($result, $content);

            endforeach;

            return $result;
        }
    ]);


    register_graphql_field( 'university', 'contacts', [
        'type' => [ 'list_of' => 'Contact' ],
        'resolve' => function( $post ) {

            $datas = get_post_meta( $post->ID, 'contacts', false )[0];

            $values = array();

            foreach($datas as $data):

                $value_email = array();

                foreach ($data['emails'] as $email):
                    array_push($value_email,  $email['email']);
                endforeach;

                $value_content = [
                    'name' => $data['name'],
                    'post' => $data['post'],
                    'code_country' => $data['country_code'],
                    'phone' => $data['phone'],
                    'emails' => $value_email
                ];

                array_push($values, $value_content);

            endforeach;

            return $values;
        }
    ]);

    register_graphql_field( 'university', 'officials', [
        'type' => [ 'list_of' => 'Official' ],
        'resolve' => function( $post ) {

            $datas = get_post_meta( $post->ID, 'officials', false )[0];

            $values = array();

            foreach($datas as $data):

                $value_content = [
                    'image' => wp_get_attachment_image_url($data['image'], 'full'),
                    'post' => $data['post'],
                    'name' => $data['name'],
                ];

                array_push($values, $value_content);

            endforeach;

            return $values;
        }
    ]);

    register_graphql_field( 'university', 'faculties', [
        'type' => [ 'list_of' => 'Faculty' ],
        'resolve' => function( $post ) {

            $datas = get_post_meta( $post->ID, 'faculties', false )[0];

            $values = array();

            foreach($datas as $data):

                $faculties = array();

                foreach($data['faculties'] as $subData):

                    $subFaculties = array();

                    foreach ($subData['faculties'] as $name):

                        $current_sub_sub = array(
                            'name' => $name['name']
                        );

                        array_push($subFaculties, $current_sub_sub);

                    endforeach;

                    $current_sub = array(
                        'name' => $subData['name'],
                        'subFaculty' => $subFaculties
                    );

                    array_push($faculties, $current_sub);
                endforeach;

                $value_content = [
                    'name' => $data['name'],
                    'subFaculty' => $faculties
                ];

                array_push($values, $value_content);

            endforeach;

            return $values;
        }
    ]);


    register_graphql_field( 'university', 'course_count', [
        'type' => 'Integer',
        'resolve' => function( $post ) {
            //return $count;
            $args = array(
                'post_type'     => 'course', //post type, I used 'product'
                'post_status'   => 'publish', // just tried to find all published post
                'posts_per_page' => -1,  //show all
                'meta_query' => array(
                    array(
                        'key' => 'university',
                        'compare' => '=',
                        'value' => $post->ID
                     )
                )
            );

            $query = new WP_Query( $args);

            return (int)$query->post_count;
        }
    ]);

} );


add_action( 'graphql_register_types', 'register_whyStudy_type' );

function register_whyStudy_type() {

    register_graphql_object_type( 'contentData', [
        'fields' => [
            'title' => [
                'type' => 'String',
            ],
            'content' => [
                'type' => 'String'
            ]
        ],
    ] );

}


add_action( 'graphql_register_types', 'register_official_type' );

function register_official_type() {

    register_graphql_object_type( 'Official', [
        'fields' => [
            'image' => [
                'type' => 'String',
            ],
            'name' => [
                'type' => 'String'
            ],
            'post' => [
                'type' => 'String'
            ]
        ],
    ] );

}


add_action( 'graphql_register_types', 'register_faculty_type' );

function register_faculty_type() {

    register_graphql_object_type( 'Faculty', [
        'fields' => [
            'name' => [
                'type' => 'String'
            ],
            'subFaculty' => [
                'type' => ['list_of' => 'subFaculty']
            ]
        ]
    ]);

    register_graphql_object_type( 'subFaculty', [
        'fields' => [
            'name' => [
                'type' => 'String'
            ],
            'subFaculty' => [
                'type' => ['list_of' => 'subSubFaculty']
            ]
        ]
    ]);

    register_graphql_object_type( 'subSubFaculty', [
        'fields' => [
            'name' => [
                'type' => 'String'
            ]
        ]
    ]);



}

