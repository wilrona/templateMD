<?php

$post_type = tr_post_type('Service', 'Services');

$post_type->setIcon('node-tree');
$post_type->setArgument('supports', ['title', 'editor', 'thumbnail'] );
