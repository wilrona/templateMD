<?php

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

//Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );
Timber::render( array( 'page.twig' ), $context );
