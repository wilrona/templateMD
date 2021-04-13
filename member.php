<?php
/* Template Name: Pade de membre */

use Timber\Timber;

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;


Timber::render(  'page-member.twig' , $context );
