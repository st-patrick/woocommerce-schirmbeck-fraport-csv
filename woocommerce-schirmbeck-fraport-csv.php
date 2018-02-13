<?php

/*
Plugin Name: Woocommerce Schirmbeck Fraport Csv
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Patrick Reinbold
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

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
}