<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2011, Kristopher Wilson
 * @package         php-gedcom 
 * @license         http://php-gedcom.kristopherwilson.com/license
 * @link            http://php-gedcom.kristopherwilson.com
 * @version         SVN: $Id$
 */

/**
 * Autoloader to autoload class files as needed
 */
function phpGedcomAutoLoader($name)
{
    if(!substr($name, 0, 7) == 'PhpGedcom\\')
        return;
    
    $name = str_replace('\\', '/', substr($name, 7)) . '.php';
    
    if(file_exists(__DIR__ . '/' . $name))
        require_once(__DIR__ . '/' . $name);
}

/**
 * Register the autoloader
 */
spl_autoload_register('phpGedcomAutoLoader');


