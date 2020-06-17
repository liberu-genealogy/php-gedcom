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

namespace PhpGedcom\Writer;

/**
 *
 */
class Chan
{
    /**
     * @param \PhpGedcom\Record\Chan $note
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Chan &$chan, $level)
    {
        $output.=$level." CHAN \n";
        // level up
        $level++;
        // DATE
        $_date = $chan->getDate();
        if(!empty($_date)){
            $output.=$level." DATE ".$_date."\n";
        }
        // TIME
        $_time = $chan->getDate();
        if(!empty($_time)){
            $output.=$level." DATE ".$_time."\n";
        }
        // $_note = array()
        $_note = $chan->getNote();
        if(!empty($_note) && count($_note) > 0){
            foreach($_note as $item){
                $_convert = \PhpGedcom\Writer\NoteRef::convert($item, $level);
                $output.=$_convert;
            }
        }
        return $output;
    }
}
