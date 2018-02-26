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

    add_menu_page('Fraport CSV', 'Fraport CSV', 'manage_options', 'my-top-level-slug', 'showPage');
}


function showPage() {
    wsfc_display_future_table();


    //no CSV for now getFraportCSV();
}

/******************************************* END meta stuff *******************************************************/



/******************************************* START shared values and setting ground rules **************************/

define("COLUMN_TITLES",
    ["row-nr", "thumbnail-helper", "helper",
        "retailer_code", "origin_sku", "sku", "ean",
        "name-de_DE", "name-en_US", "name-zh_CN",
        "title-de_DE", "title-en_US", "title-zh_CN",
        "short_description-de_DE", "short_description-en_US", "short_description-zh_CN",
        "description-de_DE", "description-en_US", "description-zh_CN",
        "family",
        "magento_tax_class_id", "magento_status", "magento_visibility", "magento_type", "magento_variation_attributes",
        "CONF-products",
        "categories",
        "price-EUR", "special_price-EUR",

        // image URLs and title as description repeated a bunch of times
        "thumbnail_image-de_DE", "thumbnail_image-en_US", "thumbnail_image-zh_CN",
        // NOT IN PREVIEW "thumbnail_image_label-de_DE", "thumbnail_image_label-en_US", "thumbnail_image_label-zh_CN",
        // NOT IN PREVIEW "small_image-de_DE", "small_image-en_US", "small_image-zh_CN",
        // NOT IN PREVIEW "small_image_label-de_DE", "small_image_label-en_US", "small_image_label-zh_CN",
        // NOT IN PREVIEW "large_image-de_DE", "large_image-en_US", "large_image-zh_CN",

        // a bunch of empty columns we didn't use in the last CSV
        // NOT IN PREVIEW "image1-de_DE", "image1-en_US", "image1-zh_CN", "image2-de_DE", "image2-en_US", "image2-zh_CN", "info_label-de_DE", "info_label-en_US", "info_label-zh_CN", "netweight", "netweight-unit", "dimensionwidth", "dimensionwidth-unit", "dimensionheight", "dimensionheight-unit", "dimensionlength", "dimensionlength-unit",

        // title, again
        // NOT IN PREVIEW "meta_title-de_DE", "meta_title-en_US", "meta_title-zh_CN",

        // more empty columns we haven't ever sed yet
        // NOT IN PREVIEW "meta_keyword-de_DE", "meta_keyword-en_US", "meta_keyword-zh_CN", "manufacturer_seriesname-zh_CN", "manufacturersdata-zh_CN", "sealofquality-zh_CN", "specialcharacteristics-zh_CN", "warnings-zh_CN", "storageconditions-zh_CN", "winequality-zh_CN", "countryoforigin-zh_CN", "allergens-zh_CN", "growingarea-zh_CN", "tastingnotes-zh_CN", "nutritionalvalue-zh_CN", "smoke_length-zh_CN", "smoke_type-zh_CN", "colourdescription-zh_CN", "clockwork-zh_CN", "washing_instructions-zh_CN", "effect-zh_CN", "fragrancenotes-zh_CN", "material-zh_CN",

        "brand_code",

        // even more unused, empty columns
        // NOT IN PREVIEW "manufacturer_seriesname-de_DE", "manufacturer_seriesname-en_US", "agelimit", "producernumber", "manufacturersdata-de_DE", "manufacturersdata-en_US", "sealofquality-de_DE", "sealofquality-en_US", "specialcharacteristics-de_DE", "specialcharacteristics-en_US", "warnings-de_DE", "warnings-en_US", "gmo_free", "withcolourant", "storageconditions-de_DE", "storageconditions-en_US", "winequality-de_DE", "winequality-en_US", "vintage", "countryoforigin-de_DE", "countryoforigin-en_US", "allergens-de_DE", "allergens-en_US", "alcoholbyvolume", "growingarea-de_DE", "growingarea-en_US", "tastingnotes-de_DE", "tastingnotes-en_US", "salt_prefix", "salt_value", "salt_unit", "carbonate_prefix", "carbonate_value", "carbonate_unit", "saturated_fatty_acids_prefix", "saturated_fatty_acids_value", "saturated_fatty_acids_unit", "fat_prefix", "fat_value", "fat_unit", "energy_kcal_prefix", "energy_kcal_value", "energy_kjoule_prefix", "energy_kjoule_value", "protein_prefix", "protein_value", "protein_unit", "fiber_prefix", "fiber_value", "fiber_unit", "sugar_prefix", "sugar_value", "sugar_unit", "big7_base_qty", "big7_base_unit", "nutritionalvalue-de_DE", "nutritionalvalue-en_US", "is_perishable", "package_size", "product_needs_cooling", "nicotine", "tar_content", "cigar_length", "smoke_length-de_DE", "smoke_length-en_US", "smoke_type-de_DE", "smoke_type-en_US",

        "colour", "colourdescription-de_DE",

        // yet more unused, empty columns
        // NOT IN PREVIW "colourdescription-en_US", "clockwork-de_DE", "clockwork-en_US", "diopters", "storage_capacity", "washing_instructions-de_DE", "washing_instructions-en_US"

        "sex",
    ]
);
define("RETAILER_CODE", "pfueller");
define("MAGENTO_TAX_CLASS_ID", 1);
define("MAGENTO_STATUS", 1);
define("MAGENTO_VISIBLE", 4);
define("MAGENTO_INVISIBLE", 1);
define("CATEGORIES", "pfueller_produkte");

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


    // Set constant values for every row
    $current_product_row_data = [
        'retailer_code' => RETAILER_CODE,
        'ean' => RETAILER_CODE,
        'magento_tax_class_id' => MAGENTO_TAX_CLASS_ID,
        'magento_status' => MAGENTO_STATUS,
        'categories' => CATEGORIES,
    ];


    /*
     * thanks to Pribhav at stackoverflow
     * https://stackoverflow.com/questions/46951224/woocommerce-product-loop-show-all-product-variation-images
     */
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post();

        ////////////////////// START parent row ////////////////////////////////
        $parent_product = wc_get_product( $loop->post->ID );

        // some products are not available for purchase bc no price has been set or similar reasons. Skip those.
        if ($parent_product->is_purchasable() != 1) continue;

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

        // assemble variation IDs, set product configurable or simple
        $conf_products = "";
        $magento_type = "simple";
        if ($parent_product->product_type == 'variable') {

            $magento_type = "configurable";

            $variations = $parent_product->get_available_variations();

            foreach ($variations as $key => $value) {
                $conf_products .= "pfueller_" . $sku_prefix . $value[variation_id] . ", "; // concatenate String that will indicate which are the variations of this parent product
            }
        }

        // determine product family and magento variation attributes by product attributes
        $product_attributes = $parent_product->get_attributes();
        $family = "other"; //default case
        $magento_variation_attributes = "";
        if ($product_attributes['pa_farbe'] != null) $magento_variation_attributes = (($magento_type == "configurable") ? "colour" : ""); // only set attributes when configurable
        // no else if here because most products have color and a size defined but colour should only be a fallback attribute, e.g. for toys
        if ($product_attributes['pa_schuhgroesse'] != null) {
            $family = "shoes";
            $magento_variation_attributes = (($magento_type == "configurable") ? "shoe_size" : ""); // only set attributes when configurable
        } elseif ($product_attributes['pa_groesse'] != null) { // TODO andere Groessen hinzufügen (Damengrößen BH-Größen, ...). The attributes of variations are the deciding factor
            $family = "clothes";
            $magento_variation_attributes = (($magento_type == "configurable") ? "clothing_size" : ""); // only set attributes when configurable
        }

        // assemble all product data into an array for the row
        $current_product_row_data_update = [
            'row-nr' => $counter,
            'thumbnail-helper' => $parent_product->get_image( array(150,150) ),
            'helper' => '<pre>' . print_r($parent_product->get_category_ids(), true) . '</pre>',
            'origin_sku' => $origin_sku,
            'sku' => RETAILER_CODE . '_' . $origin_sku,
            'name-de_DE' => $parent_product->get_name(),
            'title-de_DE' => $parent_product->get_name(),
            'short_description-de_DE' => $parent_product->get_short_description(),
            'description-de_DE' => $parent_product->get_short_description(),
            'family' => $family, // DEBUG "<pre>" . print_r($parent_product->get_attributes(), true) . "</pre>",
            'magento_visibility' => MAGENTO_VISIBLE,
            'magento_type' => $magento_type,
            'magento_variation_attributes' => $magento_variation_attributes,
            'CONF-products' => $conf_products,
            'price-EUR' => $parent_product->get_price(),
            'thumbnail_image-de_DE' => 'images/' . $origin_sku . '.jpg',
            'thumbnail_image_label-de_DE' => $parent_product->get_name(), // TODO fill up all syonymous columns beforehand like image URLS, titles, etc..
            'meta_title-de_DE' => $parent_product->get_name(),
            'brand_code' => get_the_terms($loop->post->ID, 'product_brand')[0]->slug,
        ];
        // assign updates to existing product row data array
        $current_product_row_data = array_merge( $current_product_row_data, $current_product_row_data_update );

        // output parent row
        wsfc_output_data_row($current_product_row_data);
        $counter++; // increment row count
        ////////////////////// END parent row ////////////////////////////////




        ////////////////////// START variations rows ////////////////////////////////

        if ($parent_product->product_type == 'variable') {

            $current_product_row_data['CONF-products'] = "";
            $variations = $parent_product->get_available_variations();

            foreach($variations as $key => $value) {
                $origin_sku = $sku_prefix . $value[variation_id];

                // assemble data for update per variation and update the data array
                $current_product_row_data_update = [
                    'row-nr' => $counter,
                    'origin_sku' => $origin_sku,
                    'sku' => RETAILER_CODE . '_' . $origin_sku,
                    'magento_visibility' => MAGENTO_INVISIBLE, // TODO update constant variation values before entering loop
                    'magento_type' => 'simple',
                    'magento_variation_attributes' => '',
                    'price-EUR' => $value[display_price],
                    'colour' => $value[attributes][attribute_pa_farbe],
                ];
                // assign updates to existing product row data array
                $current_product_row_data = array_merge( $current_product_row_data, $current_product_row_data_update );

                wsfc_output_data_row($current_product_row_data);
                $counter++;
            }

        }

        ////////////////////// END variations rows ////////////////////////////////



    endwhile; wp_reset_query(); // Remember to reset



    /////////////////////// START the stop sign row //////////////////////////
    echo "<tr>";
    foreach (COLUMN_TITLES as $column_title) {
        echo "<td>STOP</td>";
    }
    echo "</tr>";
    /////////////////////// END  the stop sign row  //////////////////////////


    echo "</table>";

}

function wsfc_output_data_row($row_data) {
    echo "<tr>";
    foreach (COLUMN_TITLES as $column_title) {
        echo "<td>" . $row_data[$column_title] . "</td>";
    }
    echo "</tr>";
}

/*
 * get category slug, mainly for brand codes, from category ID
 * thanks to Ken Rosaka
 * https://wordpress.org/support/topic/i-need-to-get-the-category-slug-from-the-category-id/
 */
function get_cat_slug($cat_id) {
    $cat_id = (int) $cat_id;
    $category = &get_category($cat_id);
    return $category->slug;
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