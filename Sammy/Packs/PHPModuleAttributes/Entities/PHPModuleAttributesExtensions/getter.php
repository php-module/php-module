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
   * Getter
   */
  trait Getter {

    private final function get_exports () {
      return $this->moduleInterrop ? $this : (
        $this->exports
      );
    }

    private final function get_default () {
      return $this->props ['#default'];
    }
  }
}
