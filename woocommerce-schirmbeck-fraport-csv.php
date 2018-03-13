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


    //no CSV Download button for now getFraportCSV();
}

/******************************************* END meta stuff *******************************************************/



/******************************************* START shared values and setting ground rules **************************/

define("CSV_COLUMN_TITLES",
    [
        "retailer_code", "origin_sku", "sku", "ean",

        "name-de_DE", "name-en_US", "name-zh_CN",
        "title-de_DE", "title-en_US", "title-zh_CN",

        "short_description-de_DE", "short_description-en_US", "short_description-zh_CN",
        "description-de_DE", "description-en_US", "description-zh_CN", "info_label-zh_CN", "info_text-zh_CN",

        "family",

        "magento_tax_class_id", "magento_status",
        "magento_visibility", "magento_type", "magento_variation_attributes",

        "CONF-products",

        "categories",

        "price-EUR", "special_price-EUR",

        // image URLs and title as description repeated a bunch of times
        "thumbnail_image-de_DE", "thumbnail_image-en_US", "thumbnail_image-zh_CN",
        "thumbnail_image_label-de_DE", "thumbnail_image_label-en_US", "thumbnail_image_label-zh_CN",
        "small_image-de_DE", "small_image-en_US", "small_image-zh_CN",
        "small_image_label-de_DE", "small_image_label-en_US", "small_image_label-zh_CN",
        "large_image-de_DE", "large_image-en_US", "large_image-zh_CN",

        // a bunch of empty columns we didn't use in the last CSV
        "image1-de_DE", "image1-en_US", "image1-zh_CN", "image2-de_DE", "image2-en_US", "image2-zh_CN", "info_label-de_DE", "info_label-en_US", "info_label-zh_CN", "netweight", "netweight-unit", "dimensionwidth", "dimensionwidth-unit", "dimensionheight", "dimensionheight-unit", "dimensionlength", "dimensionlength-unit",

        // title, again
        "meta_title-de_DE", "meta_title-en_US", "meta_title-zh_CN",

        // more empty columns we haven't ever sed yet
        "meta_keyword-de_DE", "meta_keyword-en_US", "meta_keyword-zh_CN", "manufacturer_seriesname-zh_CN", "manufacturersdata-zh_CN", "sealofquality-zh_CN", "specialcharacteristics-zh_CN", "warnings-zh_CN", "storageconditions-zh_CN", "winequality-zh_CN", "countryoforigin-zh_CN", "allergens-zh_CN", "growingarea-zh_CN", "tastingnotes-zh_CN", "nutritionalvalue-zh_CN", "smoke_length-zh_CN", "smoke_type-zh_CN", "colourdescription-zh_CN", "clockwork-zh_CN", "washing_instructions-zh_CN", "effect-zh_CN", "fragrancenotes-zh_CN", "material-zh_CN",

        "brand_code",

        // even more unused, empty columns
        "manufacturer_seriesname-de_DE", "manufacturer_seriesname-en_US", "agelimit", "producernumber", "manufacturersdata-de_DE", "manufacturersdata-en_US", "sealofquality-de_DE", "sealofquality-en_US", "specialcharacteristics-de_DE", "specialcharacteristics-en_US", "warnings-de_DE", "warnings-en_US", "gmo_free", "withcolourant", "storageconditions-de_DE", "storageconditions-en_US", "winequality-de_DE", "winequality-en_US", "vintage", "countryoforigin-de_DE", "countryoforigin-en_US", "allergens-de_DE", "allergens-en_US", "alcoholbyvolume", "growingarea-de_DE", "growingarea-en_US", "tastingnotes-de_DE", "tastingnotes-en_US", "salt_prefix", "salt_value", "salt_unit", "carbonate_prefix", "carbonate_value", "carbonate_unit", "saturated_fatty_acids_prefix", "saturated_fatty_acids_value", "saturated_fatty_acids_unit", "fat_prefix", "fat_value", "fat_unit", "energy_kcal_prefix", "energy_kcal_value", "energy_kjoule_prefix", "energy_kjoule_value", "protein_prefix", "protein_value", "protein_unit", "fiber_prefix", "fiber_value", "fiber_unit", "sugar_prefix", "sugar_value", "sugar_unit", "big7_base_qty", "big7_base_unit", "nutritionalvalue-de_DE", "nutritionalvalue-en_US", "is_perishable", "package_size", "product_needs_cooling", "nicotine", "tar_content", "cigar_length", "smoke_length-de_DE", "smoke_length-en_US", "smoke_type-de_DE", "smoke_type-en_US",

        "colour", "colourdescription-de_DE",

        // yet more unused, empty columns
        "colourdescription-en_US", "clockwork-de_DE", "clockwork-en_US", "diopters", "storage_capacity", "washing_instructions-de_DE", "washing_instructions-en_US",

        "sex",
        "clothing_size",

        // yet more unused, empty columns
        "manufacturer_colour", "glove_size",

        "shoe_size",

        // yet more unused, empty columns
        "is_cabin_baggage", "bag_size", "effect-de_DE", "effect-en_US", "package_size", "fragrancenotes-de_DE", "fragrancenotes-en_US", "storage_capacity", "target_age", "material-de_DE", "material-en_US",

    ]
);
define("PREVIEW_COLUMN_TITLES",
    ["row-nr", "thumbnail-helper", /*"helper", "helper2",*/

        // NOT IN PREVIEW "retailer_code",

        "origin_sku",

        // NOT IN PREVIEW "sku", "ean",

        "name-de_DE", "name-en_US", // NOT IN PREVIEW "name-zh_CN",

        // NOT IN PREVIEW "title-de_DE", "title-en_US", "title-zh_CN",

        "short_description-de_DE", // NOT IN PREVIEW "short_description-en_US", "short_description-zh_CN",

        // NOT IN PREVIEW "description-de_DE", "description-en_US", "description-zh_CN", "info_label-zh_CN", "info_text-zh_CN",


        "family",

        // NOT IN PREVIEW "magento_tax_class_id", "magento_status",
        "magento_visibility", "magento_type", "magento_variation_attributes",

        "CONF-products",

        // NOT IN PREVIEW "categories",

        "price-EUR", // NOT IN PREVIEW "special_price-EUR",

        // image URLs and title as description repeated a bunch of times
        "thumbnail_image-de_DE", // NOT IN PREVIEW "thumbnail_image-en_US", "thumbnail_image-zh_CN",
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

        "colour", // NOT IN PREVIEW "colourdescription-de_DE",

        // yet more unused, empty columns
        // NOT IN PREVIW "colourdescription-en_US", "clockwork-de_DE", "clockwork-en_US", "diopters", "storage_capacity", "washing_instructions-de_DE", "washing_instructions-en_US"

        "sex",
        "clothing_size",

        // yet more unused, empty columns
        // NOT IN PREVIW "manufacturer_colour", "glove_size",

        "shoe_size",

        // yet more unused, empty columns
        // NOT IN PREVIW "is_cabin_baggage", "bag_size", "effect-de_DE", "effect-en_US", "package_size", "fragrancenotes-de_DE", "fragrancenotes-en_US", "storage_capacity", "target_age", "material-de_DE", "material-en_US",

    ]
);
define("COLOUR_DICTIONARY", [
    "schwarz" => "black",
    "blau" => "blue",
    "grau" => "grey",
    "petrol" => "petrol",
    "rosa" => "pink",
    "weiss" => "white",
    "hellrosa" => "lightpink",
    "dunkelrosa" => "darkpink",
    "braun" => "brown",
    "creme" => "white",
    "gruen" => "green",
    "mint" => "mint",
    "lila" => "purple",
    "dunkelblau" => "darkblue",
    "hellblau" => "lightblue",
]);
define("BRA_SIZE_DICTIONARY", [
    "s-1" => "ladiesS",
    "m-2" => "ladiesM",
    "l-3" => "ladiesL",
    "75b" => "ladiesM",
    "75c" => "ladiesL",
]);
// define("CHINESE_DESCRIPTION", "非常抱歉，暂时无法用中文向您详细描述此产品");
define("CHINESE_INFOLABEL", "退税信息");
define("CHINESE_INFOTEXT", "此商品显示价格为含税的价格。购买后，您可按流程办理退税。");
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

    // disply instructions for data creation and download
    echo "
        Bitte warte, bis die Seite aufgehört hat, zu laden (siehe Ladezeichen z.B. oben im Tab bei Chrome).<br>
        Anschließend kannst du die CSV und Zip-Datei mit Bildern hier herunterladen. <br>
        Wenn der Ladevorgang zu früh abgebrochen wird, werden Produkte und Bilder fehlen.<br>
        <br>
        CSV: <a href='https://august-pfueller.shop/wp-content/plugins/woocommerce-schirmbeck-fraport-csv/products_pfueller.csv'>products_pfueller.csv Download</a><br>
        Bilder: <a href='https://august-pfueller.shop/wp-content/plugins/woocommerce-schirmbeck-fraport-csv/images.zip'>images.zip Download</a><br>
    ";

    // TODO make CSV creation and download seperate from display
    echo plugin_dir_path("woocommerce-schirmbeck-fraport-csv.php") . "pfueller_products.csv";
    echo ABSPATH . 'wp-content/plugins/woocommerce-schirmbeck-fraport-csv/' . "pfueller_products.csv";
    $csv_file = fopen( ABSPATH . 'wp-content/plugins/woocommerce-schirmbeck-fraport-csv/' . "products_pfueller.csv","w");

    // create ZIP object for images zipfile
    $zip = new ZipArchive;
    $zip_open_err_code = $zip->open(ABSPATH . 'wp-content/plugins/woocommerce-schirmbeck-fraport-csv/' . 'images.zip', ZIPARCHIVE::OVERWRITE || ZipArchive::CREATE);
    if ($zip_open_err_code === TRUE) {
        echo '<br> Zipfile open: ok';
    } else {
        die ('<br> error creating zipfile: ' . $zip_open_err_code);
    }


    $counter = 1;

    echo "<table border='2px'>";

    /////////////////////// START column titles //////////////////////////
    echo "<tr>";
    foreach (PREVIEW_COLUMN_TITLES as $column_title) {
        echo "<td>" . $column_title . "</td>";
    }
    echo "</tr>";

    // and Start in CSV as well
    wsfc_output_csv_titles($csv_file);
    /////////////////////// END column titles //////////////////////////


    // Set constant values for every row
    $current_product_row_data = [
        'retailer_code' => RETAILER_CODE,
        'ean' => RETAILER_CODE,
        'info_label-zh_CN' => CHINESE_INFOLABEL,
        'info_text-zh_CN' => CHINESE_INFOTEXT,
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

        // USE WITH CAUTION mass translate titles with Google Translate USE WITH CAUTION
        // $meta_value = wsfc_google_api_translate('de', 'en', $parent_product->get_name());
        // add_post_meta($loop->post->ID, 'name-en_US', $meta_value, true);

        /* ONCE USED NOW ONLY DOCUMENTATIONAL PURPOSE mass add english description and chinese title and description meta fields
        add_post_meta($loop->post->ID, 'short_description-en_US', $parent_product->get_short_description(), true);
        add_post_meta($loop->post->ID, 'name-zh_CN', get_post_meta(get_the_ID(), 'name-en_US', true), true);
        add_post_meta($loop->post->ID, 'short_description-zh_CN', CHINESE_DESCRIPTION, true); */

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

            // generally, a variable product is transferred as configurable product.
            // When the attribute is something like "set" or "height", though, there are no correspondig attributes in Fraport shop, so we skip that product.
            $configurable = true;
            foreach ($variations as $variation) {
                if ( is_null($variation['attributes']['attribute_pa_groesse'])
                    && is_null($variation['attributes']['attribute_groesse'])
                    && is_null($variation['attributes']['attribute_pa_bh-groesse'])
                    && is_null($variation['attributes']['attribute_pa_kleidergroesse-damen'])
                    && is_null($variation['attributes']['attribute_pa_schuhgroesse'])
                    && is_null($variation['attributes']['attribute_pa_schuhgroesse-damen'])) {
                    $configurable = false;
                    break;
                }

                // concatenate String that will indicate which are the variations of this parent product
                $conf_products .= "pfueller_" . $sku_prefix . $variation['variation_id'] . ",";
            }

            // so if the product is a variable product but we cannot transfer it as a conigurable poduct, we just skip it for now.
            if ($configurable == false) continue;
        }

        // determine product family and magento variation attributes by product attributes
        $product_attributes = $parent_product->get_attributes();
        $family = "other"; //default case
        $magento_variation_attributes = "";
        if ($product_attributes['pa_farbe'] != null) $magento_variation_attributes = (($magento_type == "configurable") ? "colour" : ""); // only set attributes when configurable
        // no else if here because most products have color and a size defined but colour should only be a fallback attribute, e.g. for toys
        if ( !is_null($product_attributes['pa_schuhgroesse']) || !is_null($product_attributes['pa_schuhgroesse-damen']) ) {
            $family = "shoes";
            $magento_variation_attributes = (($magento_type == "configurable") ? "shoe_size" : ""); // only set attributes when configurable
        } elseif (!is_null($product_attributes['pa_groesse']) || !is_null($product_attributes['pa_bh-groesse'])
                || !is_null($product_attributes['pa_kleidergroesse-damen']) || !is_null($product_attributes['groesse'])) {
            $family = "clothes";
            $magento_variation_attributes = (($magento_type == "configurable") ? "clothing_size" : ""); // only set attributes when configurable
        }

        // determine which sex / gender this sells to by the product categories
        $product_categories = $parent_product->get_category_ids();
        foreach ($product_categories as $product_category) {
            $product_categories[$product_category] = get_the_category_by_ID($product_category);
        }
        $sex = "unisex"; //default to unisex
        if (in_array("Mädchen", $product_categories)) $sex = "girls";
        elseif (in_array("Jungs", $product_categories)) $sex = "boys";
        elseif (in_array("Damen", $product_categories)) $sex = "f";
        elseif (in_array("Pfüller Kids", $product_categories)) $sex = "children";
        // do not export non-kids products just now
        if (in_array("Damen", $product_categories)) continue;

        // assemble all product data into an array for the row
        $current_product_row_data_update = [
            'row-nr' => $counter,
            'thumbnail-helper' => '<a href="' . get_permalink() . '">' . $parent_product->get_image( array(150,150) ) . '</a>',
            'helper' => '', //'<pre>' . print_r($parent_product, true) . '</pre>',
            'helper2' => '',
            'origin_sku' => $origin_sku,
            'sku' => RETAILER_CODE . '_' . $origin_sku,
            'family' => $family, // DEBUG "<pre>" . print_r($parent_product->get_attributes(), true) . "</pre>",
            'magento_visibility' => MAGENTO_VISIBLE,
            'magento_type' => $magento_type,
            'magento_variation_attributes' => $magento_variation_attributes,
            'CONF-products' => $conf_products,
            'price-EUR' => $parent_product->get_price(), // TODO fill up all syonymous columns beforehand like image URLS, titles, etc..
            'brand_code' => get_the_terms($loop->post->ID, 'product_brand')[0]->slug,
            'sex' => $sex,
        ];
        $current_product_name_keys = array_fill_keys (
            [ 'name-de_DE', 'title-de_DE', "thumbnail_image_label-de_DE", "small_image_label-de_DE", "meta_title-de_DE", ] ,
            $parent_product->get_name() );
        $current_product_english_name_keys = array_fill_keys(
            [ 'name-en_US', 'title-en_US', "meta_title-en_US", "thumbnail_image_label-en_US", "small_image_label-en_US" ],
            get_post_meta(get_the_ID(), 'name-en_US', true));
        $current_product_chinese_name_keys = array_fill_keys(
            [ 'name-zh_CN', 'title-zh_CN', "meta_title-zh_CN", "thumbnail_image_label-zh_CN", "small_image_label-zh_CN", ],
            get_post_meta(get_the_ID(), 'name-zh_CN', true));
        $current_product_empty_keys = array_fill_keys( ['clothing_size', 'shoe_size'], '' );
        $current_product_description_keys = array_fill_keys( ['short_description-de_DE', 'description-de_DE',], $parent_product->get_short_description() );
        $current_product_en_description_keys = array_fill_keys( ['short_description-en_US',  'description-en_US'],  get_post_meta(get_the_ID(), 'short_description-en_US', true));
        $current_product_zh_description_keys = array_fill_keys( ['short_description-zh_CN',  'description-zh_CN'],  get_post_meta(get_the_ID(), 'short_description-zh_CN', true));
        $current_product_image_keys = array_fill_keys(
            [
                'thumbnail_image-de_DE', "thumbnail_image-en_US", "thumbnail_image-zh_CN",
                "small_image-de_DE", "small_image-en_US", "small_image-zh_CN",
                "large_image-de_DE", "large_image-en_US", "large_image-zh_CN",
            ],
            'images/' . $origin_sku . '.jpg' );
        // assign updates to existing product row data array
        $current_product_row_data = array_merge(
            $current_product_row_data, $current_product_row_data_update,
            $current_product_name_keys, $current_product_english_name_keys, $current_product_chinese_name_keys,
            $current_product_empty_keys,
            $current_product_zh_description_keys, $current_product_en_description_keys, $current_product_description_keys,
            $current_product_image_keys );

        // output parent row
        wsfc_output_data_row($current_product_row_data);
        // aaand also, put it in the CSV
        wsfc_output_csv_data_row($current_product_row_data, $csv_file);
        // aaaaaaaand add image file to ZIP while renaming
        $zip->addFile( get_attached_file($parent_product->get_image_id()) , 'images/' . $origin_sku . '.jpg');

        $counter++; // increment row count
        ////////////////////// END parent row ////////////////////////////////




        ////////////////////// START variations rows ////////////////////////////////

        if ($parent_product->product_type == 'variable') {

            $current_product_row_data['CONF-products'] = "";

            foreach($variations as $variation) {

                // reset our bool for looping over all sizes with just one variation in WooCommerce
                $one_variation_all_sizes = false;

                $origin_sku = $sku_prefix . $variation[variation_id];

                // create acceptable size string for Fraport CSV from existing attribute
                $clothing_size = ''; // default to no value
                $shoe_size = '';
                // childrens clothing sizes
                if ( !is_null($variation['attributes']['attribute_pa_groesse']) ) {
                    $clothing_size = wsfc_process_attribute_pa_groesse( $variation['attributes']['attribute_pa_groesse'] );

                    // there is a way of having only one "official" variation that covers all sizes. In that case we have to mak sure we still iterate over al sizes
                    // so far, it seems that that is indicated by an empty. but existing attribute field
                    if ($variation['attributes']['attribute_pa_groesse'] == '') {
                        $one_variation_all_sizes = true;
                        $clothing_size = $parent_product->get_variation_attributes()[pa_groesse]; // use as shorthand for the size options
                    }
                }
                // alternative sizes with a different attribute name
                elseif (!is_null($variation['attributes']['attribute_groesse'])) {
                    $clothing_size = wsfc_filter_lettered_sizes( $variation['attributes']['attribute_groesse'] );
                }
                // bra sizes
                elseif (!is_null($variation['attributes']['attribute_pa_bh-groesse'])) {
                    $clothing_size = BRA_SIZE_DICTIONARY[ $variation['attributes']['attribute_pa_bh-groesse'] ];
                }
                // women's sizes
                elseif (!is_null($variation['attributes']['attribute_pa_kleidergroesse-damen'])) {
                    $clothing_size = $variation['attributes']['attribute_pa_kleidergroesse-damen'];
                }
                // "women's shoe sizes"
                elseif (!is_null($variation['attributes']['attribute_pa_schuhgroesse-damen'])) {
                    $shoe_size = $variation['attributes']['attribute_pa_schuhgroesse-damen'];
                }
                // shoe sizes
                elseif (!is_null($variation['attributes']['attribute_pa_schuhgroesse'])) {
                    $shoe_size = $variation['attributes']['attribute_pa_schuhgroesse'];

                    // there is a way of having only one "official" variation that covers all sizes. In that case we have to mak sure we still iterate over al sizes
                    // so far, it seems that that is indicated by an empty. but existing attribute field
                    if ($variation['attributes']['attribute_pa_schuhgroesse'] == '') {
                        $one_variation_all_sizes = true;
                        $shoe_size = $parent_product->get_variation_attributes()[pa_schuhgroesse]; // use as shorthand for the size options
                    }
                }



                // assemble data for update per variation and update the data array
                $current_product_row_data_update = [
                    'row-nr' => $counter,
                    'helper' => '<pre>' . print_r($variation['attributes'], true) . '</pre>',
                    'helper2' => $clothing_size . $shoe_size . COLOUR_DICTIONARY[ $variation[attributes][attribute_pa_farbe] ],
                    'origin_sku' => $origin_sku,
                    'sku' => RETAILER_CODE . '_' . $origin_sku,
                    'magento_visibility' => MAGENTO_INVISIBLE, // TODO update constant variation values before entering loop
                    'magento_type' => 'simple',
                    'magento_variation_attributes' => '',
                    'price-EUR' => $variation[display_price],
                    'colour' => COLOUR_DICTIONARY[ $variation[attributes][attribute_pa_farbe] ],
                    'clothing_size' => $clothing_size, // if size is set in variation, use it as value
                    'shoe_size' => $shoe_size,
                ];
                // assign updates to existing product row data array
                $current_product_row_data = array_merge( $current_product_row_data, $current_product_row_data_update );

                // if we have only one variation that covers all sizes, we need to iterate over those sizes. Luckily, we already stored those in the clothing size variable
                if ( $one_variation_all_sizes ) {

                    // if it's not a shoe, then iterate oer clothing sizes
                    if ($shoe_size == '') {
                        foreach ($clothing_size as $key => $size_variation) {
                            $current_product_row_data['clothing_size'] = wsfc_process_attribute_pa_groesse($size_variation);
                            $current_product_row_data['helper2'] = wsfc_process_attribute_pa_groesse($size_variation) . $shoe_size . COLOUR_DICTIONARY[ $variation[attributes][attribute_pa_farbe] ];
                            wsfc_output_data_row($current_product_row_data);
                            // aaand also, put it in the CSV
                            wsfc_output_csv_data_row($current_product_row_data, $csv_file);
                        }
                    } elseif ($clothing_size == '') {

                        foreach ($shoe_size as $size_variation) {
                            $current_product_row_data['shoe_size'] = $size_variation;
                            $current_product_row_data['helper2'] = $size_variation . COLOUR_DICTIONARY[ $variation[attributes][attribute_pa_farbe] ];
                            wsfc_output_data_row($current_product_row_data);
                            // aaand also, put it in the CSV
                            wsfc_output_csv_data_row($current_product_row_data, $csv_file);
                        }

                        /*
                        foreach( wc_get_product_terms( $parent_product->id, 'attribute_pa_schuhgroesse' ) as $attribute_value ){
                            $current_product_row_data['shoe_size'] = $attribute_value;
                            $current_product_row_data['helper2'] = $attribute_value; //$size_variation . $variation[attributes][attribute_pa_farbe];
                            wsfc_output_data_row($current_product_row_data);
                        }*/
                    }

                } else {
                    wsfc_output_data_row($current_product_row_data);
                    // aaand also, put it in the CSV
                    wsfc_output_csv_data_row($current_product_row_data, $csv_file);
                }


                $counter++;
            }

        }

        ////////////////////// END variations rows ////////////////////////////////



    endwhile; wp_reset_query(); // Remember to reset



    /////////////////////// START the stop sign row //////////////////////////
    echo "<tr>";
    foreach (PREVIEW_COLUMN_TITLES as $column_title) {
        echo "<td>STOP</td>";
    }
    echo "</tr>";

    // and wrie stop row in CSV, too
    wsfc_output_csv_stop($csv_file);
    /////////////////////// END  the stop sign row  //////////////////////////


    echo "</table>";

    //aaand close CSV
    fclose($csv_file);
    // aaaaaaaaaand close zip
    $zip->close();

}

