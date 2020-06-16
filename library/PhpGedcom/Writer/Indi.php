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
class Indi
{
    /**
     * @param \PhpGedcom\Record\Indi $indi
     * @param string $format
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Indi &$indi, $format = self::GEDCOM55)
    {
        $level = 0;

        // id
        $id = $indi->getId();
        $output = $level." @".$id."@ INDI\n";

        // increase level after start indi
        $level++;

        // name
        // $name = $indi->getName();
        // if(!empty($name)){
        //     $output.=$level." NAME ".$name."\n";
        // }

        // chan
        $chan = $indi->getChan();
        if(!empty($chan)){
            $output .= $level." CHAN ".$chan."\n";
        }

        // $attr
        // PhpGedcom/Record/Attr extend PhpGedcom/Record/Even and there is no change.
        // So used convert Even
        $attr = $indi->getAttr();
        if(!empty($attr) && count($attr) > 0){
            foreach($attr as $item){
                $_convert = \PhpGedcom\Writer\Indi\Even::convert($item, $level);
                $output.=$_convert;
            }
        }

        // $even
        $even = $indi->getEven();
        if(!empty($even) && count($even) > 0){
            foreach($even as $item){
                $_convert = \PhpGedcom\Writer\Indi\Even::convert($item, $level);
                $output.=$_convert;
            }
        }

        // $note
        $note = $indi->getNote();
        if(!empty($note) && count($note) > 0){
            foreach($note as $item){
                $_convert = \PhpGedcom\Writer\Indi\Note::convert($item, $level);
                $output.=$_convert;
            }
        }

        // $obje
        $obje = $indi->getObje();
        if(!empty($obje) && count($obje) > 0){
            foreach($obje as $item){
                $_convert = \PhpGedcom\Writer\Indi\Obje::convert($item, $level);
                $output.=$_convert;
            }
        }

        // $sour
        $sour = $indi->getSour();
        if(!empty($sour) && count($sour) > 0){
            foreach($sour as $item){
                $_convert = \PhpGedcom\Writer\Indi\Sour::convert($item, $level);
                $output.=$_convert;
            }
        }

        // $name
        $name = $indi->getName();
        if(!empty($name) && count($name) > 0){
            foreach($name as $item){
                $_convert = \PhpGedcom\Writer\Indi\Name::convert($item, $level);
                $output.=$_convert;
            }
        }
        
        // $alia
        $alia = $indi->getAlia();
        if(!empty($alia) && count($alia) > 0){
            foreach($alia as $item){
                $_convert = \PhpGedcom\Writer\Indi\Alia::convert($item, $level);
                $output.=$_convert;
            }
        }
        
        // $sex
        $sex = $indi->getSex();
        if(!empty($sex)){
            $output .= $level." SEX ".$sex."\n";
        }

        // $rin
        $rin = $indi->getRin();
        if(!empty($rin)){
            $output .= $level." RIN ".$rin."\n";
        }

        // $resn
        $resn = $indi->getResn();
        if(!empty($resn)){
            $output .= $level." RESN ".$resn."\n";
        }

        // $rfn
        $rfn = $indi->getRfn();
        if(!empty($rfn)){
            $output .= $level." RFN ".$rfn."\n";
        }

        // $afn
        $afn = $indi->getAfn();
        if(!empty($afn)){
            $output .= $level." AFN ".$afn."\n";
        }

        // Fams[]
        $fams = $indi->getFams();
        if(!empty($fams) && count($fams) > 0){
            foreach($fams as $item){
                $_convert = \PhpGedcom\Writer\Indi\Fams::convert($item, $level);
                $output.=$_convert;
            }
        }

        // Famc[]
        $famc = $indi->getFamc();
        if(!empty($famc) && count($famc) > 0){
            foreach($famc as $item){
                $_convert = \PhpGedcom\Writer\Indi\Famc::convert($item, $level);
                $output.=$_convert;
            }
        }

        // Asso[]
        $asso = $indi->getAsso();
        if(!empty($asso) && count($asso) > 0){
            foreach($asso as $item){
                $_convert = \PhpGedcom\Writer\Indi\Asso::convert($item, $level);
                $output.=$_convert;
            }
        }

        // $subm
        $subm = $indi->getSubm();
        if(!empty($subm) && count($subm) > 0){
            foreach($subm as $item){
                $_convert = \PhpGedcom\Writer\Indi\Subm::convert($item, $level);
                $output.=$_convert;
            }
        }

        // $anci
        $anci = $indi->getAnci();
        if(!empty($anci) && count($anci) > 0){
            foreach($subm as $item){
                $_convert = \PhpGedcom\Writer\Indi\Anci::convert($item, $level);
                $output.=$_convert;
            }
        }

        // $desi
        $desi = $indi->getDesi();
        if(!empty($desi) && count($desi) > 0){
            foreach($subm as $item){
                $_convert = \PhpGedcom\Writer\Indi\Desi::convert($item, $level);
                $output.=$_convert;
            }
        }

        // Refn[]
        $refn = $indi->getRefn();
        if(!empty($refn) && count($refn) > 0){
            foreach($subm as $item){
                $_convert = \PhpGedcom\Writer\Indi\Refn::convert($item, $level);
                $output.=$_convert;
            }
        }

        // Bapl
        $bapl = $indi->getBapl();
        if(!empty($bapl)){
            $_convert = \PhpGedcom\Writer\Indi\Bapl::convert($bapl, $level);
            $output.=$_convert;
        }
        
        // Conl
        $conl = $indi->getConl();
        if(!empty($conl)){
            $_convert = \PhpGedcom\Writer\Indi\Conl::convert($conl, $level);
            $output.=$_convert;
        }

        // Endl
        $endl = $indi->getEndl();
        if(!empty($endl)){
            $_convert = \PhpGedcom\Writer\Indi\Endl::convert($endl, $level);
            $output.=$_convert;
        }

        // Slgc
        $slgc = $indi->getSlgc();
        if(!empty($slgc)){
            $_convert = \PhpGedcom\Writer\Indi\Slgc::convert($slgc, $level);
            $output.=$_convert;
        }

        return $output;
    }
}
