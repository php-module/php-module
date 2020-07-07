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
    if (!trait_exists('php\module\PHPModulePathsRead')){
    /**
     * @trait PHPModulePathsRead
     * Base internal trait for the
     * module module.
     * -
     * This is (in the ils environment)
     * an instance of the php module,
     * wich should contain the module
     * core functionalities that should
     * be extended.
     */
    trait PHPModulePathsRead {
        /**
         * [readPath Read a PHP Module Path inside a given absolute file path]
         * @param  string $absPath [Absolute Path]
         * @return string
         */
        public static final function readPath ($absPath = '', $args = []) {
            /**
             * [$backTrace BackTrace]
             * @var arrau
             */
            $backTrace = func_get_arg (-1 + func_num_args ());

            if ( !self::validTrace ($backTrace) ) {
                $backTrace = debug_backtrace ();
            }

            if (!(is_string ($absPath) && $absPath)) {
                return;
            }

            $re = '([\\\\\/]+)';
            $ds = DIRECTORY_SEPARATOR;

            $absPath = preg_replace ( $re, $ds,
                $absPath
            );

            $absPathSlices = preg_split ($re, $absPath);

            if (self::definedPath ($absPathSlices [ 0 ])) {
                /**
                 * [$path]
                 * @var string
                 */
                $path = self::readPathValue ($absPathSlices [ 0 ], $args,
                    $backTrace
                );

                $absPath = preg_replace ('/' . $re . '$/', '', $path) . $ds . (
                    join ($ds, array_slice ($absPathSlices, 1,
                        count ( $absPathSlices )
                    ))
                );
            } else {
                /**
                 * [$path]
                 * @var string
                 */
                $path = self::readPathValueFromProp ($absPathSlices [ 0 ],
                    $backTrace
                );

                $absPath = preg_replace ('/' . $re . '$/', '', $path) . $ds . (
                    join ($ds, array_slice ($absPathSlices, 1,
                        count ( $absPathSlices )
                    ))
                );
            }

            return preg_replace ( $re, $ds, $absPath );
        }
        /**
         * [readPathValue description]
         * @param  string $pathPrefix
         * @return string
         */
        private static final function readPathValue ($pathPrefix = '', $args = []) {
            /**
             * [$paths description]
             * @var array
             */
            $paths = self::paths ();
            $re = '/((\\\|\/)+)$/';

            $backTrace = func_get_arg (-1 + func_num_args ());

            if ( !self::validTrace ($backTrace) ) {
                $backTrace = debug_backtrace ();
            }

            $args = !is_array ($args) ? [] : (
                $args
            );

            if (is_string ($paths [ $pathPrefix ])) {
                /**
                 * [$pathValue final path value]
                 * @var string
                 */
                $pathValue = preg_replace ( $re, '',
                    $paths [ $pathPrefix ]
                );
            } elseif ($paths [ $pathPrefix ] instanceof \Closure) {
                $val = preg_replace ( $re, '',
                    $paths [ $pathPrefix ]
                );
                /**
                 * [$pathValue final path value]
                 * @var string
                 */
                $pathValue = call_user_func_array ($paths [ $pathPrefix ],
                    array_merge ([$val], $args)
                );
            }

            return self::readPath ($pathValue, $args, $backTrace);
        }

        private static final function re ($str) {
            return ( boolean ) (
                preg_match ('/^\//', $str) &&
                preg_match ('/(\/([i])?)$/', $str)
            );
        }
        /**
         * [readPathValueFromProp description]
         * @param  string $pathPrefix
         * @return string
         */
        private static final function readPathValueFromProp ($pathPrefix = '') {
            $re = '/^(<([a-zA-Z0-9_\-]+)\s*\/?>)$/';
            $re1 = '/^:module_(.+)/i';
            /**
             * [$backTrace BackTrace]
             * @var arrau
             */
            $backTrace = func_get_arg (-1 + func_num_args ());

            if ( !self::validTrace ($backTrace) ) {
                $backTrace = debug_backtrace ();
            }
            /**
             * Verify matching of '$re' inside the
             * '$pathPrefix' string and save the match
             * results in the '$match' variable
             */
            if (preg_match ($re, $pathPrefix, $match)) {
                $property = trim ($match [ 2 ]);

                $propertyValue = self::getConfig ($property);

                if ( is_string ($propertyValue) ) {
                    return self::readPath ($propertyValue, [], $backTrace);
                } else {
                    $paths = self::paths ();

                    foreach ($paths as $path => $sufix) {
                        if (!self::re ($path)) {
                            continue;
                        }
                        /**
                         * Try Matching
                         */
                        if ( @preg_match ($path, $property, $match) ) {
                            return self::readPath ($sufix, [$match], $backTrace);
                        }
                    }
                }
            } elseif (preg_match ($re1, $pathPrefix, $match)) {
                /**
                 *
                 */
                if (method_exists (self::class, $match [ 1 ])) {
                    return forward_static_call_array (
                        [self::class, $match [ 1 ]], [
                            $backTrace, $pathPrefix, $match
                        ]
                    );
                }
            } elseif (preg_match ('/^<+$/', trim($pathPrefix), $match)) {
                $dir = dirname ($backTrace[0]['file']);

                $pathPrefixLen = strlen ($match[0]);

                for ($i = 0; $i < $pathPrefixLen; $i++) {
                    $dir = dirname ($dir);
                }

                return $dir;
            }

            return $pathPrefix;
        }
    }}
}
