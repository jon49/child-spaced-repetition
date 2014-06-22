<?php

/**
 * App Controller
 */
class AppController extends BaseController {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		ob_start();
	}

	/**
	 * Get View
	 */
	protected function set_view() {
		$this->view = new View(BASEDIR . '/app/views/main.php');
		$this->view->primary_header = new View(BASEDIR . '/app/views/primary_header.php');
	}

	/**
	 * Render
	 */
	protected function render() {

		// Catch Output Buffer
		$this->view->main_content = trim(ob_get_contents());
		ob_end_clean();

		// Render View
		parent::render();
	}

}