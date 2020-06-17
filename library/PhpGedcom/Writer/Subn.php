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
class Subn
{
    /**
     * @param \PhpGedcom\Record\Subn $note
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Subn &$subn)
    {
        $level = 0;
        $output = "";
        $_subn = $refn->getSubn();
        if(empty($_refn)){
            return $output;
        }else{
            $output.=$level." SUBN ".$_subn."\n";
        }
        // level up
        $level++;

        // SUBM
        $subm = $subn->getSubm();
        if(!empty($subm)){
            $output.=$level." SUBM ".$subm."\n";
        }

        // FAMF
        $famf = $subn->getFamf();
        if(!empty($famf)){
            $output.=$level." FAMF ".$famf."\n";
        }

        // TEMP
        $temp = $subn->getTemp();
        if(!empty($temp)){
            $output.=$level." TEMP ".$temp."\n";
        }

        // ANCE
        $ance = $subn->getAnce();
        if(!empty($ance)){
            $output.=$level." ANCE ".$ance."\n";
        }
        
        // DESC
        $desc = $subn->getDesc();
        if(!empty($desc)){
            $output.=$level." DESC ".$desc."\n";
        }
        // ORDI
        $ordi = $subn->getOrdi();
        if(!empty($ordi)){
            $output.=$level." ORDI ".$ordi."\n";
        }

        // RIN
        $rin = $subn->getRin();
        if(!empty($rin)){
            $output.=$level." RIN ".$rin."\n";
        }
        
        return $output;
    }
}
