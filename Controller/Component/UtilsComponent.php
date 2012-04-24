<?php
/**
 * Copyright 2007-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2007-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Utils Plugin
 *
 * Utils Util Component
 *
 * @package utils
 * @subpackage utils.controllers.components
 */
class UtilsComponent extends Component {

/**
 * Controller
 *
 * @var mixed $controller
 */ 
	public $Controller; 

/**
 * Startup Callback
 *
 * @param object Controller object
 */
	public function startup(Controller $controller) {
		$this->Controller =& $controller;
	}

/**
 * Clean html string using Cleaner helper
 *
 * @param string $text
 * @param string $settings
 * @return string
 */
	public function cleanHtml($text, $settings = 'full') {
		App::uses('CleanerHelper', 'Utils.View/Helper');
		App::uses('View', 'View');
		$cleaner = & new CleanerHelper(new View);
		return $cleaner->clean($text, $settings);
	}

}
