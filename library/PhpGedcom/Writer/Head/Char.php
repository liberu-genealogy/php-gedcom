<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Xiang Ming <wenqiangliu344@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Xiang Ming
 * @package         php-gedcom 
 * @license         GPL-3.0
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom\Writer\Head;

/**
 *
 */
class Char
{
    /**
     * @param \PhpGedcom\Record\Head\Char $char
     * @param string $format
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Head\Char &$char, $level)
    {
        $output ="";
        // char
        $_char = $char->getChar();
        if($_char){
            $output.=$level." CHAR ".$_char."\n";
        }else{
            return $output;
        }

        // level up
        $level++;
        // VERS
        $vers = $char->getVersion();
        if($vers){
            $output.=$level." VERS ".$vers."\n";
        }

        
        return $output;
    }
}
