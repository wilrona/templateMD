<?php

use WPGraphQL\Data\Connection\PostObjectConnectionResolver;

add_action( 'graphql_register_types', function() {

    register_graphql_field( 'currency', 'currency', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'currency', true );
        }
    ]);

    register_graphql_field( 'currency', 'symbole', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'symbole', true );
        }
    ]);

    register_graphql_field( 'currency', 'symbole_indication', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'symbole_indication', true );
        }
    ]);

    register_graphql_field( 'currency', 'position', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'position', true );
        }
    ]);

    register_graphql_field( 'currency', 'value_currency', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'value_currency', true );
        }
    ]);

    register_graphql_field( 'currency', 'default_currency', [
        'type' => 'String',
        'resolve' => function( $post ) {
            return get_post_meta( $post->ID, 'default_currency', true );
        }
    ]);

    register_graphql_field( 'currency', 'countries', [
        'type' => ['list_of' => 'String'],
        'resolve' => function( $post ) {

            $countries = array();

            foreach (get_post_meta( $post->ID, 'country', false )[0] as $country):
                array_push($countries, $country['attendant']);
            endforeach;

            return $countries;
        }
    ]);

} );

