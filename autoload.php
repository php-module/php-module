<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\php\module
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Samil
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\php\module {
  use Sammy\Packs\PhpModule\Helper;
	/**
	 * Root
	 * Absoluete path to the current directory
	 * whish phpmodule will considere as its
	 * root directory
	 */
	defined ('PHP_MODULE_ROOT_DIR') or define ('PHP_MODULE_ROOT_DIR', __DIR__);
  defined ('PHP_MODULE_NAMESPACE') or define ('PHP_MODULE_NAMESPACE', 'Sammy\Packs\php\module');

  $autoloadFile = __DIR__ . '/vendor/autoload.php';

  $phpModuleHelpersDirPath = join (DIRECTORY_SEPARATOR, [
    PHP_MODULE_ROOT_DIR,
    'Sammy',
    'Packs',
    'PHPModuleAttributes',
    'Helpers'
  ]);

  if (is_file ($autoloadFile)) {
    include_once $autoloadFile;
  }

	Helper::autoloadFiles ($phpModuleHelpersDirPath);
  #
  # import the index file containg the php-module
  # core global function to be used when importing
  # a module.
  #
	include_once (__DIR__ . '/index.php');
}
