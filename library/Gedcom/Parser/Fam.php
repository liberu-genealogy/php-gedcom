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

namespace Gedcom\Parser;

/**
 *
 *
 */
class Fam extends \Gedcom\Parser\Component
{
    protected static $_eventTypes = array('ANUL','CENS','DIV','DIVF','ENGA','MARR',
        'MARB','MARC','MARL','MARS');
    
    /**
     *
     *
     */
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $fam = new \Gedcom\Record\Fam();
        $fam->setId($identifier);
        
        $parser->getGedcom()->addFam($fam);
        
        $parser->forward();
        
        while(!$parser->eof())
        {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int)$record[0];
            $recordType = strtoupper(trim($record[1]));
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'HUSB':
                    $fam->setHusb($parser->normalizeIdentifier($record[2]));
                break;    
                
                case 'WIFE':
                    $fam->setWife($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'CHIL':
                    $fam->addChil($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'NCHI':
                    $fam->setNchi(trim($record[2]));
                break;
                
                case 'SUBM':
                    $fam->addSubm($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'RIN':
                    $fam->setRin(trim($record[2]));
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $fam->setChan($chan);
                break;
                
                case 'SLGS':
                    $slgs = \Gedcom\Parser\Fam\Slgs::parse($parser);
                    $fam->addSlgs($slgs);
                break;
                
                case 'REFN':
                    $ref = \Gedcom\Parser\Refn::parse($parser);
                    $fam->addRefn($ref);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $fam->addNote($note);
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $fam->addSour($sour);
                break;
                
                case 'OBJE':
                    $obje = \Gedcom\Parser\ObjeRef::parse($parser);
                    $fam->addObje($obje);
                break;
                
                case 'EVEN':
                case 'ANUL':
                case 'CENS':
                case 'DIV':
                case 'DIVF':
                case 'ENGA':
                case 'MARR':
                case 'MARB':
                case 'MARC':
                case 'MARL':
                case 'MARS':
                    $className = ucfirst(strtolower($recordType));
                    $class = '\\Gedcom\\Parser\\Fam\\' . $className;
                    
                    $even = $class::parse($parser);
                    $fam->addEven($even);
                    break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $fam;
    }
}
