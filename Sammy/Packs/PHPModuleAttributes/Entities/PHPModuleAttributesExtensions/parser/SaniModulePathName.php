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
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('php\module\SaniModulePathName')){
  /**
   * @trait SaniModulePathName
   * Base internal trait for the
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
   * \Samils\dir_boot ('./exts');
   */
  trait SaniModulePathName {
    /**
     * [SaniModulePathName]
     * @param string $name
     * -
     * Sanitize module path name
     * to have a clean name, without
     * any special chars matching
     * to: /[\\\\\/\.]/
     */
    public static final function SaniModulePathName ($name = '') {
      # C:\usres\Sammy\.\foo\bar\baz.php
      # C:\users\Sammy\..\..\foo\bar.php
      $re = '/\.*(\/|\\\)+/';

      return preg_replace ($re, '',
        $name
      );
    }
  }}
}
