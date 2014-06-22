<?php

/**
 * Trigger Error
 */
class ErrorHandler {

	/**
	 * Setup
	 */
	public static function setup() {

		// Set Error Handler
		set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) {

			// This would happen if the @ operator was used
			if (error_reporting() == 0) { return; }

			// Get BackTrace
			$debug_backtrace = debug_backtrace();
print_r($debug_backtrace);
die();
			// Save to Error Log
			Error::save($errno, $errstr, $errfile, $errline, $debug_backtrace);
				
			// User Notices dont redirect or stop script
			if ($errno != E_USER_NOTICE) {

				// // If constants are defined and were on a live version of the website
				// if (LIVE_SITE) {

				// 	if (CurrentUser::in_admin_section()) {
				// 		$url_error = '/admin/errors/error_page.htm';
				// 		if (Ajax::started()) {
				// 			Ajax::ajax_redirect($url_error);	
				// 		} else {
				// 			redirect($url_error);
				// 		}
				// 	} else {
				// 		$url_error = '/errors/error_page.htm';
				// 		if (!file_exists(BASEDIR . $url_error)) { die('Oops! Our website has experienced an error. Our administrators have been notified. We apologize for the inconvenience'); }
				// 		if (Ajax::started()) {
				// 			Ajax::ajax_redirect($url_error);	
				// 		} else {
				// 			redirect($url_error);
				// 		}
				// 	}

				// // Development version of the website
				// } else {
			
					// Determine Error Title
					switch ($errno) {
						case E_ERROR:			$error_title = 'Fatal'; break;
						case E_WARNING:			$error_title = 'Warning'; break;
						case E_USER_WARNING:	$error_title = 'User Warning'; break;
						case E_USER_ERROR:		$error_title = 'User Error'; break;
						default:				$error_title = 'Unknown';
					}
			
					echo "<br><b>{$error_title}</b>: " . nl2br($errstr, TRUE) . " in <b>{$errfile}</b> on line <b>{$errline}</b>";
					echo '<br><br><b>Debug Trace:</b><br>';
					echo nl2br(Error::friendly_debug_backtrace($debug_backtrace), TRUE);
					exit();
			
				//}
				
			}

		}, error_reporting());

		// Catch Fatal Errors
		register_shutdown_function(function() {
			$last_error = error_get_last();
			if (in_array($last_error['type'], [E_ERROR, E_USER_ERROR, E_PARSE])) {
				echo 'fatal error - need to fix this';
				print_r($last_error);
				die();
				//self::handle($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line'], NULL);
			}
		});

	}
	
}