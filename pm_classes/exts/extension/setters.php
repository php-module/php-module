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
     * ExtensionSetters
     */
    trait ExtensionSetters {
        /**
         * [setExtensions description]
         * @param array $extensions
         */
        static final function setExtensions ($extensions = array ()) {
            if (!(is_array($extensions) && $extensions))
                return;

            $extensionsCount = count (
                $extensions
            );

            for ($i = 0; $i < $extensionsCount; $i++) {
                if (!(is_string($extensions[$i]) && $extensions[$i]))
                    continue;

                $extension = strtolower (
                    $extensions[$i]
                );

                if (!in_array($extension, self::$extensions)) {
                    array_push (self::$extensions, $extension);
                }
            }
        }
        /**
         * [setExtension description]
         * @param string $extension
         */
        static final function setExtension ($extension = '') {
            return self::setExtensions ([ $extension ]);
        }
    }
}
