<?php
/**
 *
 */

/**
 * Autoloader to autoload class files as needed
 */
function phpGedcomAutoLoader($name)
{
    if(!substr($name, 0, 7) == 'Gedcom\\')
        return;
    
    $name = str_replace('\\', '/', substr($name, 7)) . '.php';
    
    if(file_exists(__DIR__ . '/' . $name))
        require_once(__DIR__ . '/' . $name);

}

/**
 * Register the autoloader
 */
spl_autoload_register('phpGedcomAutoLoader');


