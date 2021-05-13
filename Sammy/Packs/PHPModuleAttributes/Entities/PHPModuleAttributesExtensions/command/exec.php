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
	 * CommandExecuter
	 */
	trait CommandExecuter {
		/**
		 * [CommandExec description]
		 * @param string $cmd
		 * @param [type] $value
		 */
		private static final function CommandExec ($cmd = '', $val) {
			$cmdRe = '/^(module:([a-zA-Z0-9_]+)\s*)/i';
			# Get the command name in order
			# executing it to get the right
			# result for configuring the 
			# php-module extensions
			if (preg_match ( $cmdRe , $cmd )) {
				$cmdArgs = trim(preg_replace ($cmdRe, '', 
					$cmd
				));

				$cmdNameCore = preg_replace ('/^(module:)/i', '', 
					$cmd
				);

				preg_match ('/^([a-zA-Z0-9_]+)/', $cmdNameCore, 
					$cmdName
				);

				$methodRef = [ static::class, 
					'_conf' . trim(strtolower ($cmdName[0]))
				];

				if (call_user_func_array('method_exists', $methodRef)) {
					forward_static_call_array($methodRef, array (
						$cmdArgs, $val
					));
				}
			}
		}
	}
}
