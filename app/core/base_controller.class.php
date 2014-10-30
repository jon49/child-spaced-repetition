<?php

/**
 * Base Controller
 */
abstract class BaseController {

	/**
	 * Controller View
	 */
	public $view;
	abstract protected function set_views();

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_views();
	}

	/**
	 * Render
	 */
	protected function render() {
		echo $this->view;
	}

	/**
	 * Destructor (Render)
	 */
	public function __destruct() {
		$this->render();
	}

}