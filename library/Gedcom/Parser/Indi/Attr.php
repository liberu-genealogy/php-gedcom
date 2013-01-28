<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2011, Kristopher Wilson
 * @package         php-gedcom 
 * @license         http://php-gedcom.kristopherwilson.com/license
 * @link            http://php-gedcom.kristopherwilson.com
 * @version         SVN: $Id$
 */

namespace Gedcom\Parser\Indi;

/**
 *
 *
 */
abstract class Attr extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $className = '\\Gedcom\\Record\\Indi\\' . ucfirst(strtolower(trim($record[1])));
        $attr = new $className();
        
        $attr->setType(trim($record[1]));
        
        if(isset($record[2]))
            $attr->setAttr(trim($record[2]));
        
        $parser->forward();
        
        while(!$parser->eof())
        {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim($record[1]));
            $currentDepth = (int)$record[0];
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'TYPE':
                    $attr->setType(trim($record[2]));
                break;
                
                case 'DATE':
                    $attr->setDate(trim($record[2]));
                break;
                
                case 'PLAC':
                    $plac = \Gedcom\Parser\Indi\Even\Plac::parse($parser);
                    $attr->setPlac($plac);
                break;
                
                case 'ADDR':
                    $attr->setAddr(\Gedcom\Parser\Addr::parse($parser));
                break;
                
                case 'PHON':
                    $phone = \Gedcom\Parser\Phon::parse($parser);
                    $attr->addPhon($phone);
                break;
                
                case 'CAUS':
                    $attr->setCaus(trim($record[2]));
                break;
                
                case 'AGE':
                    $attr->setAge(trim($record[2]));
                break;
                
                case 'AGNC':
                    $attr->setAgnc(trim($record[2]));
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $attr->addSour($sour);
                break;
                
                case 'OBJE':
                    $obje = \Gedcom\Parser\ObjeRef::parse($parser);
                    $attr->addObje($obje);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $attr->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $attr;
    }
}
