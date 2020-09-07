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
 * @license         MIT
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom\Writer\Indi;

/**
 *
 */
class Fams
{
    /**
     * @param \PhpGedcom\Record\Indi\Fams $attr
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Indi\Fams &$fams, $level = 0)
    {
        $output = '';
        // NAME
        $_fams = $fams->getFams();
        if(empty($_fams)){
            return $output;
        }
        $output.= $level." FAMS @".$_fams."@\n";
        // level up
        $level++;
        
        // note
        $note = $fams->getNote();
        if(!empty($note) && count($note) > 0){
            foreach($note as $item){
                $_convert = \PhpGedcom\Writer\NoteRef::convert($item, $level);
                $output.=$_convert;
            }
        }

        return $output;
    }
}
