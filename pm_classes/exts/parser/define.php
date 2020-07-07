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
     * Define
     */
    trait Define {
        /**
         * [DefineParser0 description]
         * @param string $prop [description]
         * @param [type] $val  [description]
         */
        public static function DefineParser ($parser = '', $val = null) {
            if (!(is_string($parser) && $val instanceof \Closure))
                return;

            $moduleParseRe = '/^(module:parse\s+)/i';
            $parserList = preg_split ('/\s+/',
                preg_replace ($moduleParseRe, '',
                    trim ($parser)
                )
            );

            $parserListCount = count (
                $parserList
            );

            for ($i = 0; $i < $parserListCount; $i++) {
                if ($i === 0) {
                    self::$parsers[ strtolower ((string)$parserList[$i]) ] = (
                        $val
                    );
                } else {
                    self::$parsers[ strtolower ((string)$parserList[$i]) ] = (
                        strtolower ((string)$parserList[ 0 ])
                    );
                }
            }
        }
    }
}
