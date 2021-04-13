<?php


add_action( 'graphql_register_types', 'register_contact_type' );

function register_contact_type() {

    register_graphql_object_type( 'Contact', [
        'fields' => [
            'name' => [
                'type' => 'String',
            ],
            'post' => [
                'type' => 'String',
            ],
            'code_country' => [
                'type' => 'String',
            ],
            'phone' => [
                'type' => 'String',
            ],
            'emails' => [
                'type' => ['list_of' => 'String']
            ]
        ],
    ] );



}

add_action( 'graphql_register_types', 'register_list_type' );

function register_list_type() {

    register_graphql_object_type( 'List', [
        'fields' => [
            'id' => [
                'type' => 'String',
            ],
            'image' => [
                'type' => 'String'
            ]
        ],
    ] );

    register_graphql_object_type( 'ListCourse', [
        'fields' => [
            'id' => [
                'type' => 'String',
            ],
            'priority' => [
                'type' => 'Integer'
            ]
        ],
    ] );



    register_graphql_object_type( 'Tuition_fees', [
        'fields' => [
            'tuition_fees' => [
                'type' => 'Integer'
            ],
            'currency' => [
                'type' => 'String'
            ]
        ],
    ] );

}
