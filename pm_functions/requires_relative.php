<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace php
 * - Autoload, application dependencies
 * ---
 * THE CURRENT FILE CONTAINS THE MAIN PHP
 * MODULE FUNCTION; WICH PROVIDES TO THE
 * PROJECT A WAY FOR IMPORTING THE MODULES
 * INSIDE IT AND SET UP THE PROVIDED FUNCTIONALITY
 * FORM THE IMPORTED PHP MODULE.
 * IT IS A FEATURE PROVIDED BY PHP MODULE
 * LIBRARY, WICH BRINGS THE EXTENSIBILY TO
 * THE PHP PROJECTS IN ORDER MAKING ITS
 * MODULES AND FUNCTIONALITIES MORE REUSABLE
 * THAN THEY SHOULD BE.
 * ---
 * @php-module version 1.0.6
 * @edited at 20/05/2020 03:50
 * ---
 * Sammy
 * ---
 */
namespace php;
/**
 * [requires description]
 * @param  string $__module__
 *  The imported module reference,
 *  it should be a string containg an
 *  absolute file path to the required
 *  php module.
 *  When importing a module by reference,
 *  php module will firstly look for the
 *  exact given reference in order getting
 *  the first result from that, in case of
 *  not getting any it will considere other
 *  alternatives that should help php module
 *  finding the module by the given reference
 *  including relative paths that should norma
 *  -lly start by a one or more dot chars; or
 *  it should be an absolute path refering to
 *  a ils path that should containg the module
 *  original directory and the, getting the mo
 *  -dule coreaccording to the found extension
 *   file by thephp module extensions order.
 * @param array $trace
 * @param array $args
 *  The sent arguments to the module scope
 * @return unknown
 */
function requires_relative ($__module__, $trace = null){
	$args = array_slice (func_get_args(), 2,
		func_num_args ()
	);

	$backTraceDatasSentFromFuncArguments = (boolean) (
		is_array ($trace) &&
		isset($trace[0])  &&
		isset($trace[0][
			'file'
		])
	);

	if (!$backTraceDatasSentFromFuncArguments)
		$trace = debug_backtrace();

	$alts = module::ImportAlternateDirectories (
		dirname ($trace[0]['file']), $__module__
	);

	$altsLen = count ($alts);

	for ($i = 0; $i < $altsLen; $i++) {
		$ext = module::shouldImport (
			$alts[$i]
		);

		if ( is_string ($ext) ) {
            return call_user_func_array ('php\requires',
                array_merge ([$alts[$i] . $ext], $args)
            );
		}
	}
}
