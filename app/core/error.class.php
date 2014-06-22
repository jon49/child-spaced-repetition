<?php

/*

NOTICE
- Gets logged in database
- Script continues working


WARNING
- User is taken to an error page
- Gets logged in database

ERROR
- User is taken to an error page
- Gets logged in database
- Email gets sent to admin

*/

/**
 * Handles Errors
 */
class Error extends Model {

	
	public static $table	= 'error_log';
	public static $table_id	= 'error_log_id';
	

	/****************************************
	*    SAVE                               *
	*****************************************/

	/**
	 * Save an error log record
	 */
	public static function save($error_level, $message, $file, $line_no, $backtrace) {
	
		// Current User Object
		global $current_user;

		// Determine Error Level Type
		switch ($error_level) {
	
			case E_ERROR:
				$error_level_id = ERROR_LEVEL_FATAL;
				break;
	
			case E_NOTICE:
			case E_USER_NOTICE:
				$error_level_id = ERROR_LEVEL_NOTICE;
				break;
				
			case E_WARNING:
			case E_USER_WARNING:
				$error_level_id = ERROR_LEVEL_WARNING;
				break;
			
			case E_USER_ERROR:
				$error_level_id = ERROR_LEVEL_ERROR;
				$email_error = TRUE;
				break;
				
			default:
				$error_level_id = ERROR_LEVEL_OTHER;
				
		}

		// Prepare SQL Values
		$sql_values = Array();
		$sql_values['error_level_id'] 	= $error_level_id;
		$sql_values['php_error_level'] 	= $error_level;
		$sql_values['message'] 			= $message;
		$sql_values['backtrace'] 		= serialize(self::friendly_debug_backtrace($backtrace, FALSE));
		$sql_values['url'] 				= $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
		$sql_values['parameters'] 		= $_SERVER['QUERY_STRING'];
		$sql_values['line_no'] 			= $line_no;
		$sql_values['file'] 			= $file;
		$sql_values['referer'] 			= $_SERVER['HTTP_REFERER'];
		$sql_values['user_id'] 			= $current_user->user_id;
		$sql_values['ip_address'] 		= get_user_ip();
		$sql_values['datetime_added'] 	= 'NOW()';
		
		// Apply In Quotes Logic
		$sql_values = db::array_in_quotes($sql_values);

		// Insert
		$results = db::insert('error_log', $sql_values);
		$insert_id = $results->insert_id;
		
		// Email Error
		if ($email_error) {
			self::email_error(self::get_error_log_as_message($insert_id));
			Sql::update('error_log', 'email_sent', '1', "WHERE error_log_id = '{$insert_id}'");
		}
		
	}
	
	
	/****************************************
	*    EMAIL ERROR                        *
	*****************************************/
	
	/**
	 * Email error to admin
	 */
	public static function email_error($error_message) {

		// SQL
		$sql = "
			SELECT user.email
			FROM user
			JOIN admin USING (user_id)
			WHERE admin.email_errors = 1
			AND user.active = 1
			";

		// Execute
		$results = db::execute($sql);
		
		// Loop Rows
		while ($row = $results->fetch_assoc()) {
			$email_que = new EmailQue();
			$email_que->volume_control = FALSE;
			try {$email_que->send($row['email'], $error_message, 'Error On: ' . $_SERVER['HTTP_HOST']);}
			catch (Exception $e) {
				die('System Error');
			}
		}	
	
	}

	/**
	 * Turn an error log record into an HTML message
	 */
	public static function get_error_log_as_message($error_log_id) {
	
		// SQL Results
		$results = Sql::select('error_log', NULL, "WHERE error_log_id = '{$error_log_id}'");
		
		// Get Row
		$row = $results->fetch_assoc();
		
		// Make Message
		return self::make_error_message($row);
	
	}

	/**
	 * Make Error Message
	 */
	public static function make_error_message($row) {
		
		// Turn Error Log Record into string message
		foreach ($row as $key => $value) {
			$message .= '<b>' . $key . '</b><br>' . nl2br($value) . '<br><br>';
		}
		
		return $message;
		
	}


	/****************************************
	*    UTILITIES                          *
	*****************************************/

	/**
	 * Turn the default php backtrace info a more friendly one
	 */
	public static function friendly_debug_backtrace($debug_backtrace, $html = TRUE) {
	
		// Reverse Order
		$reverse_debug_backtrace = array_reverse($debug_backtrace);
		
		// Store each file called
		$trace = Array();

		// Loop Debug Backtrace
		$i = 0;
		foreach($reverse_debug_backtrace as $key => $trace_frag) {
			$i++;
			$trace_frag['file'] = replace_null($trace_frag['file'], 'File Unknown');
			$trace_frag['line'] = replace_null($trace_frag['line'], 'Unknown');
			$trace_frag['function'] = replace_null($trace_frag['function'], 'Function Unknown');
			$trace[] = "<b>{$i}</b>: Called: {$trace_frag['class']}{$trace_frag['type']}{$trace_frag['function']}() in <b>{$trace_frag['file']}</b> on line <b>{$trace_frag['line']}</b>";
			if ($trace_frag['function'] == 'trigger_error') {break;}
		}

		// Turn into string
		$trace = implode("\n", $trace);
	
		// Return
		return $html ? $trace : strip_tags($trace);
	
	}
	
}

?>
