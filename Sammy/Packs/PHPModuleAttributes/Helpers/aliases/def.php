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
if ( !function_exists ('def') ) {
/**
 * @function def
 * Base internal function for the
 * Sami\Cli module command 'def'.
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
function def () {
  return call_user_func_array ('php\\def',
    [func_get_args (), debug_backtrace ()]
  );
}}
