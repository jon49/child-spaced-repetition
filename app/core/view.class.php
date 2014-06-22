<?php

/**
 * View
 */
class View {

	/**
	 * Object Variables
	 */
	private $path;
	private $vars = Array();

	/**
	 * Constructor (Set Path)
	 */
	public function __construct($path) {
		$this->path = $path;
	}

	/**
	 * Magic Setter
	 */
	public function __set($name, $value) {
		if ($value instanceof PageView) { 
			$this->{$name} = $value;
			$this->vars[$name] = $this->{$name};
		} else if ($name == 'content') {
			$this->vars['content'] .= $value; 
		} else {
			$this->vars[$name] = $value;
		}
	}

	public function __get($name) {
		if (isset($this->{$name})) {
			return $this->{$name};
		} else {
			return $this->vars[$name];
		}
	}

	/**
	 * Render
	 */
	public function __toString() {

		// Filename must exist
		if (!file_exists($this->path)) {
			trigger_error('Path [' . $this->path . '] doesn\'t exist', E_USER_ERROR);
		}

		// Turn Vars into unique variables
		extract($this->vars);

		// Get View Output
		ob_start();
		require($this->path);
		$output = ob_get_contents();
		ob_end_clean();

		// Return Output
		return $output;

	}

}