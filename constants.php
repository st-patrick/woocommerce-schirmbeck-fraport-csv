<?php
/**
 * Created by PhpStorm.
 * User: reinp
 * Date: 4/9/2018
 * Time: 8:50 PM
 */

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

        "is_saleable",

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

        "delivery_disable",

        "brand_code", // APPARENTLY NOT NEEDED "label-zh_CN", "label-en_US", "label-de_DE",

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
        /*"is_cabin_baggage",*/ "bag_size", "effect-de_DE", "effect-en_US", "package_size", "fragrancenotes-de_DE", "fragrancenotes-en_US", "storage_capacity", "target_age", "material-de_DE", "material-en_US",

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

        "categories",

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

        "brand_code", "label-zh_CN", "label-en_US", // NOT IN PEVIEW "label-de_DE",

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
define("BRAND_DICTIONARY", [
    "american-outfitters" =>  "american-outfitters",
    "trixie" =>  "trixie",
    "petit-bateau" =>  "petit-bateau",
    "billieblush" =>  "billieblush",
    "absorba" =>  "absorba",
    "wertfein" =>  "wertfein",
    "gucci-kids" =>  "gucci",
    "dolce-gabbana" =>  "dolce-and-gabbana",
    "givenchy-kids" =>  "givenchy",
    "il-gufo" =>  "il-gufo",
    "scotch-soda" =>  "scotch-and-soda",
    "dsquared2" =>  "dsquared",
    "hitch-hiker" =>  "hitch-hiker",
    "tartine-et-chocolat" =>  "tartine_chocolat",
    "kenzo-kids" =>  "kenzo",
    "liewood" =>  "liewood",
    "milestone" =>  "milestone",
    "yellowsub" =>  "yellowsub",
    "annabels-bows" =>  "annabels-bows",
    "maman-va-etre-jalouse" =>  "maman-va-tre-jalouse",
    "teeny-tini" =>  "teeny-tini",
    "aletta" =>  "aletta",
    "manuela-de-juan" =>  "manuela-de-juan",
    "aquazzura" =>  "aquazzura",
    "fior-di-coccole" =>  "fior-di-coccole",
    "jamara-germany" =>  "jamara",
    "crocodile-creek" =>  "crocodile-creek",
    "living-kitzbuehel" =>  "living-kitzbuehel",
    "atsuko-matano" =>  "atsuko-matano",
    "jottum" =>  "jottum",
    "bleu-comme-gris" =>  "bleu-comme-gris",
    "seidenweiss" =>  "seidenweiss",
    "verdecchia" =>  "verdecchia",
    "done-by-deer" =>  "done-by-deer",
    "impfs-elfs" =>  "impfs-elfs",
    "les-lutins" =>  "les-lutins",
    "minna-parikka" =>  "minna_parikka",
    "milk-friends" =>  "milk-friends",
    "rex-international" =>  "rex-international",
    "coincoin-paris" =>  "coincoin-paris",
    "vivetta-kids" =>  "vivetta-kids",
    "donkey-products" =>  "donkey-products",
    "per-te-una-storia-italiana" =>  "per-te-una-storia-italiana",
    "aletta" =>  "aletta",
    "il-gufo" =>  "il-gufo",
    "billieblush" =>  "billieblush",
    "Po" =>  "po-paris",
    "elodie-details" =>  "elodie-details",
    "atelier-choux-paris" =>  "atelier-choux-paris",
]);
define ("BRAND_LABEL_DICTIONARY", [
    "american-outfitters" => "American Outfitters",
    "trixie" => "Trixie",
    "petit-bateau" => "Petit Bateau",
    "billieblush" => "Billieblush",
    "absorba" => "Absorba",
    "wertfein" => "Wertfein",
    "gucci" => "Gucci",
    "dolce-and-gabbana" => "Dolce & Gabbana",
    "givenchy" => "Givenchy",
    "il-gufo" => "Il Gufo",
    "scotch-and-soda" => "Scotch & Soda",
    "dsquared" => "Dsquared",
    "hitch-hiker" => "Hitch Hiker",
    "tartine_chocolat" => "Tartine et Chocolat",
    "kenzo" => "Kenzo",
    "liewood" => "Liewood",
    "milestone" => "Milestone",
    "yellowsub" => "Yellowsub",
    "annabels-bows" => "Annabels Bows",
    "maman-va-tre-jalouse" => "Maman va être Jalouse",
    "teeny-tini" => "Teeny Tini",
    "aletta" => "Aletta",
    "manuela-de-juan" => "Manuela de Juan",
    "aquazzura" => "Aquazzura",
    "fior-di-coccole" => "Fior di Coccole",
    "jamara" => "Jamara",
    "crocodile-creek" => "Crocodile Creek",
    "living-kitzbuehel" => "Living Kitzbühel",
    "atsuko-matano" => "Atsuko Matano",
    "jottum" => "Jottum",
    "bleu-comme-gris" => "Bleu Comme Gris",
    "seidenweiss" => "Seidenweiss",
    "verdecchia" => "Verdecchia",
    "done-by-deer" => "Done by Deer",
    "impfs-elfs" => "Impfs Elfs",
    "les-lutins" => "Les Lutins",
    "minna_parikka" => "Minna Parikka",
    "milk-friends" => "Milk Friends",
    "rex-international" => "Rex International",
    "coincoin-paris" => "Coincoin Paris",
    "vivetta-kids" => "Vivetta Kids",
    "donkey-products" => "Donkey Products",
    "per-te-una-storia-italiana" => "Per Te Una Storia Italiana",
    "aletta" => "Aletta",
    "il-gufo" => "Il Gufo",
    "billieblush" => "Billieblush",
    "po-paris" => "Po! Paris",
    "elodie-details" => "Elodie Details",
    "atelier-choux-paris" => "Atelier Choux Paris",
]);
define ("BRAND_CHINESE_LABEL_DICTIONARY", [
    "gucci" => "Gucci 古驰",
    "dolce-and-gabbana" => "Dolce & Gabbana 杜嘉班纳",
    "kenzo" => "Kenzo 高田贤三",
]);
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