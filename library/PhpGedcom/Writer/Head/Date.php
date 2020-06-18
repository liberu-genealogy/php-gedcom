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
class Date
{
    /**
     * @param \PhpGedcom\Record\Head\Date $date
     * @param string $format
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Head\Date &$date, $level)
    {
        $output = "";
        $_date = $date->getDate();
        if($_date){
            $output .=$level." DATE ".$_date."\n";
        }else{
            return $output;
        }

        // level up
        $level++;
        // Time
        $time = $date->getTime();
        if($time){
            $output.=$level." TIME ".$time."\n";
        }

        return $output;
    }
}
