<?php

use WPGraphQL\Data\Connection\PostObjectConnectionResolver;

add_action( 'graphql_register_types', function() {

    register_graphql_field( 'course', 'campus', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'campus', true );
        }
    ]);

    register_graphql_field( 'course', 'academic_unit', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'academic_unit', true );
        }
    ]);

    register_graphql_field( 'course', 'final_award', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'final_award', true );
        }
    ]);

//    register_graphql_field( 'course', 'program_detail', [
//        'type' => 'String',
//        'resolve' => function( $post ) {
//            return get_post_meta( $post->ID, 'program', true );
//        }
//    ]);

    register_graphql_field( 'course', 'curriculum_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'curriculum', true );
        }
    ]);

    register_graphql_field( 'course', 'admission_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'admission', true );
        }
    ]);

    register_graphql_field( 'course', 'application_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'application', true );
        }
    ]);

    register_graphql_field( 'course', 'career_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'career', true );
        }
    ]);

    register_graphql_field( 'course', 'tuition_fees_detail', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'tuition_fees_detail', true );
        }
    ]);

    register_graphql_field( 'course', 'language', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'language', true );
        }
    ]);


    register_graphql_field( 'course', 'duration_time', [
        'type' => 'Duration_time',
        'resolve' => function( $post ) {
            return [
                'time_number' => get_post_meta( $post->ID, 'duration_number', true ),
                'time_month' => get_post_meta( $post->ID, 'duration_month', true )
            ];
        }
    ]);

    register_graphql_field( 'course', 'attendant', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'attendant', true );
        }
    ]);

    register_graphql_field( 'course', 'delivery_mode', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'delivery_mode', true );
        }
    ]);

    register_graphql_field( 'course', 'option', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'specialisation', true );
        }
    ]);

    register_graphql_field( 'course', 'student_quota', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'student_quota', true );
        }
    ]);

    register_graphql_field( 'course', 'course_type', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'course_type', true );
        }
    ]);

    register_graphql_field( 'course', 'all_open', [
        'type' => 'Boolean',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'all_open', true );
        }
    ]);

    /**
     * data for local student
     */

    register_graphql_field( 'course', 'national_fees', [
        'type' => 'Tuition_fees',
        'resolve' => function( $post ) {
            return [
                'tuition_fees' => get_post_meta( $post->ID, 'tuition_fees_national', true ),
                'currency' => get_post_meta( $post->ID, 'currency_national', true )
            ];
        }
    ]);

    register_graphql_field( 'course', 'national_application_fees', [
        'type' => 'Tuition_fees',
        'resolve' => function( $post ) {
            return [
                'tuition_fees' => get_post_meta( $post->ID, 'tuition_fees_application_national', true ),
                'currency' => get_post_meta( $post->ID, 'currency_application_national', true )
            ];
        }
    ]);

    register_graphql_field( 'course', 'fees_apply_to_national', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'fees_apply_to_national', true );
        }
    ]);

    /**
     * data for foreign student
     */

    register_graphql_field( 'course', 'international_fees', [
        'type' => 'Tuition_fees',
        'resolve' => function( $post ) {
            return [
                'tuition_fees' => get_post_meta( $post->ID, 'tuition_fees_international', true ),
                'currency' => get_post_meta( $post->ID, 'currency_international', true )
            ];
        }
    ]);

    register_graphql_field( 'course', 'international_application_fees', [
        'type' => 'Tuition_fees',
        'resolve' => function( $post ) {
            return [
                'tuition_fees' => get_post_meta( $post->ID, 'tuition_fees_application_international', true ),
                'currency' => get_post_meta( $post->ID, 'currency_application_international', true )
            ];
        }
    ]);

    register_graphql_field( 'course', 'fees_apply_to_international', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'fees_apply_to_international', true );
        }
    ]);

    register_graphql_field( 'course', 'fees_duration', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'fees_duration', true );
        }
    ]);

    register_graphql_field( 'course', 'sessions', [
        'type' => [ 'list_of' => 'Session' ],
        'resolve' => function( $post ) {

            $durations = get_post_meta( $post->ID, 'sessions', false )[0];

            $values = array();

            foreach($durations as $duration):

                $value_content = [
                    'name' => $duration['name'],
                    'classes_begin' => [
                        'day' => $duration['day_begin'],
                        'month' => $duration['month_begin'],
                        'year' => $duration['year_begin']

                    ],
                    'start_application' => [
                        'day' => $duration['day_start_application'],
                        'month' => $duration['month_start_application'],
                        'year' => $duration['year_start_application']
                    ],
                    'deadline_application' => [
                        'day' => $duration['day_deadline_application'],
                        'month' => $duration['month_deadline_application'],
                        'year' => $duration['year_deadline_application']
                    ]
                ];

                array_push($values, $value_content);

            endforeach;

            return $values;
        }
    ]);


    register_graphql_field( 'course', 'contacts', [
        'type' => [ 'list_of' => 'Contact' ],
        'resolve' => function( $post ) {

            $datas = get_post_meta( $post->ID, 'contacts', false )[0];

            $values = array();

            foreach($datas as $data):

                $value_email = array();

                foreach ($data['emails'] as $email):
                    array_push($value_email, $email['email']);
                endforeach;

                $value_content = [
                    'name' => $data['name'],
                    'post' => $data['post'],
                    'code_country' => $data['code_country'],
                    'phone' => $data['phone'],
                    'emails' => $value_email
                ];

                array_push($values, $value_content);

            endforeach;

            return $values;
        }
    ]);


    register_graphql_connection(
        [
            'fromType' => 'course',
            'toType' => 'university',
            'fromFieldName' => 'university',
            'resolve' => function($course, $args, $context, $info){
                $connexion = new PostObjectConnectionResolver($course, $args, $context, $info, 'university');
                $featured = get_post_meta($course->ID, 'university', true);
                $list_array = array($featured);
                $connexion->set_query_arg('post__in', $list_array);
                return $connexion->get_connection();
            }
        ]
    );

} );

add_action( 'graphql_register_types', 'register_duration_type' );

function register_duration_type() {
    register_graphql_object_type( 'Duration_time', [
        'fields' => [
                'time_number' => [
                    'type' => 'Integer'
                ],
                'time_month' => [
                    'type' => 'String'
                ]
        ],
    ] );
}


add_action( 'graphql_register_types', 'register_session_type' );

function register_session_type() {

    register_graphql_object_type( 'Session', [
        'fields' => [
            'name' => [
                'type' => 'String',
            ],
            'classes_begin' => [
                'type' => 'Date'
            ],
            'start_application' => [
                'type' => 'Date'
            ],
            'deadline_application' => [
                'type' => 'Date',
            ]
        ],
    ] );

    register_graphql_object_type( 'Date', [
        'fields' => [
            'day' => [
                'type' => 'Integer'
            ],
            'month' => [
                'type' => 'String'
            ],
            'year' => [
                'type' => 'String'
            ]
        ],
    ] );
}
