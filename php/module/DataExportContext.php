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
  use php\module;
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!class_exists ('php\module\DataExportContext')) {
  /**
   * @class DataExportContext
   * Base internal class for the
   * module module.
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
   * -
   */
  class DataExportContext {
    /**
     * [$module The Module Object]
     * @var php\module
     */
    private $module;
    /**
     * [__construct]
     * @param module $module
     */
    public final function __construct (module $module) {
      $this->module = ($module);
    }

    public final function __set ($prop = '', $value = null) {
      return call_user_func_array ([$this->module, 'exports'],
        array_merge (func_get_args (), [debug_backtrace()])
      );
    }
  }}
}
