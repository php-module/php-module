<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace php
 * - Autoload, application dependencies
 */
namespace php {
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists ('php\\def')) {
  /**
   * @function def
   * Base internal function for the
   * php\module library helper 'def'.
   * -
   * This is (in the current environment)
   * an instance of the php module,
   * wich should contain the module
   * core functionalities that should
   * be extended.
   * -
   * For extending the module, just create
   * an 'exts' directory in the module directory
   * and boot it by using the ils directory boot.
   */
  function def ($property, $value) {
    if (is_array ($property) && func_num_args () >= 2) {
      list ($arguments, $backTrace) = func_get_args ();
    } elseif (is_string ($property)) {
      $arguments = func_get_args ();
      $backTrace = debug_backtrace ();
    }

    if (!module::validTrace ($backTrace)) {
      $backTrace = debug_backtrace ();
    }

    $definingFromModuleContext = ( boolean ) (
      isset ($backTrace [2]) &&
      is_array ($backTrace [2]) &&
      isset ($backTrace [2]['args']) &&
      is_array ($backTrace [2]['args']) &&
      isset ($backTrace [2]['args'][0]) && (
        $backTrace [2]['args'][0] instanceof module
      )
    );

    if ( $definingFromModuleContext ) {
      /**
       * [$module]
       * @var php\module
       */
      $module = @$backTrace [2]['args'][0];
      /**
       * setProp
       */
      return call_user_func_array ([$module, 'setProp'], $arguments);
    }
  }}
}
