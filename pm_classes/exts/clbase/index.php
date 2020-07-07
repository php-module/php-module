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
     * clBase
     */
    trait clBase {
        /**
         * [$parsers description]
         * @var array
         * An array containg a list of additional parsers
         */
        private static $parsers = null;

        /**
         * [$configInitialized description]
         * @var boolean
         */
        private static $configInitialized = (
            false
        );

        /**
         * [$extensions description]
         * @var array
         */
        private static $extensions = array (
            '', '.php'
        );
        /**
         * [$exports]
         * @var boolean
         */
        public $exports;
        /**
         * @var module_cache
         * - Module cache is an array containg
         * - a list of whole the modules that've
         * - ever been imported inside the current
         * - application or even by ils
         * - it is used for avoiding reapeatitions
         * - of logic about how getting some module
         * - scope given a project structure and make
         * - ils php/module more fast when importing
         * - application dependencies or using them by
         * - php module function
         */
        private static $module_cache = [];
        /**
         * [$module_default_paths]
         * @var array
         */
        private static $module_default_paths = [
            '~' => '<rootDir>',
            '@root' => '<rootDir>',
            '/^((module(Root)?|)Dir)$/i' => ':module_root_dir'
        ];
        /**
         * [$module_configs]
         * @var array
         */
        private static $module_configs = [
            'extensions-file-path' => '~/config/modules',
            'php-modules-directories' => [],
            'php-module-paths' => [],
            'root-dir' => '/'
        ];
    }
}