/*
 * a helper function that returns translated text via Google API
 * thanks to Savetheinternet at stackoverflow
 * https://stackoverflow.com/questions/4640378/translate-a-php-string-using-google-translator-api
 */
function wsfc_google_api_translate($from_lan, $to_lan, $text){

    $request_url = 'https://www.googleapis.com/language/translate/v2?q=' . urlencode($text) . '&source=' . $from_lan . '&target=' . $to_lan . '&key=AIzaSyCaUg1p8UxH8jwK-GI5suRHeCPnHvoqevU';

    $json = json_decode(file_get_contents($request_url));
    $translated_text = $json->data->translations[0]->translatedText;

    return $translated_text; // DEBUG . '<pre>' . print_r($json, true) . '</pre>';
}

function wsfc_output_data_row($row_data) {
    echo "<tr>";
    foreach (PREVIEW_COLUMN_TITLES as $column_title) {
        echo "<td>" . $row_data[$column_title] . "</td>";
    }
    echo "</tr>";
}

function wsfc_output_csv_titles($file) {
    $tobewritten = [];

    foreach (CSV_COLUMN_TITLES as $column_title) {
        $tobewritten[$column_title] = $column_title;
    }
    fputcsv($file, $tobewritten, ';');

}

function wsfc_output_csv_data_row($row_data, $file) {
    $tobewritten = [];

    foreach (CSV_COLUMN_TITLES as $column_title) {
        $tobewritten[$column_title] = $row_data[$column_title];
    }
    fputcsv($file, $tobewritten, ';');

}

