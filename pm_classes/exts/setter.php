<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace php\module
 * - Autoload, application dependencies
 */
namespace php\module {
	/**
	 * Setter
	 */
	trait Setter {
		/**
		 * [__set description]
		 * @param string $property [description]
		 * @param unknown $val
		 */
		public final function __set ($property = '', $val){
			if (property_exists('php\module', $property)){
				if (method_exists ($this, $property))
					call_user_func_array ([$this, $property], [$val]);
				$this->$property = $val;
			}
		}
	}
}
