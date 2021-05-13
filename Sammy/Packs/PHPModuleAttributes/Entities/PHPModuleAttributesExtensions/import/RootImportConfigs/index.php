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
     * RootImportConfigs
     */
    trait RootImportConfigs {

        private static function path2re ($path = '') {
            $re = '/[\/\^\$\[\]\{\}\(\)\\\\.]/';
            return '/^(' . preg_replace_callback ($re, function ($match) {
                return '\\' . $match[0];
            }, (string)$path) . ')/';
        }
        /**
         * [getModuleRootDir description]
         * @param  string $absPath
         * @return string|null
         */
        public static final function getModuleRootDir ($abs = '') {
            # In order making use of any rule or settings
            # in the current ilsApplication, php module'll
            # make sure that the Applicatio COnfigurations
            # are all done to use them.
            # In case of not done, it'll stop because of
            # missing informations.
            # ---
            # ConfigurationsDone
            # A boolean value indicating if the ils application
            # configurations are ready and done.

            # Stop the function flux in case of missing
            # informations about the ils application
            # configurations;
            # This is done to avoid having errors when
            # trying to use them late.

            # php module configurations
            # a constant in the Configure
            # class containing the php
            # module basic configurations
            # set inside.
            $phpModuleConf = self::getConfigDatas ();
            # pmd
            # php module directories
            # A key in the modules configurations array
            # inside the application configurations
            $pmd = 'php-modules-directories';
            # Before going the script on, make sure
            # the php module configurations are already
            # set; and the php module configurations such
            # as the php module directories are set there.
            # Return a null value otherwise.
            if (!(is_array($phpModuleConf) && isset($phpModuleConf[ $pmd ]))) {
                # Stop the function flux on
                # condition that the php module
                # configurations are not set yet.
                return;
            }

            $dirs = $phpModuleConf[ $pmd ];
            $moduleDatas = [];

            foreach ( $dirs as $dir ) {

                $absDir = self::path2re ( preg_replace ('/\/+/',
                    DS, self::readPath ( $dir )
                ));

                if (@preg_match ($absDir, $abs)) {
                    $modulePath = preg_replace ($absDir, '',
                        $abs
                    );

                    $splModulePath = preg_split ('/(\/|\\\)+/',
                        preg_replace ('/^(\/|\\\)+/', '', $modulePath)
                    );

                    /**
                     * [$moduleDatas description]
                     * @var array
                     */
                    $moduleDatas = array (
                        'moduleBaseDir' => self::readPath ( $dir ),
                        'moduleRootDir' => $splModulePath [0]
                    );

                    break;
                }
            }

            return join (DS, array_values ($moduleDatas));
        }

        public static function validTrace ($backTrace) {
            return ( boolean ) (
                is_array ($backTrace) &&
                isset ($backTrace [ 0 ]) &&
                is_array ($backTrace [ 0 ]) &&
                isset ($backTrace [ 0 ]['file']) &&
                is_string ($f = $backTrace [ 0 ][ 'file' ])
            );
        }

        public static final function root_dir ($backTrace = []) {

            if ( !self::validTrace ($backTrace) ) {
                return;
            }

            $dir = dirname ($backTrace[0]['file']);

            return forward_static_call_array (
                [self::class, 'getModuleRootDir'], [$dir]
            );
        }
    }
}
