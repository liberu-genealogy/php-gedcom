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

namespace PhpGedcom\Writer;

/**
 *
 */
class SourRef
{
    /**
     * @param \PhpGedcom\Record\SourRef $sour
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\SourRef &$sour, $level)
    {
        $output = "";
        $_sour = $sour->getSour();
        if(!empty($_sour)){
            $output.=$level." SOUR ".$_sour."\n";
        }
        $level++;
        // protected $_text    = null;
        $_text = $sour->getText();
        if(!empty($_text)){
            $output.=$level." TEXT ".$_text."\n";
        }
        // protected $_note    = array();
        $note = $sour->getNote();
        if($note && count($note) > 0){
            foreach($note as $item){
                $_convert = \PhpGedcom\Writer\NoteRef::convert($item, $level);
                $output.= $_convert;
            }
        }
        // protected $_data    = null;
        $_data = $sour->getData();
        if($_data){
            $_convert = \PhpGedcom\Writer\Sour\Data::convert($_data, $level);
            $output.= $_convert;
        }
        // protected $_page setPage 
        $_page = $sour->getPage();
        if(!empty($_page)){
            $output.=$level." PAGE ".$_page."\n";
        }
        // protected $_even    = null;
        $_even = $sour->getData();
        if($_even){
            $_convert = \PhpGedcom\Writer\SourRef\Even::convert($_even, $level);
            $output.= $_convert;
        }
        // protected $_quay   
        $_quay = $sour->getQuay();
        if(!empty($_quay)){
            $output.=$level." QUAY ".$_quay."\n";
        }
        // protected $_obje    = array();
        // This is not defined in parser.
        return $output;
    }
}
