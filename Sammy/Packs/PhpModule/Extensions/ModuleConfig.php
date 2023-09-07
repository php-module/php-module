<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\PhpModule\Extensions
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
namespace Sammy\Packs\PhpModule\Extensions {
  use function php\requires;
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\PhpModule\Extensions\ModuleConfig')) {
  /**
   * @trait ModuleConfig
   * Base internal trait for the
   * PhpModule\Extensions module.
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
  trait ModuleConfig {
    /**
     * [initialize_config description]
     * @return null
     */
    public static function initialize_config () {
      if (self::$configInitialized) {
        return;
      }

      self::init ();

      $extensionsFilePaths = self::getConfig ('extensions-file-path');

      if (!is_array ($extensionsFilePaths)) {
        $extensionsFilePaths = [
          $extensionsFilePaths
        ];
      }

      foreach ($extensionsFilePaths as $extensionsFilePath) {
        $phpModuleConfigs = requires ($extensionsFilePath);

        self::phpModuleConfigure ($phpModuleConfigs);
      }


      # Set the config initialized
      # property as true to avoid
      # repeating this action.
      self::$configInitialized = true;
    }

    /**
     * [phpModuleConfigure description]
     * @param  array  $phpModuleConfigs
     * @return null
     */
    private static function phpModuleConfigure () {
      if (!(func_num_args () >= 1)) {
        return;
      }

      $phpModuleConfigs = func_get_arg (0);

      if (!(is_array ($phpModuleConfigs))) {
        return;
      }

      foreach ($phpModuleConfigs as $prop => $val) {
        if (!(is_string ($prop) || is_int ($prop))) {
          continue;
        }

        $moduleParseRe = '/^(module:parse(r|)\s+)/i';

        if (preg_match ($moduleParseRe, $prop)) {
          self::DefineParser ($prop, $val);
        } else {
          $command = is_int ($prop) ? $val : $prop;

          self::CommandExec ($command, $val);
        }
      }
    }

    /**
     * @method string sanitize config property name
     */
    private static function sanitizePropName ($property) {
      $property = preg_replace ('/\s+/', '-', trim ($property));

      $property = preg_replace_callback ('/[A-Z]/', function ($match) {
          return ('-' . strtolower ($match [0]));
      }, $property);

      $dashesRe = '/(^(-+)|(-+)$)/';

      $property = preg_replace ($dashesRe, '', $property);
      $property = preg_replace ('/-{2,}/', '-', $property);

      return strtolower ($property);
    }

    public static function getConfigDatas () {
      return array_merge ([], self::$module_configs);
    }

    /**
     * [config set a configuration for the php module class]
     * @param  string|array $propert(y|ies)
     * @param  mixed $value
     * @return null
     */
    public static function config ($property = null, $value = null) {
      /**
       * Make a general configuration
       * if '$property' is an array
       */
      if (is_array ($property)) {
        /**
         * Iterate the given array and configure
         * each property inside it for the current
         * class context
         */
        foreach ($property as $key => $value) {
          self::config ($key, $value);
        }
      } elseif (is_string ($property)) {
        $property = self::sanitizePropName ($property);
        /**
         * [$conf PHP Module Configurations List]
         * @var array
         */
        $conf = self::$module_configs;

        $invalidConfigProperty = (boolean)(
          isset ($conf [$property]) &&
          gettype ($conf [$property]) !== gettype ($value)
        );

        if ($invalidConfigProperty) {
          return;
        }

        if (isset ($conf [$property]) && is_array ($conf [$property])) {
          $value = array_merge ($conf [$property], $value);
        }

        self::$module_configs [$property] = $value;
      }
    }

    /**
     * @method mixed get confiuration data
     */
    public static function getConfigData () {
      $propertyList = func_get_args ();

      /**
       * [$dataList]
       * @var array
       */
      $dataList = [];

      $propertyListLen = count ($propertyList);

      for ($i = 0; $i < $propertyListLen; $i++) {
        $property = self::sanitizePropName ((string)$propertyList [$i]);
        $dataList [$property] = null;

        if (isset (self::$module_configs [$property])) {
          $dataList [$property] = self::$module_configs [$property];
        }
      }

      return $dataList;
    }

    public static function getConfigDataArray () {
      $propertyList = func_get_args ();
      /**
       * [$dataList]
       * @var array
       */
      $dataList = [];

      $propertyListLen = count ($propertyList);

      for ($i = 0; $i < $propertyListLen; $i++) {
        $property = self::sanitizePropName ((string)$propertyList [$i]);

        if (isset (self::$module_configs [$property])) {
          array_push ($dataList, self::$module_configs [$property]);
        } else {
          array_push ($dataList, null);
        }
      }

      return $dataList;
    }

    public static function getConfig ($property) {
      $configData = self::getConfigData ($property);

      $property = self::sanitizePropName ($property);

      if (isset ($configData [$property])) {
        return $configData [$property];
      }
    }
  }}
}
