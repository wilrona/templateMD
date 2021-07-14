<?php
/* Template Name: Home page */

use Timber\Timber;

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

Timber::render(  'page-home.twig' , $context );
