<?php

$currency_index = tr_page('currency', 'view', 'Currency');
$currency_index->setIcon('money');
$currency_index->setTitle('Config currency');
$currency_index->setArgument('position', 40);
$currency_index->setArgument('view_file', tr_view('currency.view')->getView());



