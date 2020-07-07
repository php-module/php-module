<?php
/**
 * @version 2.0
 * @author Sammy
 * 
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\Sami\Cli
 * - Autoload, application dependencies
 */
if ( !function_exists ('requires') ) {
/**
 * @function requires
 * Base internal function for the
 * Sami\Cli module command 'requires'.
 * -
 * This is (in the ils environment)
 * an instance of the php module,
 * wich should contain the module 
 * core functionalities that should
 * be extended.
 * -
 * For extending the module, just create
 * an 'exts' directory in the module directory
 * and boot it by using the ils directory boot.
 */
function requires () {
	return call_user_func_array ('php\\requires', 
		array_merge (func_get_args (),  debug_backtrace ())
	);
}}
