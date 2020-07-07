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
	 * PHPModuleConfigure
	 */
	trait PHPModuleConfigure {
		/**
		 * [phpModuleConfigure description]
		 * @param  array  $phpModuleConfigs
		 * @return null
		 */
		private static final function phpModuleConfigure () {
			if (!(func_num_args() >= 1))
				return;

			$phpModuleConfigs = func_get_arg (
				0
			);

			if (!(is_array($phpModuleConfigs)))
				return;

			foreach ($phpModuleConfigs as $prop => $val) {
				if (!(is_string($prop) || is_int($prop)))
					continue;

				$moduleParseRe = '/^(module:parse(r|)\s+)/i';
				if (preg_match ($moduleParseRe, $prop)) {
					self::DefineParser ($prop, $val);
				} else {
					self::CommandExec (is_int($prop) ? $val : $prop,
						$val
					);
				}
			}
		}

        private static final function sanitizePropName ($property) {
            $property = preg_replace ('/\s+/', '-',
                trim ($property)
            );

            $property = preg_replace_callback ('/[A-Z]/', function ($match) {
                return ('-' . strtolower ($match [0]));
            }, $property);

            $property = preg_replace ('/-{2,}/', '-',
                preg_replace ('/^(-+)/', '',
                    preg_replace ('/(-+)$/', '', $property)
                )
            );

            return strtolower ( $property );
        }

        /**
         * [config set a configuration for the php module class]
         * @param  string|array $propert(y|ies)
         * @param  mixed $value
         * @return null
         */
        public static final function config ($property = null, $value = null) {
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
            } elseif (is_string($property)) {
                $property = self::sanitizePropName (
                    $property
                );
                /**
                 * [$conf PHP Module Configurations List]
                 * @var array
                 */
                $conf = self::$module_configs;

                $invalidConfigProperty = ( boolean ) (
                    isset ($conf [ $property ]) && (
                        gettype ($conf [ $property ]) !== gettype ($value)
                    )
                );

                if ( $invalidConfigProperty ) {
                    return;
                }

                if (isset ($conf[ $property ]) && is_array ($conf[ $property ])) {
                    $value = array_merge ($conf [ $property ], $value);
                }

                self::$module_configs [ $property ] = (
                    $value
                );
            }
        }

        public static final function getConfigDatas () {
            return array_merge ( [], self::$module_configs );
        }

        public static final function getConfigData () {
            $propertyList = func_get_args ();
            /**
             * [$dataList]
             * @var array
             */
            $dataList = [];

            $propertyListLen = count (
                $propertyList
            );

            for ($i = 0; $i < $propertyListLen; $i++) {
                $property = self::sanitizePropName (
                    (string)$propertyList [ $i ]
                );

                if (isset (self::$module_configs [ $property ])) {
                    $dataList [ $property ] = (
                        self::$module_configs [ $property ]
                    );
                } else {
                    $dataList [ $property ] = null;
                }
            }

            return $dataList;
        }

        public static final function getConfigDataArray () {
            $propertyList = func_get_args ();
            /**
             * [$dataList]
             * @var array
             */
            $dataList = [];

            $propertyListLen = count (
                $propertyList
            );

            for ($i = 0; $i < $propertyListLen; $i++) {
                $property = self::sanitizePropName (
                    (string)$propertyList [ $i ]
                );

                if (isset (self::$module_configs [ $property ])) {
                    array_push ($dataList,
                        self::$module_configs [
                            $property
                        ]
                    );
                } else {
                    array_push ( $dataList, null );
                }
            }

            return $dataList;
        }

        public static final function getConfig ($property) {
            $configData = self::getConfigData ($property);

            $property = self::sanitizePropName (
                $property
            );

            return !isset ($configData [$property]) ? null : (
                $configData [$property]
            );
        }
	}
}
