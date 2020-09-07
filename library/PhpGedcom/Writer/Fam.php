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
class Fam
{
    /**
     * @param \PhpGedcom\Record\Fam $sour
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Fam &$fam, $level=0)
    {
        
        $output = "";
        $id = $fam->getId();
        if(empty($id)){
            return $output;
        }else{
            $output.=$level." @".$id."@ FAM "."\n";
        }
        // level up
        $level++;

        // HUSB
        $husb = $fam->getHusb();
        if(!empty($husb)){
            $output.=$level." HUSB @".$husb."@\n";
        }
        
        // WIFE
        $wife = $fam->getWife();
        if(!empty($wife)){
            $output.=$level." WIFE @".$wife."@\n";
        }

        // CHIL
        $chil = $fam->getChil();
        if(!empty($chil) && count($chil) > 0){
            foreach($chil as $item){
                if($item){
                    $_convert = $level." CHIL @".$item."@\n";
                    $output.=$_convert;
                }
            }
        }
        // NCHI
        $nchi = $fam->getNchi();
        if(!empty($nchi)){
            $output.=$level." NCHI ".$nchi."\n";
        }

        // SUBM array
        $subm = $fam->getSubm();
        
        if(!empty($subm) && count($subm) > 0){
            foreach($subm as $item){
                if($item){
                    $output.=$level." SUBM ".$item."\n";
                }
            }
        }

        // RIN
        $rin = $fam->getRin();
        if(!empty($rin)){
            $output.=$level." RIN ".$rin."\n";
        }
        // CHAN
        $chan = $fam->getChan();
        if(!empty($chan)){
            $_convert = \PhpGedcom\Writer\Chan::convert($chan, $level);
            $output.=$_convert;
        }
        // SLGS
        $slgs = $fam->getSlgs();
        if(!empty($slgs) && count($slgs) > 0){
            if($slgs){
                $_convert = \PhpGedcom\Writer\Fam\Slgs::convert($item, $level);
                $output.=$_convert;
            }
        }

        // REFN array
        $refn = $fam->getRefn();
        if(!empty($refn) && count($refn) > 0){
            foreach($refn as $item){
                if($item){
                    $_convert = \PhpGedcom\Writer\Refn::convert($item, $level);
                    $output.=$_convert;
                }
            }
        }

        // NOTE array
        $note = $fam->getNote();
        if(!empty($note) && count($note) > 0){
            foreach($note as $item){
                if($item){
                    $_convert = \PhpGedcom\Writer\NoteRef::convert($item, $level);
                    $output.=$_convert;
                }
            }
        }

        // SOUR
        $sour = $fam->getSour();
        if(!empty($sour) && count($sour) > 0){
            foreach($sour as $item){
                if($item){
                    $_convert = \PhpGedcom\Writer\SourRef::convert($item, $level);
                    $output.=$_convert;
                }
            }
        }

        // OBJE
        $obje = $fam->getObje();
        if(!empty($obje) && count($obje) > 0){
            foreach($obje as $item){
                if($item){
                    $_convert = \PhpGedcom\Writer\ObjeRef::convert($item, $level);
                    $output.=$_convert;
                }
            }
        }

        // EVEN
        $even = $fam->getAllEven();
        if(!empty($even) && count($even) > 0){
            foreach($even as $item){
                if($item){
                    $_convert = \PhpGedcom\Writer\Fam\Even::convert($item, $level);
                    $output.=$_convert;
                }
            }
        }
        return $output;
    }
}
