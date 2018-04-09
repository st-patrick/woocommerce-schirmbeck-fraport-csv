<?php
/**
 * Created by PhpStorm.
 * User: reinp
 * Date: 4/9/2018
 * Time: 10:08 PM
 */




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


/*
 * clean string from spaces, special characters and make it all caps
 */
function wsfc_build_sku_prefix_from_name($name) {
    /*
     * build SKU prefix from name.
     * First, remove whitespaces.
     * Then, convert special chars. Thanks to Stewie on Stackoverflow: https://stackoverflow.com/questions/9720665/how-to-convert-special-characters-to-normal-characters
     * Then, remove other special chars. Thanks to Terry Harvey on Stackoverflow: https://stackoverflow.com/questions/14114411/remove-all-special-characters-from-a-string
     * Lastly, cut first three letters and convert to upper case.
     */
    $sku_prefix =
        strtoupper(
            substr(
                preg_replace('/[^A-Za-z0-9\-]/', '',
                    iconv('utf-8', 'ascii//TRANSLIT',
                        preg_replace('/\s+/', '',
                            $name
                        )
                    )
                ), 0, 3
            )
        );

    return $sku_prefix;
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