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

    private final function set_exports ($value, $backTrace = null) {
      if (!self::validTrace ($backTrace)) {
        $backTrace = debug_backtrace ();
      }

      $trace = $backTrace [ 0 ];
      $traceFile = !isset ($trace ['file']) ? '' : (
        $trace [ 'file' ]
      );

      if ( !$this->moduleFile ) {
        $this->moduleFile = (
          $traceFile
        );
      }

      if ($traceFile !== $this->moduleFile) {
        return;
      }

      $eData = array ( # exports datas
        'exports' => $value
      );

      self::$modulesExports [ $traceFile ] = (
        !isset (self::$modulesExports [ $traceFile ]) ? $eData : (
          self::$modulesExports [ $traceFile ]
        )
      );

      if ($this->moduleInterrop) {
        $this->props ['#default'] = $value;
      } else {
        $this->exports = self::$modulesExports [ $traceFile ][
          'exports'
        ];
      }
    }
	}
}
