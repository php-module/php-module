<?php
/**
 * @class module
 * @version 1.0
 */
namespace php {
    if (!class_exists('php\\module')) {
    /**
     * @class module
     */
    final class module {
        use module\Parser;
        use module\Define;
        use module\Config;
        use module\InitCl;
        use module\Setter;
        use module\clBase;
        use module\Paths;

        # Exts
        use module\AlternateDirectories;
        use module\PHPModuleConfigure;
        use module\ImportByAlternates;
        use module\PHPModulePathsConf;
        use module\PHPModulePathsRead;
        use module\RootImportConfigs;
        use module\ExtensionSetters;
        use module\CommandExecuter;
        use module\Extender;

        public static final function setModuleCache ($module, $module_abs) {
        }
        /**
         * @method exports
         * - Method used to export any object
         * - for the  current module
         */
        public final function exports ($module_object) {
        }
    }}
}
