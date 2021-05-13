<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\PHPModule\Path
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\PHPModule\Path {
  use php\module as phpmodule;
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!class_exists('Sammy\Packs\PHPModule\Path\Base')){
  /**
   * @class Base
   * Base internal class for the
   * PHPModule\Path module.
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
  class Base {

    public final function resolve () {
      if (func_num_args() >= 1) {
        $funcArgs = func_get_args ();
        $defaultTrace = debug_backtrace ();

        $arg0 = $funcArgs [ 0 ];
        $arg1 = !isset ($funcArgs [ 1 ]) ? null : (
          $funcArgs [ 1 ]
        );

        if (is_array ($arg0) && self::areStr ($arg0)) {
          if (!phpmodule::validTrace ($arg1)) {
            $arg1 = $defaultTrace;
          }

          $i = 0;
          $ds = DIRECTORY_SEPARATOR;
          $baseDir = dirname (
            $arg1[0][ 'file' ]
          );


          if (phpmodule::definedPath ($arg0[ 0 ])) {
            $i = 1;
            $baseDir = phpmodule::readPath (
              $arg0[ 0 ]
            );
          }

          $resolvedPath = preg_replace ('/((\\\|\/)+)$/', '', $baseDir) . (
            $ds . join ($ds, array_slice ($arg0, $i, count ($arg0)))
          );

          return ($resolvedPath);
        } elseif (self::areStr ($funcArgs)) {
          return $this->resolve ($funcArgs,
            $defaultTrace
          );
        }
      }
    }

    public final function join () {
      $args = func_get_args ();
      # Make sure the given arguments
      # list only contains strings
      # before joning
      if (self::areStr ($args)) {
        $path = join (DIRECTORY_SEPARATOR,
          $args
        );

        return phpmodule::readPath (
          $path
        );
      }
    }

    private static final function areStr ($datas) {
      foreach ($datas as $data) {
        if (!is_string ($data)) {
          return false;
        }
      }
      return count ($datas) >= 1;
    }
  }}

  $module->exports = (
    new Base
  );
}
