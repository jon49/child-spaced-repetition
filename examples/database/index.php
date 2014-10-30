<?php

// Init
include($_SERVER['DOCUMENT_ROOT'] . '/app/core/initialize.php');



/****************************************
  INSERT Statements
*****************************************/

/**
 * To make an INSERT Statement, start off by making an associative array where
 * the keys of the array are your table fields and the values are the field's values
 */

$insert_values = [
	'first_name' => 'John',
	'last_name' => 'SmithYYY',
	'email' => 'john@smith.com',
	'datetime_added' => 'NOW()'
];

$insert_values = db::auto_quote($insert_values, ['datetime_added']);

// Insert
$results = db::insert('user', $insert_values);

// The results object given to us after the insert will have certain
// qualities that we might want, such as the recent Insert ID
$user_id = $results->insert_id;


