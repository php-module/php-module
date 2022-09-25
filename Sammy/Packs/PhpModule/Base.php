<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\PhpModule
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Ysare
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\PhpModule {
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\PhpModule\Base')) {
  /**
   * @trait Base
   * Base internal trait for the
   * PhpModule module.
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
   */
  trait Base {
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
    private static $configInitialized = !true;

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
    private $exports;
    private $moduleInterrop = !true;
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
      '/^((module(Root)?|)Dir)$/i' => ':module_root_dir',
      '@HOME' => ':module_root_dir'
    ];
    /**
     * [$module_configs]
     * @var array
     */
    private static $module_configs = [
      'extensions-file-path' => '~/config/modules',
      'php-modules-directories' => [
        PHP_MODULE_ROOT_DIR . '/php-module/modules'
      ],
      'php-module-paths' => [
        PHP_MODULE_ROOT_DIR . '/php-module/modules'
      ],
      'root-dir' => '/'
    ];
  }}
}
