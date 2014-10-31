<?php

/**
 * Base Controller
 */
abstract class BaseController {

	/**
	 * Controller View
	 */
	public $view;

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