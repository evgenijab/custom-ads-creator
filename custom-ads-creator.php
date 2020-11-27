<?php
/**
 * Plugin Name:       Custom ADs Creator
 * Description:       Simple Plugin that creates custom ADs
 * Version:           1.1
 * Author:            Evgenija Butleska
 * Author URI:        https://evgenijab.github.io/
 */
wp_enqueue_style( 'tabs-css',  plugin_dir_url( __FILE__ ) . '/inc/custom-ads.css' );
wp_enqueue_script('tabs-js',  plugin_dir_url( __FILE__ ) . '/inc/custom-ads.js', array('jquery'), '1.0.0', false );

add_action( 'init', 'create_custom_ads_post' );
// The custom function to register a custom_ads post type
// function create_custom_ads_post() {
function create_custom_ads_post() {
    register_post_type( 'custom_ads',
        array(
            'labels' => array(
                'name' => 'Custom ADs',
                'singular_name' => 'Custom AD',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New AD',
                'edit' => 'Edit',
                'edit_item' => 'Edit AD',
                'new_item' => 'New AD',
                'view' => 'View',
                'view_item' => 'View AD',
                'search_items' => 'Search ADs',
                'not_found' => 'No ADs found',
                'not_found_in_trash' => 'No ADs found in Trash',
                'parent' => 'Parent AD'
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields', 'excerpt' ),
            'taxonomies' => array( 'location', 'price' ),
            'has_archive' => true,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'query_var'         => true,
        )
    );
}
// The custom function to register a custom taxonomy 'Locations'
function locations_taxonomies() {
  $labels = array(
    'name'              => _x( 'Locations', 'taxonomy general name' ),
    'singular_name'     => _x( 'Location', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Locations' ),
    'all_items'         => __( 'All Locations' ),
    'parent_item'       => __( 'Parent Locations' ),
    'parent_item_colon' => __( 'Parent Locations:' ),
    'edit_item'         => __( 'Edit Location' ), 
    'update_item'       => __( 'Update Location' ),
    'add_new_item'      => __( 'Add New Location' ),
    'new_item_name'     => __( 'New Location' ),
    'menu_name'         => __( 'Location' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'locations', 'custom_ads', $args );
}
add_action( 'init', 'locations_taxonomies', 0 );
// The custom function to register a custom taxonomy 'Prices'
function prices_taxonomies() {
  $labels = array(
    'name'              => _x( 'Prices', 'taxonomy general name' ),
    'singular_name'     => _x( 'Price', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Prices' ),
    'all_items'         => __( 'All Prices' ),
    'parent_item'       => __( 'Parent Prices' ),
    'parent_item_colon' => __( 'Parent Prices:' ),
    'edit_item'         => __( 'Edit Price' ), 
    'update_item'       => __( 'Update Price' ),
    'add_new_item'      => __( 'Add New Price' ),
    'new_item_name'     => __( 'New Price' ),
    'menu_name'         => __( 'Price' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'prices', 'custom_ads', $args );
}
add_action( 'init', 'prices_taxonomies', 0 );
// Adding custom_ads posts to query
//Preview in homepage when homepage displays latest posts
function add_custom_ads_to_query( $query ) {
  if ( is_home() && $query->is_main_query() )
    $query->set( 'post_type', array( 'post', 'custom_ads' ) );
  return $query;
}
add_action( 'pre_get_posts', 'add_custom_ads_to_query' );
//Adding custom single-{post_type}.php template
add_filter( 'template_include', 'include_single_ads_template_function', 1 );
function include_single_ads_template_function( $template_path ) {
    if ( get_post_type() == 'custom_ads' ) {
        if ( is_single() ) {
            // checks if the tempalte file exists in the theme folder first,
            // otherwise serves the template file from the plugin dir
            if ( $theme_file = locate_template( array ( 'templates/single-custom_ads.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . 'templates/single-custom_ads.php';
            }
        }
    }
    return $template_path;
}
//Adding custom page template
add_filter( 'page_template', 'ads_list_page_template' );
function ads_list_page_template( $page_template ){

    if ( get_page_template_slug() == 'template-ads-list.php' ) {
        $page_template = dirname( __FILE__ ) . '/template-ads-list.php';
    }
    return $page_template;
}

//Adding "Template ADs List" template to page attirbute template section to be selected from dorpdown.
add_filter( 'theme_page_templates', 'ads_list_add_template_to_select', 10, 4 );
function ads_list_add_template_to_select( $post_templates, $wp_theme, $post, $post_type ) {

    $post_templates['template-ads-list.php'] = __('Template ADs List');

    return $post_templates;
}
//Creating a filter by taxonomy for ADs post type
add_action('wp_ajax_myfilter', 'filter_by_taxonomy_function');
add_action('wp_ajax_nopriv_myfilter', 'filter_by_taxonomy_function');
 
function filter_by_taxonomy_function(){

//Show custom ADs post if only one of both filters is selected            
    if( isset( $_POST['locationfilter'] ) && $_POST['locationfilter'] || isset($_POST['pricefilter']) && $_POST['pricefilter'])
        $args['tax_query'] = array(
            'relation' => 'OR',
                array(
                    'taxonomy' => 'locations',
                    'field' => 'id',
                    'terms' => $_POST['locationfilter'],
                    'post_type' => 'custom_ads',
                ),
                array(
                    'taxonomy' => 'prices',
                    'field' => 'id',
                    'terms' => $_POST['pricefilter'],
                    'post_type' => 'custom_ads',

                )
        );
//Show custom ADs post if the two filters are selected  
    if( isset( $_POST['locationfilter'] ) && $_POST['locationfilter'] && isset($_POST['pricefilter']) && $_POST['pricefilter'])
        $args['tax_query'] = array(
            'relation' => 'AND',
                        array(
                    'taxonomy' => 'locations', 'prices',
                    'field' => 'id',
                    'terms' => $_POST['locationfilter'], $_POST['pricefilter'],
                    'post_type' => 'custom_ads',
                ),
                array(
                    'taxonomy' => 'prices',
                    'field' => 'id',
                    'terms' => $_POST['pricefilter'],
                    'post_type' => 'custom_ads',
                )

            );

    $loop = new WP_Query( $args );
    if( $loop->have_posts() ) :
        while( $loop->have_posts() ): $loop->the_post();
    if ( '' === locate_template( 'template-parts/custom-ads-content.php', true, false ) )
        include( 'template-parts/custom-ads-content.php' ); 
         endwhile;
        wp_reset_postdata();
    else : echo 'No ADs found';
    endif;
    die();
}
?>