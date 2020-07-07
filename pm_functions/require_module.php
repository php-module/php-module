<?php
/**
 * @version 2.0
 * @author Sammy
 * --- --- --- --- --- --- --- --- ---
 * @keywords Samils, ils, php framework
 * --- --- --- --- --- --- --- --- ---
 * @namespace php
 * - Autoload, application dependencies
 * ---
 * THE CURRENT FILE CONTAINS THE MAIN PHP
 * MODULE FUNCTION; WICH PROVIDES TO THE
 * PROJECT A WAY FOR IMPORTING THE MODULES
 * INSIDE IT AND SET UP THE PROVIDED FUNCTIONALITY
 * FROM THE IMPORTED PHP MODULE.
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

include_once Root . '/pm_classes/module.php';
include_once Root . '/pm_functions/requires' . (
	'_relative.php'
);
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
 *  -dule core according to the found extension
 *   file by the php module extensions order.
 * @param  array $args
 *  The sent arguments to the module scope
 * @return unknown
 */
function requires ($__module__ = null) {
	$funcArgs = func_get_args ();

	$moduleCompleteReference = ( boolean ) (
		is_array ($__module__) &&
		isset ($__module__[ 'module' ])
	);

	if ( $moduleCompleteReference ) {

		$args = !isset ($__module__['args']) ? [] : (
			$__module__['args']
		);

		if (isset ($__module__['trace'])) {
			$backTrace = $__module__['trace'];
		}

		$__module__ = $__module__[
			'module'
		];
	} else {
		/**
		 * [$args description]
		 * @var array
		 */
		$args = array_slice ($funcArgs, 1,
			count( $funcArgs )
		);
	}

	if (!isset ($backTrace)) $backTrace = (
		debug_backtrace()
	);
	# Having the module name or reference in the
	# $__module__ variable and the module arguments
	# in the $args variable; get the module inside
	# a configured directory in order importing it
	# sending the given arguments to the same's scope;
	# After doing that, cache the module refernce in order
	# avoiding to import it after doing that (if the environment
	# configurations are set to cache the module reference when
	# importing them from php module library).
	# ---
	# Firstly, make sure the given module name
	# is a strng and it is not an empty string
	# or a null value.
	# Stop the flux otherwise.
	if (!(is_string($__module__) && !empty($__module__))) {
		# Stop and return a null value on
		# condition that the given value
		# module name is not a valid string
		# or is an empty string or a null value
		# wich should not be converted to a module
		# name.
		return;
	}
	# Reads a possible path being used in the module
	# reference; before doing it, make sure the Saml
	# class is already declared and should be used in
	# the current context;
	# Otherwise, it should ignore this possibility on
	# condition that the ils paths should be readed form
	# the current context directly.

	# Read the path inside the module refernce in case
	# of containing a path at the beggining of the reference
	# string that should be used as the reference for getting
	# the module source.
	# If found some path, re evaluate the $__module__ variable
	# in order having inside it the full reference for the
	# required module.
    $__module__ = module::readPath (
        # The given module name or reference
        # for being imported by php module scope
        # context.
        # ---
        # The current variable value should change
        # in case of containg a valid and known path
        # inside of it; making sure that the real module
        # reference according to that path is taken and
        # held in order being used to import the module
        # instance.
        $__module__, $backTrace
    );
	# In case of having an real and valid
	# absolute module file path, php module
	# should just import the refernce and
	# return the module instance (exported data)
	# ---
	# Try getting the absoulute
	# path in order finding the
	# current php module
	# ---
	# Verify if the given module reference points
	# to a file in the project directory, in order
	# using the file absolute path to make reference
	# to the module core in case of existing;
	# otherwise, php module should considere using of
	# relative paths or an absolute path with a reference
	# for a ils path that should point to the module directory.
	if (!is_file($__module__)) {
		# On condition that the given module reference
		# is not an absolute file path, php module has
		# to look for other ways in order getting the
		# module absolute path for using it.
		# ---
		# Check if the given module reference is a relative
		# path according to the back trace file, when calling
		# php module from; it that case, use the 'requires_relative'
		# function inside the php namespace to import the given module
		# core to the php module caller.
		if (preg_match('/^\.+/', $__module__)){
			# trace
			# Whole the php stack trace for the
			# current call contained in an array
			# stored in the '$trace' variable that
			# should be passed as an argument for
			# the 'requires_relative' function called
			# bellow.
			# ---
			# Get the trace story in order getting the
			# origin file directory of the current call
			# of php module and look for the relative
			# reference  from there.
			$trace = debug_backtrace();
			# Make a relative seek from the origin file
			# directory absolute path.
			# ---
			# Sending the given module reference and the
			# stack trace array, and also the arguments
			# sent to the required module scope.
			return call_user_func_array ('php\\requires_relative', array_merge (
				[$__module__, $trace],
				# The imported module reference,
				# it should be a string containg an
				# absolute file path to the required
				# php module.
				$args
			));
		} else {
			# pmd
			# php module directories
			# A key in the modules configurations array
			# inside the application configurations
            # ---
            # A list of directories where php module should
            # find a global module insi de the current project
            # directory
			$dirs = module::getConfig ('php-modules-directories');

			if (!(is_array($dirs) && $dirs))
				return;

			foreach ($dirs as $i => $dir) {

				$alts = module::ImportAlternateDirectories (
					module::readPath ($dir), $__module__
				);

				foreach ($alts as $in => $alt){
					$ext = module::shouldImport ($alt);

					if (is_string ($ext)) {
                        return call_user_func_array ('php\\requires',
                            array_merge ([$alt . $ext], $args)
                        );
					}
				}
			}
		}

		return;
	}
	# module
	# php module base class instance, an object containing the
	# core of php module base class, used to store datas about
	# the exportation from the required php module.
	$module = new module();
	# argsCount
	# - Arguments Count
	# Number of the sent arguments to the
	# required php module scope.
	# wish should help when iterating them.
	$argsCount = count (
		# A list of sent arguments
		# to the module scope
		$args
	);
	# Map the arguments list to declare one per one
	# in order making them assecible fom the module
	# scope, as php variables named in the 'arg' patt
	# -ern: arg0, arg1 and so on.
	# Creating this is a way for letting whole the arguments
	# values assecible inside of the php module scope and pro
	# -viding a short reference to them.
	for ($i = 0; $i < $argsCount; $i++) {
		# VariableName
		# Following the 'arg' pattern
		# for variable naming by ending
		# with the argument index in the
		# args array sent when requiring
		# the current php module by the
		# sent reference.
		$variableName = 'arg' . (
			# The current argument index
			# in the '$args' array sent
			# when requiring the current
			# php module by the sent
			# module reference from the
			# function arguments list.
			$i
		);
		# Dinamically declaring the variable
		# with the a generated name above acc
		# -ording to the 'arg' pattern, that
		# should now be assecible from the php
		# module scope.
		$$variableName = (
			# The current argument in the '$args'
			# list according to the '$i' iterator
			# ; wich should contain the value for
			# the argument in the '$i' position in
			# the '$args' array sent to the php module
			# scope.
			$args[ $i ]
		);
	}
	# PHPModuleParser
	# - php module parser
	# A previously registrered closure
	# that should be able to parse the
	# current file type (by extension).
	# The 'php\module::parser' function
	# will return a null value or a closure
	# according to a basic verification about
	# existing or not the parser for the current
	# module file type (extension) from the php
	# module extensions.
	# ---
	# php module parser'll be used to parse and
	# read the current module source.
	$PHPModuleParser = module::parser (
		# Required module file extension
		# ---
		# Send it to verify if the module
		# file has a known extension that
		# should parsed by a different parser
		# tha the php module base.
		pathinfo ( $__module__, PATHINFO_EXTENSION )
	);
	# Before even trying to use the found
	# [php module parser], make sure it is
	# not a null value of an empty string or
	# array;
	# in order avoiding to parse the required
	# php module with a non reference of parser.
	# ---
	# Skip if the php module parser is not a
	# valid parser for php module library core.
	if ( $PHPModuleParser ) {
		# Verify if the found php module parser
		# is an instance of the Closure class and
		# should be invoked directly in order having
		# the php module file parsed into the required
		# file and it should return the wanted value to
		# the php module caller.
		if ($PHPModuleParser instanceof \Closure) {
			# Directly invoke the php module parser
			# on condition that the found parser for
			# the current module is an instance of the
			# php Closure class.
			# Sending the required php module absolute
			# path as an argument for the parser.
			return call_user_func_array ( $PHPModuleParser,
				# The imported module reference,
				# it should be a string containg an
				# absolute file path to the required
				# php module.
				array ($__module__)
			);
		} elseif (is_array($PHPModuleParser)) {
			# In case of being an array, it should
			# be a reference for a static method or
			# an instance method for a given class
			# reference in the first position in the
			# '$PHPModuleParser' array;
			# Hence, php module'll try invoking a static
			# method in case of it existing in the given
			# class reference in case of the fisrt position
			# in the php module parser array containing a
			# string.
			if (is_string($PHPModuleParser[0])) {
				# The next call'll resolve with a php module
				# file instance that should be the response
				# for the required information when importing
				# the current php module.
				# ---
				# Given a static method for being used as the
				# parser for the current file type (extension)
				# , make a static call for the reference method
				# sent to the php module configurations.
				return forward_static_call_array ($PHPModuleParser,
					# The imported module reference,
					# it should be a string containg an
					# absolute file path to the required
					# php module.
					array ($__module__)
				);
			} else {
				# The next call'll resolve with a php module
				# file instance that should be the response
				# for the required information when importing
				# the current php module.
				# ---
				# Call the given reference for a method acording
				# to the array '$PHPModuleParser' datas.
				return call_user_func_array ($PHPModuleParser,
					# The imported module reference,
					# it should be a string containg an
					# absolute file path to the required
					# php module.
					array ($__module__)
				);
			}
		}
	}

	# include the module file inside the
	# function scope in order making the
	# 'module' object assecible from it
	if (is_file ($__module__)) {
		require ($__module__);
		return (
			$module->exports
		);
	}
}
