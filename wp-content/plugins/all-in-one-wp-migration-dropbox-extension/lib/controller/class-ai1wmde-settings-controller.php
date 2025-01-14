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

class Ai1wmde_Settings_Controller {

	public static function index() {
		$model = new Ai1wmde_Settings;

		$dropbox_backup_schedules = get_option( 'ai1wmde_dropbox_cron', array() );
		$last_backup_timestamp    = get_option( 'ai1wmde_dropbox_timestamp', false );

		$last_backup_date = $model->get_last_backup_date( $last_backup_timestamp );
		$next_backup_date = $model->get_next_backup_date( $dropbox_backup_schedules );

		Ai1wm_Template::render(
			'settings/index',
			array(
				'backups'                  => get_option( 'ai1wmde_dropbox_backups', false ),
				'dropbox_backup_schedules' => $dropbox_backup_schedules,
				'notify_ok_toggle'         => get_option( 'ai1wmde_dropbox_notify_toggle', false ),
				'notify_error_toggle'      => get_option( 'ai1wmde_dropbox_notify_error_toggle', false ),
				'notify_email'             => get_option( 'ai1wmde_dropbox_notify_email', get_option( 'admin_email', false ) ),
				'last_backup_date'         => $last_backup_date,
				'next_backup_date'         => $next_backup_date,
				'ssl'                      => get_option( 'ai1wmde_dropbox_ssl', true ),
				'token'                    => get_option( 'ai1wmde_dropbox_token', false ),
				'total'                    => get_option( 'ai1wmde_dropbox_total', false ),
			),
			AI1WMDE_TEMPLATES_PATH
		);
	}

	public static function settings() {
		$params = stripslashes_deep( $_POST );

		// Dropbox update
		if ( isset( $params['ai1wmde_dropbox_update'] ) ) {
			$model = new Ai1wmde_Settings;

			// Cron update
			if ( ! empty( $params['ai1wmde_dropbox_cron'] ) ) {
				$model->set_cron( (array) $params['ai1wmde_dropbox_cron'] );
			} else {
				$model->set_cron( array() );
			}

			// Set SSL mode
			if ( ! empty( $params['ai1wmde_dropbox_ssl'] ) ) {
				$model->set_ssl( 0 );
			} else {
				$model->set_ssl( 1 );
			}

			// Set number of backups
			if ( ! empty( $params['ai1wmde_dropbox_backups'] ) ) {
				$model->set_backups( (int) $params['ai1wmde_dropbox_backups'] );
			} else {
				$model->set_backups( 0 );
			}

			// Set size of backups
			if ( ! empty( $params['ai1wmde_dropbox_total'] ) && ! empty( $params['ai1wmde_dropbox_total_unit'] ) ) {
				$model->set_total( (int) $params['ai1wmde_dropbox_total'] . trim( $params['ai1wmde_dropbox_total_unit'] ) );
			} else {
				$model->set_total( 0 );
			}

			// Set notify ok toggle
			$model->set_notify_ok_toggle( isset( $params['ai1wmde_dropbox_notify_toggle'] ) );

			// Set notify error toggle
			$model->set_notify_error_toggle( isset( $params['ai1wmde_dropbox_notify_error_toggle'] ) );

			// Set notify email
			$model->set_notify_email( trim( $params['ai1wmde_dropbox_notify_email'] ) );
		}

		// Redirect to settings page
		wp_redirect( network_admin_url( 'admin.php?page=site-migration-dropbox-settings' ) );
		exit;
	}

	public static function revoke() {
		$params = stripslashes_deep( $_POST );

		// Dropbox logout
		if ( isset( $params['ai1wmde_dropbox_logout'] ) ) {
			$model = new Ai1wmde_Settings;
			$model->revoke();
		}

		// Redirect to settings page
		wp_redirect( network_admin_url( 'admin.php?page=site-migration-dropbox-settings' ) );
		exit;
	}

	public static function account() {
		$model = new Ai1wmde_Settings;
		if ( ( $account = $model->get_account() ) ) {
			echo json_encode( $account );
		}
		exit;
	}

	public static function notify_ok_toggle() {
		$model = new Ai1wmde_Settings;
		if ( ( $notify_ok_toggle = $model->get_notify_ok_toggle() ) ) {
			return $notify_ok_toggle;
		}
	}

	public static function notify_error_toggle() {
		$model = new Ai1wmde_Settings;
		if ( ( $notify_error_toggle = $model->get_notify_error_toggle() ) ) {
			return $notify_error_toggle;
		}
	}

	public static function notify_email() {
		$model = new Ai1wmde_Settings;
		if ( ( $notify_email = $model->get_notify_email() ) ) {
			return $notify_email;
		}
	}
}