function wsfc_output_csv_stop($file) {
    $tobewritten = [];

    foreach (CSV_COLUMN_TITLES as $column_title) {
        $tobewritten[$column_title] = 'STOP';
    }
    fputcsv($file, $tobewritten, ';');
}

function wsfc_process_attribute_pa_groesse($pa_groesse) {
    $processed_pa_groesse = 'child' . $pa_groesse;

    if (in_array($pa_groesse, ['S', 'M', 'L', 'XL', 's', 'm', 'l', 'xl'])) {
        $processed_pa_groesse = strtoupper($pa_groesse);
    }

    return $processed_pa_groesse;
}

function wsfc_filter_lettered_sizes($unfiltered_size) {
    $filtered_size = strtoupper($unfiltered_size);

    if (strpos($filtered_size, 'XXL') !== false) $filtered_size = 'XXL';
    elseif (strpos($filtered_size, 'XL') !== false) $filtered_size = 'XL';
    elseif (strpos($filtered_size, 'L') !== false) $filtered_size = 'L';
    elseif (strpos($filtered_size, 'M') !== false) $filtered_size = 'M';
    elseif (strpos($filtered_size, 'XXS') !== false) $filtered_size = 'XXS';
    elseif (strpos($filtered_size, 'XS') !== false) $filtered_size = 'XS';
    elseif (strpos($filtered_size, 'S') !== false) $filtered_size = 'S';
    else $filtered_size = $unfiltered_size; //if no size was found, return unaltered, original size strin

    return $filtered_size;
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