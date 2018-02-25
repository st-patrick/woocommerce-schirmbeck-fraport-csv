<?php

/******************************************* START meta stuff *******************************************************/

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
    wsfc_display_future_table();


    //no CSV for now getFraportCSV();
}

/******************************************* END meta stuff *******************************************************/



/******************************************* START shared values and setting ground rules **************************/

define("COLUMN_TITLES",
    ["row-nr", "retailer_code", "origin_sku", "sku", "ean",
        "name-de_DE", "name-en_US", "name-zh_CN",
        "title-de_DE", "title-en_US", "title-zh_CN",
        "short_description-de_DE", "short_description-en_US", "short_description-zh_CN",
        "description-de_DE", "description-en_US", "description-zh_CN",
        "variation IDs"]
);
define("RETAILER_CODE", "pfueller");

// set locale for special characters n chinese Characters
setlocale(LC_CTYPE, 'en_US.UTF8');

/******************************************* END shared values and setting ground rules **************************/


function wsfc_display_future_table() {

    $counter = 1;

    echo "<table border='2px'>";

    /////////////////////// START column titles //////////////////////////
    echo "<tr>";
    foreach (COLUMN_TITLES as $column_title) {
        echo "<td>" . $column_title . "</td>";
    }
    echo "</tr>";
    /////////////////////// END column titles //////////////////////////



    /*
     * thanks to Pribhav at stackoverflow
     * https://stackoverflow.com/questions/46951224/woocommerce-product-loop-show-all-product-variation-images
     */
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 10
    );
    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post();

        ////////////////////// START parent row ////////////////////////////////
        $parent_product = wc_get_product( $loop->post->ID );

        /*
         * build SKU prefix from name.
         * First, remove whitespaces.
         * Then, convert special chars. Thanks to Stewie on Stackoverflow: https://stackoverflow.com/questions/9720665/how-to-convert-special-characters-to-normal-characters
         * Lastly, cut first three letters and convert to upper case.
         */
        $sku_prefix = strtoupper(
            substr(
             iconv('utf-8', 'ascii//TRANSLIT', (
                 preg_replace('/\s+/', '',
                     $parent_product->get_name())
                 )
             ), 0, 3)
        );
        $origin_sku = $sku_prefix . $loop->post->ID;

        // assemble variation IDs
        $variation_ids_string = "";
        if ($parent_product->product_type == 'variable') {

            $variations = $parent_product->get_available_variations();

            foreach ($variations as $key => $value) {
                $variation_ids_string .= $value[variation_id] . ", ";
            }
        }

        // assemble all product data into an array for the row
        $current_product_row_data = [
            'row-nr' => $counter,
            'retailer_code' => RETAILER_CODE,
            'origin_sku' => $origin_sku,
            'sku' => RETAILER_CODE . '_' . $origin_sku,
            'ean' => RETAILER_CODE,
            'name-de_DE' => $parent_product->get_name(),
            'title-de_DE' => $parent_product->get_name(),
            'short_description-de_DE' => $parent_product->get_short_description(),
            'description-de_DE' => $parent_product->get_short_description(),
            'variation IDs' => $variation_ids_string
        ];

        // output parent row
        wsfc_output_data_row($current_product_row_data);
        $counter++; // increment row count
        ////////////////////// END parent row ////////////////////////////////




        ////////////////////// START variations rows ////////////////////////////////

        if ($parent_product->product_type == 'variable') {

            $current_product_row_data['variation IDs'] = "";
            $variations = $parent_product->get_available_variations();

            foreach($variations as $key => $value) {
                $origin_sku = $sku_prefix . $value[variation_id];

                // assemble data for update per variation and update the data array
                $current_product_row_data_update = [
                    'row-nr' => $counter,
                    'origin_sku' => $origin_sku,
                    'sku' => RETAILER_CODE . '_' . $origin_sku
                ];
                // assign updates to existing product row data array
                $current_product_row_data = array_merge( $current_product_row_data, $current_product_row_data_update );

                wsfc_output_data_row($current_product_row_data);
                $counter++;
            }

        }

        ////////////////////// END variations rows ////////////////////////////////



    endwhile; wp_reset_query(); // Remember to reset

    echo "</table>";

}

function wsfc_output_data_row($row_data) {
    echo "<tr>";
    foreach (COLUMN_TITLES as $column_title) {
        echo "<td>" . $row_data[$column_title] . "</td>";
    }
    echo "</tr>";
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