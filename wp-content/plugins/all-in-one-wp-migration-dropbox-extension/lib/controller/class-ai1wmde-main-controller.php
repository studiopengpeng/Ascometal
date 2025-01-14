<?php
/**
 * Copyright (C) 2014-2018 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

class Ai1wmde_Main_Controller {

	/**
	 * Main Application Controller
	 *
	 * @return Ai1wmde_Main_Controller
	 */
	public function __construct() {
		register_activation_hook( AI1WMDE_PLUGIN_BASENAME, array( $this, 'activation_hook' ) );

		// Activate hooks
		$this->activate_actions()
			->activate_filters()
			->activate_textdomain();
	}

	/**
	 * Activation hook callback
	 *
	 * @return Object Instance of this class
	 */
	public function activation_hook() {

	}

	/**
	 * Initializes language domain for the plugin
	 *
	 * @return Object Instance of this class
	 */
	private function activate_textdomain() {
		load_plugin_textdomain( AI1WMDE_PLUGIN_NAME, false, false );

		return $this;
	}

	/**
	 * Register plugin menus
	 *
	 * @return void
	 */
	public function admin_menu() {
		// sublevel Settings menu
		add_submenu_page(
			'site-migration-export',
			__( 'Dropbox Settings', AI1WMDE_PLUGIN_NAME ),
			__( 'Dropbox Settings', AI1WMDE_PLUGIN_NAME ),
			'export',
			'site-migration-dropbox-settings',
			'Ai1wmde_Settings_Controller::index'
		);
	}

	/**
	 * Register scripts and styles for Export Controller
	 *
	 * @return void
	 */
	public function register_export_scripts_and_styles( $hook ) {
		if ( stripos( 'toplevel_page_site-migration-export', $hook ) === false ) {
			return;
		}

		wp_enqueue_script(
			'ai1wmde-js-export',
			Ai1wm_Template::asset_link( 'javascript/export.min.js', 'AI1WMDE' ),
			array( 'jquery' )
		);
		wp_enqueue_style(
			'ai1wmde-css-export',
			Ai1wm_Template::asset_link( 'css/export.min.css', 'AI1WMDE' )
		);
	}

	/**
	 * Register scripts and styles for Import Controller
	 *
	 * @return void
	 */
	public function register_import_scripts_and_styles( $hook ) {
		if ( stripos( 'all-in-one-wp-migration_page_site-migration-import', $hook ) === false ) {
			return;
		}

		wp_enqueue_style(
			'ai1wmde-css-import',
			Ai1wm_Template::asset_link( 'css/import.min.css', 'AI1WMDE' )
		);
		wp_enqueue_script(
			'ai1wmde-js-import',
			Ai1wm_Template::asset_link( 'javascript/import.min.js', 'AI1WMDE' ),
			array( 'jquery' )
		);
		wp_localize_script( 'ai1wmde-js-import', 'ai1wmde_import', array(
			'token' => get_option( 'ai1wmde_dropbox_token' ),
			'ajax'  => array(
				'folder_url' => admin_url( 'admin-ajax.php?action=ai1wmde_dropbox_folder' ),
			),
		) );
	}

	/**
	 * Register scripts and styles for Settings Controller
	 *
	 * @return void
	 */
	public function register_settings_scripts_and_styles( $hook ) {
		if ( stripos( 'all-in-one-wp-migration_page_site-migration-dropbox-settings', $hook ) === false ) {
			return;
		}

		wp_enqueue_script(
			'ai1wmde-js-settings',
			Ai1wm_Template::asset_link( 'javascript/settings.min.js', 'AI1WMDE' ),
			array( 'jquery' )
		);
		wp_enqueue_style(
			'ai1wm-css-export',
			Ai1wm_Template::asset_link( 'css/export.min.css' )
		);
		wp_enqueue_style(
			'ai1wmde-css-settings',
			Ai1wm_Template::asset_link( 'css/settings.min.css', 'AI1WMDE' )
		);
		wp_localize_script( 'ai1wmde-js-settings', 'ai1wmde_settings', array(
			'token' => get_option( 'ai1wmde_dropbox_token' ),
			'ajax'  => array(
				'account_url' => admin_url( 'admin-ajax.php?action=ai1wmde_dropbox_account' ),
			),
		) );
		wp_localize_script( 'ai1wmde-js-settings', 'ai1wm_feedback', array(
			'ajax'       => array(
				'url' => admin_url( 'admin-ajax.php?action=ai1wm_feedback' ),
			),
			'secret_key' => get_option( AI1WM_SECRET_KEY ),
		) );
	}

	/**
	 * Outputs menu icon between head tags
	 *
	 * @return void
	 */
	public function admin_head() {
		?>
		<style type="text/css" media="all">
			.ai1wm-label {
				border: 1px solid #5cb85c;
				background-color: transparent;
				color: #5cb85c;
				cursor: pointer;
				text-transform: uppercase;
				font-weight: 600;
				outline: none;
				transition: background-color 0.2s ease-out;
				padding: .2em .6em;
				font-size: 0.8em;
				border-radius: 5px;
				text-decoration: none !important;
			}

			.ai1wm-label:hover {
				background-color: #5cb85c;
				color: #fff;
			}
		</style>
	<?php
	}

	/**
	 * Register listeners for actions
	 *
	 * @return Object Instance of this class
	 */
	private function activate_actions() {
		// Init
		add_action( 'admin_init', array( $this, 'init' ) );

		// Router
		add_action( 'admin_init', array( $this, 'router' ) );

		// Admin header
		add_action( 'admin_head', array( $this, 'admin_head' ) );

		// All in One WP Migration
		add_action( 'plugins_loaded', array( $this, 'ai1wm_loaded' ), 10 );

		// Export and import commands
		add_action( 'plugins_loaded', array( $this, 'ai1wm_commands' ), 20 );

		// Enable notifications
		add_action( 'plugins_loaded', array( $this, 'ai1wm_notification' ), 20 );

		// Add export scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'register_export_scripts_and_styles' ), 20 );

		// Add import scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'register_import_scripts_and_styles' ), 20 );

		// Add settings scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'register_settings_scripts_and_styles' ), 20 );

		return $this;
	}

	/**
	 * Register listeners for filters
	 *
	 * @return Object Instance of this class
	 */
	private function activate_filters() {
		// Add links to plugin list page
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 5, 2 );

		return $this;
	}

	/**
	 * Enable notifications
	 *
	 * @return void
	 */
	public function ai1wm_notification() {
		if ( isset( $_REQUEST['dropbox'] ) ) {
			// Add ok notifications
			add_filter( 'ai1wm_notification_ok_toggle', 'Ai1wmde_Settings_Controller::notify_ok_toggle' );
			add_filter( 'ai1wm_notification_ok_email', 'Ai1wmde_Settings_Controller::notify_email' );

			// Add error notifications
			add_filter( 'ai1wm_notification_error_toggle', 'Ai1wmde_Settings_Controller::notify_error_toggle' );
			add_filter( 'ai1wm_notification_error_email', 'Ai1wmde_Settings_Controller::notify_email' );
		}
	}

	/**
	 * Export and import commands
	 *
	 * @return void
	 */
	public function ai1wm_commands() {
		if ( isset( $_REQUEST['dropbox'] ) ) {
			// Add export commands
			add_filter( 'ai1wm_export', 'Ai1wmde_Export_Dropbox::execute', 250 );
			add_filter( 'ai1wm_export', 'Ai1wmde_Export_Upload::execute', 260 );
			add_filter( 'ai1wm_export', 'Ai1wmde_Export_Retention::execute', 270 );
			add_filter( 'ai1wm_export', 'Ai1wmde_Export_Done::execute', 280 );

			// Add import commands
			add_filter( 'ai1wm_import', 'Ai1wmde_Import_Dropbox::execute', 20 );
			add_filter( 'ai1wm_import', 'Ai1wmde_Import_Download::execute', 30 );
			add_filter( 'ai1wm_import', 'Ai1wmde_Import_Settings::execute', 290 );
			add_filter( 'ai1wm_import', 'Ai1wmde_Import_Database::execute', 310 );

			// Remove export commands
			remove_filter( 'ai1wm_export', 'Ai1wm_Export_Download::execute', 250 );

			// Remove import commands
			remove_filter( 'ai1wm_import', 'Ai1wm_Import_Upload::execute', 5 );
		}
	}

	/**
	 * Check whether All in one WP Migration has been loaded
	 *
	 * @return void
	 */
	public function ai1wm_loaded() {
		if ( ! defined( 'AI1WM_PLUGIN_NAME' ) ) {
			if ( is_multisite() ) {
				add_action( 'network_admin_notices', array( $this, 'ai1wm_notice' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'ai1wm_notice' ) );
			}
		} else {
			if ( is_multisite() ) {
				add_action( 'network_admin_menu', array( $this, 'admin_menu' ), 20 );
			} else {
				add_action( 'admin_menu', array( $this, 'admin_menu' ), 20 );
			}

			// Dropbox settings
			add_action( 'admin_post_ai1wmde_dropbox_settings', 'Ai1wmde_Settings_Controller::settings' );

			// Dropbox revoke
			add_action( 'admin_post_ai1wmde_dropbox_revoke', 'Ai1wmde_Settings_Controller::revoke' );

			// Cron settings
			add_action( 'ai1wmde_dropbox_hourly_export', 'Ai1wm_Export_Controller::export' );
			add_action( 'ai1wmde_dropbox_daily_export', 'Ai1wm_Export_Controller::export' );
			add_action( 'ai1wmde_dropbox_weekly_export', 'Ai1wm_Export_Controller::export' );
			add_action( 'ai1wmde_dropbox_monthly_export', 'Ai1wm_Export_Controller::export' );

			// Picker
			add_action( 'ai1wm_import_left_end', 'Ai1wmde_Import_Controller::picker' );

			// Add export button
			add_filter( 'ai1wm_export_dropbox', 'Ai1wmde_Export_Controller::button' );

			// Add import button
			add_filter( 'ai1wm_import_dropbox', 'Ai1wmde_Import_Controller::button' );

			// Add import unlimited
			add_filter( 'ai1wm_max_file_size', array( $this, 'max_file_size' ) );
		}
	}

	/**
	 * Display All in one WP Migration notice
	 *
	 * @return void
	 */
	public function ai1wm_notice() {
		?>
		<div class="error">
			<p>
				<?php
				_e(
					'Dropbox extension requires <a href="https://wordpress.org/plugins/all-in-one-wp-migration/" target="_blank">All-in-One WP Migration plugin</a> to be activated. ' .
					'<a href="https://help.servmask.com/knowledgebase/install-instructions-for-dropbox-extension/" target="_blank">Dropbox extension install instructions</a>',
					AI1WMDE_PLUGIN_NAME
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Add links to plugin list page
	 *
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( $file == AI1WMDE_PLUGIN_BASENAME ) {
			$links[] = __( '<a href="https://help.servmask.com/knowledgebase/dropbox-extension-user-guide/" target="_blank">User Guide</a>', AI1WMDE_PLUGIN_NAME );
		}

		return $links;
	}

	/**
	 * Max file size callback
	 *
	 * @return string
	 */
	public function max_file_size() {
		return AI1WMDE_MAX_FILE_SIZE;
	}

	/**
	 * Register initial parameters
	 *
	 * @return void
	 */
	public function init() {
		// Set Dropbox Token
		if ( isset( $_GET['ai1wmde-token'] ) ) {
			update_option( 'ai1wmde_dropbox_token', $_GET['ai1wmde-token'] );

			// Redirect
			wp_redirect( network_admin_url( 'admin.php?page=site-migration-dropbox-settings' ) );
			exit;
		}

		// Set Purchase ID
		if ( ! get_option( 'ai1wmde_plugin_key' ) ) {
			update_option( 'ai1wmde_plugin_key', AI1WMDE_PURCHASE_ID );
		}
	}

	/**
	 * Register initial router
	 *
	 * @return void
	 */
	public function router() {
		// Export
		if ( current_user_can( 'export' ) ) {
			add_action( 'wp_ajax_ai1wmde_dropbox_account', 'Ai1wmde_Settings_Controller::account' );
		}

		// Import
		if ( current_user_can( 'import' ) ) {
			add_action( 'wp_ajax_ai1wmde_dropbox_folder', 'Ai1wmde_Import_Controller::folder' );
		}
	}
}
