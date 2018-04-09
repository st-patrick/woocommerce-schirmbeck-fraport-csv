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

include_once "constants.php";
include_once "functions.php";

/*
 * add custom button in products submenu in admin area
 * see functions.php for what actually happens in mt_add_pages
 * source: https://codex.wordpress.org/Administration_Menus#Using_add_submenu_page
 */
// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');


/******************************************* START shared values and setting ground rules **************************/

// set locale for special characters n chinese Characters
setlocale(LC_CTYPE, 'en_US.UTF8');

/******************************************* END shared values and setting ground rules **************************/


function wsfc_display_future_table() {

    // disply instructions for data creation and download
    echo "<br>
        Bitte warte, bis die Seite aufgehört hat, zu laden (siehe Ladezeichen z.B. oben im Tab bei Chrome).<br>
        Anschließend kannst du die CSV und Zip-Datei mit Bildern hier herunterladen. <br>
        Wenn der Ladevorgang zu früh abgebrochen wird, werden Produkte und Bilder fehlen.<br>
        <br>
        CSV: <a href='https://august-pfueller.shop/wp-content/plugins/woocommerce-schirmbeck-fraport-csv/products_pfueller.csv'>products_pfueller.csv Download</a><br>
        Bilder: <a href='https://august-pfueller.shop/wp-content/plugins/woocommerce-schirmbeck-fraport-csv/images.zip'>images.zip Download</a><br>
    ";

    // TODO make CSV creation and download seperate from display

    // DEBUG echo plugin_dir_path("woocommerce-schirmbeck-fraport-csv.php") . "pfueller_products.csv";
    // DEBUG echo ABSPATH . 'wp-content/plugins/woocommerce-schirmbeck-fraport-csv/' . "pfueller_products.csv";

    $csv_file = fopen( ABSPATH . 'wp-content/plugins/woocommerce-schirmbeck-fraport-csv/' . "products_pfueller.csv","w");

    // create ZIP object for images zipfile
    $zip = new ZipArchive;
    $zip_open_err_code = $zip->open(ABSPATH . 'wp-content/plugins/woocommerce-schirmbeck-fraport-csv/' . 'images.zip', ZIPARCHIVE::OVERWRITE || ZipArchive::CREATE);
    if ($zip_open_err_code === TRUE) {
        echo '<br> Zipfile open: ok<br>';
    } else {
        die ('<br> error creating zipfile: ' . $zip_open_err_code . '<br>');
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
        "is_saleable" => 1
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

        /* ONCE USED NOW ONLY DOCUMENTATIONAL PURPOSE remove HTML entities from name fields
        // thanks to txnull for helpful comment on http://php.net/manual/de/function.html-entity-decode.php
        $english_name_without_html = html_entity_decode(get_post_meta(get_the_ID(), 'name-en_US', true),  ENT_QUOTES | ENT_XML1, 'UTF-8');
        // DEBUG echo $english_name_without_html;
        update_post_meta($loop->post->ID, 'name-en_US', $english_name_without_html);
        update_post_meta($loop->post->ID, 'name-zh_CN', $english_name_without_html);*/


        // some products are not available for purchase bc no price has been set or similar reasons. Skip those.
        if ($parent_product->is_purchasable() != 1) continue;

        /*
         * build SKU prefix from name.
         * First, remove whitespaces.
         * Then, convert special chars. Thanks to Stewie on Stackoverflow: https://stackoverflow.com/questions/9720665/how-to-convert-special-characters-to-normal-characters
         * Lastly, cut first three letters and convert to upper case.
         */
        $sku_prefix = wsfc_build_sku_prefix_from_name($parent_product->get_name());
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

                // TODO refactor this code into a function that determines whether a variation is a "one variation product for multiple vriations'
                // there is a way of having only one "official" variation that covers all sizes. In that case we have to mak sure we still iterate over al sizes
                // so far, it seems that that is indicated by an empty. but existing attribute field
                $counter = 0;

                if ( is_array(['attributes']['attribute_pa_groesse'])) {
                    $clothing_size = $parent_product->get_variation_attributes()[pa_groesse]; // use as shorthand for the size options
                    foreach ($clothing_size as $size_variation) {
                        // we need to "invent" new SKUs for the single WooCommerce variations that represent multiple actual sizes / colors / etc...
                        // TODO refactor these lines into a function
                        $counter++;
                        $conf_products .= "pfueller_" . $sku_prefix . $variation['variation_id'] . $counter . ",";
                    }
                } elseif ( is_array($variation['attributes']['attribute_pa_schuhgroesse'])) {
                    $shoe_size = $parent_product->get_variation_attributes()[pa_schuhgroesse]; // use as shorthand for the size options
                    foreach ($shoe_size as $size_variation) {
                        // we need to "invent" new SKUs for the single WooCommerce variations that represent multiple actual sizes / colors / etc...
                        // TODO refactor these lines into a function
                        $counter++;
                        $conf_products .= "pfueller_" . $sku_prefix . $variation['variation_id'] . $counter . ",";
                    }
                } else {
                    // concatenate String that will indicate which are the variations of this parent product
                    $conf_products .= "pfueller_" . $sku_prefix . $variation['variation_id'] . ",";
                }

                // DEBUG $conf_products .= "<pre>" . print_r($variation['attributes'], true) . "</pre>";
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

        // store categories of this product in an array
        $product_categories = $parent_product->get_category_ids();
        $categories = "";
        foreach ($product_categories as $product_category) {
            $product_categories[$product_category] = get_the_category_by_ID($product_category);
        }

        // do not export non-kids products just now
        if (in_array("Damen", $product_categories)) continue;
        // determine which sex / gender this sells to by the product categories
        $sex = "unisex"; //default to unisex
        if (in_array("Mädchen", $product_categories)) $sex = "girls";
        elseif (in_array("Jungs", $product_categories)) $sex = "boys";
        elseif (in_array("Damen", $product_categories)) $sex = "f";
        elseif (in_array("Pfüller Kids", $product_categories)) $sex = "children";

        // build Fraport categories from given shop categories
        if (in_array("Pfüller Kids", $product_categories) || in_array("Kindermode", $product_categories) || in_array("Mädchen", $product_categories)) {
            $categories .= "kinderbekleidung,";
            if (in_array("Schuhe", $product_categories)) {
                $categories .= "kinderschuhe,";
            } elseif (in_array("Accessoires", $product_categories)) {
                $categories .= "kinderausstattung,";
            }
        } elseif (in_array("Kinderwagen", $product_categories) || in_array("Baby", $product_categories)) {
            $categories .= "kinderausstattung";
        } else {
            echo "<br>" . $origin_sku . " not in a useful category, skipping...";
            continue;
        }


        //prepare brand code frm dictionary
        $brand_code = get_the_terms($loop->post->ID, 'product_brand')[0]->slug;
        $fraport_dictionary_brand_code = BRAND_DICTIONARY[ $brand_code ];
        if ($fraport_dictionary_brand_code != null) $brand_code = $fraport_dictionary_brand_code;

        // prepare band labels from fraport array
        $brand_label = BRAND_LABEL_DICTIONARY[$brand_code];
        $chinese_brand_label = BRAND_CHINESE_LABEL_DICTIONARY[$brand_code];
        if ($chinese_brand_label == null) $chinese_brand_label = $brand_label;


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
            'categories' => $categories,
            'price-EUR' => $parent_product->get_price(), // TODO fill up all syonymous columns beforehand like image URLS, titles, etc..
            'brand_code' =>  $brand_code,
            "label-zh_CN" => $chinese_brand_label,
            "label-en_US" => $brand_label,
            "label-de_DE" => $brand_label,
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

        // store the row of attributes of the parent product in a seperate array, bc it needs to be the last row after
        // its variations ... since PHP assigns by copy with arrays, this is fairly straightforward
        $parent_product_row_data = $current_product_row_data;


        // add image file to ZIP while renaming
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
                    $counter = 0;

                    // if it's not a shoe, then iterate oer clothing sizes
                    // TODO this if clause isn't too good, rather is_array on the field itself
                    if ($shoe_size == '') {
                        foreach ($clothing_size as $key => $size_variation) {

                            // we need to "invent" new SKUs for the single WooCommerce variations that represent multiple actual sizes / colors / etc...
                            // TODO refactor these lines into a function
                            $counter++;
                            $variation_sku = $origin_sku . $counter;
                            $current_product_row_data['origin_sku'] = $variation_sku;
                            $current_product_row_data['sku'] = RETAILER_CODE . '_' . $variation_sku;

                            $current_product_row_data['clothing_size'] = wsfc_process_attribute_pa_groesse($size_variation);
                            $current_product_row_data['helper2'] = wsfc_process_attribute_pa_groesse($size_variation) . $shoe_size . COLOUR_DICTIONARY[ $variation[attributes][attribute_pa_farbe] ];
                            wsfc_output_data_row($current_product_row_data);
                            // aaand also, put it in the CSV
                            wsfc_output_csv_data_row($current_product_row_data, $csv_file);
                        }
                    } elseif ($clothing_size == '') {

                        foreach ($shoe_size as $size_variation) {

                            // we need to "invent" new SKUs for the single WooCommerce variations that represent multiple actual sizes / colors / etc...
                            // TODO refactor these lines into a function, see above
                            $counter++;
                            $variation_sku = $origin_sku . $counter;
                            $current_product_row_data['origin_sku'] = $variation_sku;
                            $current_product_row_data['sku'] = RETAILER_CODE . '_' . $variation_sku;

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



        // finally, output parent product row after its variations
        // output parent row
        wsfc_output_data_row($parent_product_row_data);
        // aaand also, put it in the CSV
        wsfc_output_csv_data_row($parent_product_row_data, $csv_file);



    endwhile; wp_reset_query(); // Remember to reset


    echo "</table>";

    //aaand close CSV
    fclose($csv_file);
    // aaaaaaaaaand close zip
    $zip->close();

}