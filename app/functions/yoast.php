<?php

function get_crumb_array(){

    /*
    echo '<xmp>';
    var_export(yoast_breadcrumb('', '', false));
    echo '</xmp>';
    */

    $crumb = array();

    //Get all preceding links before the current page
    $dom = new DOMDocument();
    $dom->loadHTML(yoast_breadcrumb('', '', false));
    $items = $dom->getElementsByTagName('a');

    foreach ($items as $tag)
        $crumb[] =  array('text' => $tag->nodeValue, 'href' => $tag->getAttribute('href'), 'current' => false);

    //Get the current page text and href
    $items = new DOMXpath($dom);
    $dom = $items->query('//*[contains(@class, "breadcrumb_last")]');
    $crumb[] = array('text' => $dom->item(0)->nodeValue, 'href' => trailingslashit(home_url($wp->request)), 'current' => true);
    return $crumb;
}
