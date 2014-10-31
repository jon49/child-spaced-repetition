<?php

/**
 * Default View
 */
class DefaultView extends View {
	public function __construct() {
		
		// Set Main View
		parent::__construct(ROOT . '/app/templates/main.php');
		
		// Set Sub Views
		$this->primary_header = new View(ROOT . '/app/templates/primary_header.php');
		
	}
}