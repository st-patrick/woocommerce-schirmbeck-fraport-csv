<?php

/*
Plugin Name: Woocommerce Schirmbeck Fraport Csv
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: This Plugin creates a CSV file to be imported by the Frankfurt Airport Marketing Team to represent all items currently in stock.
Version: 1.0
Author: Atelier Schirmbeck
Author URI: https://www.atelierschirmbeck.com/
License: Proprietary, All Rights Reserved
*/


/*
 * add custom button in products submenu in admin area
 * source: https://codex.wordpress.org/Administration_Menus#Using_add_submenu_page
 */

// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');

// action function for above hook
function mt_add_pages() {
    /*
    $parent_slug = 'edit.php?post_type=product';
    $page_title = 'Download Fraport CSV';
    $menu_title = 'Fraport CSV';
    $capability = 'Here you can download the CSV needed for Fraport product import with one click';
    $menu_slug = 'fraport-csv-download';
    $function = 'getFraportCSV';

    add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);*/

    add_menu_page('My Custom Page', 'My Custom Page', 'manage_options', 'my-top-level-slug', 'showPage');
}


function showPage() {
    echo "<h1>this is dummy content</h1>";
    //no CSV for now getFraportCSV();
}


/*********************************** START CSV functionality **********************************************/

function getFraportCSV() {
    array_to_csv_download(array(
        array(1,2,3,4), // this array is going to be the first row
        array(1,2,3,4)), // this array is going to be the second row
        "numbers.csv"
    );
}

// thanks to complex857 and the community https://stackoverflow.com/questions/16251625/how-to-create-and-download-a-csv-file-from-php-script
function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');

    // open the "output" stream
    // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }

    return;
}

/*********************************** END CSV functionality **********************************************/