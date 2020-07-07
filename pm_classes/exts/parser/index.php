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
     * Parser
     */
    trait Parser {
        /**
         * [parser description]
         * @param  string $extension
         * @return Closure
         */
        public static function parser ($extension = '') {
            if (!is_string($extension))
                return;

            # Turn extension value into
            # lower case
            $extension = '.' . preg_replace ('/^\./', '', strtolower (
                (string)$extension
            ));

            if (isset(self::$parsers[$extension])) {
                $parser = self::$parsers [ $extension ];

                if ($parser instanceof \Closure) {
                    return $parser;
                } else {
                    return !is_string($parser) ? null : (
                        !isset(self::$parsers[strtolower((string)($parser))]) ? null : (
                            self::parser (strtolower((string)($parser)))
                        )
                    );
                }
            }
        }
    }
}
