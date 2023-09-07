<?php

use Sammy\Packs\PhpModule\Helper;

$module->exports = [
  /**
   * php module json parser
   */
  'module:parser .json' => function (string $fileAbsolutePath) {
    # Make sure the given file name reference
    # is a valid reference for a file containing
    # some information that should be used any how
    if (is_file ($fileAbsolutePath)) {
      # Parse the file content from a
      # json string to an array and
      # return its content for the
      # caller
      return Helper::jsonFile ($fileAbsolutePath);
    }
  }
];
