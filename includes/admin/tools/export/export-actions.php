<?php
/**
 * Exports Actions
 *
 * These are actions related to exporting data from Give
 *
 * @package     Give
 * @subpackage  Admin/Export
 * @copyright   Copyright (c) 2016, WordImpress
 * @license     https://opensource.org/licenses/gpl-license GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Process the download file generated by a batch export
 *
 * @since 1.5
 * @return void
 */
function give_process_batch_export_form() {

	if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'give-batch-export' ) ) {
		wp_die( esc_html__( 'Nonce verification failed.', 'give' ), esc_html__( 'Error', 'give' ), array( 'response' => 403 ) );
	}

	require_once GIVE_PLUGIN_DIR . 'includes/admin/tools/export/class-batch-export.php';

	/**
	 * Fires before batch export.
	 *
	 * @since 1.5
	 *
	 * @param string $class Export class.
	 */
	do_action( 'give_batch_export_class_include', $_REQUEST['class'] );

	$export = new $_REQUEST['class'];
	$export->export();

}

add_action( 'give_form_batch_export', 'give_process_batch_export_form' );

/**
 * Exports earnings for a specified time period
 *
 * Give_Earnings_Export class.
 *
 * @since 1.5
 * @return void
 */
function give_export_earnings() {
	require_once GIVE_PLUGIN_DIR . 'includes/admin/reporting/class-export-earnings.php';

	$earnings_export = new Give_Earnings_Export();

	$earnings_export->export();
}

add_action( 'give_earnings_export', 'give_export_earnings' );


/**
 * Add a hook allowing extensions to register a hook on the batch export process
 *
 * @since  1.5
 * @return void
 */
function give_register_batch_exporters() {
	if ( is_admin() ) {
		/**
		 * Fires in the admin, while plugins loaded.
		 *
		 * Allowing extensions to register a hook on the batch export process.
		 *
		 * @since 1.5
		 *
		 * @param string $class Export class.
		 */
		do_action( 'give_register_batch_exporter' );
	}
}

add_action( 'plugins_loaded', 'give_register_batch_exporters' );

/**
 * Register the payments batch exporter
 *
 * @since  1.5
 */
function give_register_payments_batch_export() {
	add_action( 'give_batch_export_class_include', 'give_include_payments_batch_processor', 10, 1 );
}

add_action( 'give_register_batch_exporter', 'give_register_payments_batch_export', 10 );

/**
 * Loads the payments batch process if needed
 *
 * @since  1.5
 *
 * @param  string $class The class being requested to run for the batch export
 *
 * @return void
 */
function give_include_payments_batch_processor( $class ) {

	if ( 'Give_Batch_Payments_Export' === $class ) {
		require_once GIVE_PLUGIN_DIR . 'includes/admin/tools/export/class-batch-export-payments.php';
	}

}

/**
 * Register the customers batch exporter
 *
 * @since  1.5.2
 */
function give_register_customers_batch_export() {
	add_action( 'give_batch_export_class_include', 'give_include_customers_batch_processor', 10, 1 );
}

add_action( 'give_register_batch_exporter', 'give_register_customers_batch_export', 10 );

/**
 * Loads the customers batch process if needed
 *
 * @since  1.5.2
 *
 * @param  string $class The class being requested to run for the batch export
 *
 * @return void
 */
function give_include_customers_batch_processor( $class ) {

	if ( 'Give_Batch_Customers_Export' === $class ) {
		require_once GIVE_PLUGIN_DIR . 'includes/admin/tools/export/class-batch-export-customers.php';
	}

}

/**
 * Register the download products batch exporter
 *
 * @since  1.5
 */
function give_register_forms_batch_export() {
	add_action( 'give_batch_export_class_include', 'give_include_forms_batch_processor', 10, 1 );
}

add_action( 'give_register_batch_exporter', 'give_register_forms_batch_export', 10 );

/**
 * Loads the file downloads batch process if needed
 *
 * @since  1.5
 *
 * @param  string $class The class being requested to run for the batch export
 *
 * @return void
 */
function give_include_forms_batch_processor( $class ) {

	if ( 'Give_Batch_Forms_Export' === $class ) {
		require_once GIVE_PLUGIN_DIR . 'includes/admin/tools/export/class-batch-export-forms.php';
	}

}
