<?php
/**
 * Function at_import_mdh_scripts
 * Load import scripts
 */
add_action('admin_enqueue_scripts', 'at_import_mdh_scripts');
function at_import_mdh_scripts($page) {
    if(strpos($page, 'at_import_mydirtyhobby') === false) return;

    wp_enqueue_script( 'at-mydirtyhobby', get_template_directory_uri() . '/library/import/assets/js/mydirtyhobby.js');
}


add_action('wp_ajax_at_import_mdh_crawl_amateurs', 'at_import_mdh_crawl_amateurs');
function at_import_mdh_crawl_amateurs() {
    $offset = (isset($_GET['offset']) ? $_GET['offset'] : '0');
    $import = new AT_Import_MDH_Crawler();
    $import->amateurs_crawl($offset);
}