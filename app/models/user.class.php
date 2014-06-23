<?php

/**
 * User
 */
class User extends Model {

	/**
	 * Save User
	 */
	public static function save($input) {

		// Note that Server Side validation is not being done here


		// Prepare SQL Values
		$sql_values = [
			'user_id' => $input['user_id'],
			'first_name' => $input['first_name'],
			'last_name' => $input['last_name'],
			'email' => $input['email'],
			'password' => $input['password']
		];

		// Ensure values are encompased with quote marks
		$sql_values = db::array_in_quotes($sql_values);

		// Insert
		$results = db::insert('user', $sql_values);
		
		// Get Recent Insert ID
		$user_id = $results->insert_id;

		// Return a new instance of this user as an object
		return new User($user_id);

	}

}