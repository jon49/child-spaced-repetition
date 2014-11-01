<?php

/**
 * App Controller
 */
class AppController extends BaseController {

	/**
	 * Constructor
	 */
	public function __construct() {
		ob_start();
		$this->view = new DefaultView();
		$this->init();
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