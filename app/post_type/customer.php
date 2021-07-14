<?php

$post_type = tr_post_type('Client', 'Clients');

$post_type->setIcon('user-tie');
$post_type->setArgument('supports', ['title', 'thumbnail'] );
