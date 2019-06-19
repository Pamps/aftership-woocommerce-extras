<?php
/*
   Plugin Name: Aftership WooCommerce Extras
   Plugin URI: https://www.darrenlambert.com/wordpress/plugins/aftership-woocommerce-extras/
   Description: Adds extra features to AfterShip for WooCommerce
   Version: 1.0
	 Author: Darren Lambert
	 Author URI: https://www.darrenlambert.com/
   Text Domain: aftershipwcextras
   License: GPLv3
	*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AftershipWooCommerceExtras
{
  /**
   * Constructor
   */
  public function __construct()
  {
    add_action('admin_init', array(&$this, 'admin_init'));
  }

  /**
   * Initialize the plugin
   */
  public function admin_init()
  {

    // Check WooCommerce and Aftership installed
    if ( is_plugin_active('aftership-woocommerce-tracking/aftership.php') && is_plugin_active('woocommerce/woocommerce.php') ) {
      add_filter( 'manage_edit-shop_order_columns', array(&$this, 'add_columns_to_orders') );
      add_action( 'manage_shop_order_posts_custom_column', array(&$this, 'add_columns_content') );
    }

  }

  /**
   * Adds column headers to 'Orders' page
   *
   * @param string[] $columns
   * @return string[] $new_columns
   */
  function add_columns_to_orders( $columns ) {

    // Build a new array of columns and add the new columns to the end
    $new_columns = array();

    foreach ( $columns as $column_name => $column_info ) {

        $new_columns[ $column_name ] = $column_info;

        if ( 'order_status' === $column_name ) {
            $new_columns['carrier'] = __( 'Carrier', 'aftershipwcextras' );
            $new_columns['tracking-number'] = __( 'Tracking #', 'aftershipwcextras' );
        }
    }

    return $new_columns;
  }

  /**
   * Adds column data
   *
   * @param string[] $column name of column being displayed
   */
  function add_columns_content( $column ) {
    global $post;

    // Carrier
    if ( 'carrier' === $column ) {

      $carrier = get_post_meta($post->ID, '_aftership_tracking_provider_name', true);
      if ($carrier) { echo $carrier; }

    }

    // Tracking number
    if ( 'tracking-number' === $column ) {

      $tracking_number = get_post_meta($post->ID, '_aftership_tracking_number', true);
      if ($tracking_number) { echo $tracking_number; }

    }

  }

}

new AftershipWooCommerceExtras;