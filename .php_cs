<?php

/**
 * php-cs-fixer - configuration file
 */

use Symfony\CS\FixerInterface;

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->ignoreVCS(true)
    ->notName('.php_cs')
    ->notName('AllTests.php')
    ->notName('composer.*')
    ->notName('*.phar')
    ->notName('*.ico')
    ->notName('*.ttf')
    ->notName('*.gif')
    ->notName('*.swf')
    ->notName('*.jpg')
    ->notName('*.png')
    ->notName('*.exe')
    ->notName('wpnxm-software-registry.php')
    ->exclude('vendor')
    ->exclude('registry') // registry is JSON pretty printed in its own short format
    ->exclude('nbproject') // netbeans project files
    ->in(__DIR__);

return Symfony\CS\Config\Config::create()
    // use SYMFONY_LEVEL:
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    // and extra fixers:
    ->fixers(array(
        'align_equals',
        'align_double_arrow',
        'concat_with_spaces',
        'ordered_use',
        'strict',
        'strict_param',
        //'short_array_syntax'
    ));