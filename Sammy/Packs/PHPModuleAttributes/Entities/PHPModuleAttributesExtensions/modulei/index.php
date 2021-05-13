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
  use Closure;
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('php\module\InstanceFragmentBase')){
  /**
   * @trait InstanceFragmentBase
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
  trait InstanceFragmentBase {
    /**
     * [$props Properties]
     * @var array
     * -
     * A list of property names
     * declared inside the current
     * php module context
     */
    private $props = array (
      '#default' => null
    );

    private $moduleFile;

    private static $modulesExports = array ();
    private static $moduleContexts = array ();
    private static $id = 0;
    /**
     * [setProp]
     * - Set a property
     * - inside the module context
     * @param string $prop
     * @param mixed $value
     */
    public final function setProp ($prop = '', $value = null) {
      if ( $this->pvn ($prop) ) {
        $traceDatas = self::getTraceDatas (func_get_args(),
          debug_backtrace ()
        );

        $traceFile = $traceDatas ['file'];

        if ( !$this->moduleFile ) {
          $this->moduleFile = $traceFile;
        }

        self::register ($this->moduleFile);

        $prop = strtolower ( $prop );

        if (!isset(self::$modulesExports [ $this->moduleFile ]['props'])) {
          self::$modulesExports [ $this->moduleFile ]['props'] = [];
        }

        $props = self::$modulesExports [ $this->moduleFile ]['props'];

        $propertyDefined = ( boolean ) (
          isset ($props [ $prop ]) &&
          gettype ($props[$prop]) === gettype ( $value )
        );

        if ( !$propertyDefined ) {
          $props = self::$modulesExports [ $this->moduleFile ][
            'props'][$prop] = $value;
        }
      }
    }
    /**
     * [getProp]
     * - Get a property value
     * - inside the module context
     * - by the key
     * @param  string $porp
     * @return mixed
     */
    public final function getProp ($prop = null) {
      if (is_string ($prop) && $this->pvn ($prop)) {

        $traceDatas = self::getTraceDatas (func_get_args(),
          debug_backtrace ()
        );

        $traceFile = $traceDatas ['file'];

        if ( !$this->moduleFile ) {
          $this->moduleFile = (
            $traceFile
          );
        }

        self::register ($this->moduleFile);

        $prop = strtolower ( $prop );

        $props = self::$modulesExports [ $this->moduleFile ][
          'props'
        ];

        return !isset ($props[ $prop ]) ? null : (
          $props [ $prop ]
        );
      }
    }



    private static function getTraceDatas ($funcArgs, $defaultTrace) {
      $n = count ( $funcArgs );
      $backTrace = !($n >= 1) ? $defaultTrace : (
        $funcArgs [ -1 + $n ]
      );

      if (!self::validTrace ($backTrace)) {
        $backTrace = $defaultTrace;
      }

      $trace = $backTrace [ 0 ];
      $traceFile = !isset ($trace ['file']) ? '' : (
        $trace [ 'file' ]
      );

      return [
        $backTrace, 'file' => $traceFile
      ];
    }


    /**
     * [__set]
     */
    public final function __set ($prop = '', $value = null) {
      $traceDatas = self::getTraceDatas (func_get_args(),
        debug_backtrace ()
      );

      $property = 'set_' . strtolower (
        (string)( $prop )
      );

      if (method_exists ($this, $property)) {
        return call_user_func_array ([$this, $property],
          array_merge ([ $value ], [ $traceDatas[0] ])
        );
      }


      return call_user_func_array ([$this, 'setProp'],
          array_merge (func_get_args (), [ $traceDatas[0] ])
      );
    }
    /**
     * [__get]
     */
    public final function __get ($prop = '') {
      $traceDatas = self::getTraceDatas (func_get_args(),
        debug_backtrace ()
      );

      $property = 'get_' . strtolower (
        (string)( $prop )
      );

      if (method_exists ($this, $property)) {
        return call_user_func ([$this, $property]);
      }

      return call_user_func_array ([$this, 'getProp'],
          array_merge (func_get_args (), [ $traceDatas[0] ])
      );
    }
    /**
     * [__invoke]
     * @return mixed
     */
    public final function __invoke () {
      $constructorAlts = preg_split ('/\s+/',
        '__init __initialize initialize'
      );

      foreach ($constructorAlts as $constructorAlt) {
        $altProp = $this->getProp ($constructorAlt);

        if ($altProp instanceof Closure) {
          $methClosure = Closure::bind ($altProp,
            $this, self::class
          );

          return call_user_func_array ($methClosure,
            func_get_args ()
          );
        }
      }
    }
    /**
     * [__call]
     * @param  string $meth
     * @param  array  $args
     * @return mixed
     */
    public final function __call ($meth = '', $args = []) {
      $methProp = $this->getProp ($meth);

      if ($methProp instanceof Closure) {
        $methClosure = Closure::bind ($methProp,
          $this, self::class
        );

        return call_user_func_array ($methClosure,
          $args
        );
      }
    }
    /**
     * [__isset]
     * - verify if a property name
     * - is declared in the module
     * - context
     * @param  string $prop
     * @return boolean
     */
    public final function __isset ($prop = null) {
      $prop = strtolower ((string)($prop));
      return ( boolean ) (isset ($this->props [ $prop ]));
    }

    public final function moduleInterrop () {
      return ( boolean ) ($this->moduleInterrop);
    }

    private final function pvn ($prop) {
      return ( boolean ) (
        is_string ($prop)
        && !empty ($prop) &&
        preg_match ('/^([a-zA-Z0-9_]+)$/',
          $prop
        )
      );
    }

    private static final function register ($traceFile) {
      if (!isset (self::$modulesExports [ $traceFile ])) {
        self::$modulesExports [ $traceFile ] = array (
          'exports' => null,
          'props' => array ()
        );
      }
    }

    public static function id () {
      return (int)(++self::$id);
    }

    public final function __initialize_module_register () {
      if ( !$this->moduleFile ) return;

      $moduleFile = $this->moduleFile;

      $context = $this->moduleInterrop ? $this : $this->exports;

      if (!isset (self::$moduleContexts [ $moduleFile ])) {
        self::$moduleContexts [ $moduleFile ] = $context;
      }
    }

    public final function __getModuleContext ($module) {
      if (isset (self::$moduleContexts [ $module ])) {
        return self::$moduleContexts [ $module ];
      }
    }

    public static final function fileAbsolutePath ($filePath) {
      $filePathSlices = preg_split ('/(\\\|\/)+/', $filePath);
      $filePathSlicesCount = count ($filePathSlices);
      $fileAbsolutePath = '';

      for ($i = 0; $i < $filePathSlicesCount; $i++) {
        $filePathSlice = $filePathSlices [ $i ];

        if (preg_match ('/^(\.+)$/', $filePathSlice)) {
          if ($filePathSlice === '..')
            $fileAbsolutePath = dirname ($fileAbsolutePath);
        } else {
          $fileAbsolutePath .= DIRECTORY_SEPARATOR . $filePathSlice;
        }
      }

      return preg_replace ('/^(\\\|\/)/', '', $fileAbsolutePath);
    }

  }}
}
