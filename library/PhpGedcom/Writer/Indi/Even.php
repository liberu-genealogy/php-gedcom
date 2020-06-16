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

namespace PhpGedcom\Writer\Indi;

/**
 *
 */
class Even
{
    /**
     * @param \PhpGedcom\Record\Indi\Even $even
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Indi\Even &$even, $level = 0)
    {
        $output = "";

        // $_attr;
        $attr = $even->getAttr();
        if(!empty($attr)){
            $output.=$level." EVEN ".$attr."\n";
        }else{
            $output = $level." EVEN\n";
        }
        $level++;

        // $type;
        $type = $even->getType();
        if(!empty($type)){
            $output.=$level." TYPE ".$type."\n";
        }

        // $date;
        $date = $even->getDate();
        if(!empty($date)){
            $output.=$level." DATE ".$date."\n";
        }
        
        // Plac
        $plac = $even->getPlac();
        if(!empty($plac)){
            $_convert = \PhpGedcom\Writer\Indi\Even\Plac::convert($plac, $level);
            $output.=$_convert;
        }

        // $caus;
        $caus = $even->getCaus();
        if(!empty($caus)){
            $output.=$level." CAUS ".$caus."\n";
        }

        // $age;
        $age = $even->getAge();
        if(!empty($age)){
            $output.=$level." AGE ".$age."\n";
        }

        // $addr
        $addr = $even->getAddr();
        if(!empty($addr)){
            $_convert = \PhpGedcom\Writer\Addr::convert($addr, $level);
            $output.=$_convert;
        }

        // $phon = array()
        $phon = $even->getPhon();
        if(!empty($phon) && count($phon) > 0){
            foreach($phon as $item){
                $_convert = \PhpGedcom\Writer\Phon::convert($item, $level);
                $output.=$_convert;
            }
        }
        // $agnc
        $agnc = $even->getAgnc();
        if(!empty($agnc)){
            $output.=$level." AGNC ".$agnc."\n";
        }

        // $ref = array();
        // This is not in parser

        // $obje = array();

        // $sour = array();

        // $note = array();

        // Record\Chan

        return $output;
    }
}
