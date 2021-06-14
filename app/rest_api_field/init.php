<?php

//include ('course.php');
include ("posts.php");
include ("user.php");
include ("edition.php");

if( ! function_exists( 'post_meta_request_params' ) ) :
    function post_meta_request_params( $args, $request ){
        $args += array(
            'meta_key'   => $request['meta_key'],
            'meta_value' => $request['meta_value'],
            'meta_query' => $request['meta_query'],
        );
        return $args;
    }
    add_filter( 'rest_post_query', 'post_meta_request_params', 99, 2 );
    // add_filter( 'rest_page_query', 'post_meta_request_params', 99, 2 ); // Add support for `page`
    // add_filter( 'rest_my-custom-post_query', 'post_meta_request_params', 99, 2 ); // Add support for `my-custom-post`
endif;