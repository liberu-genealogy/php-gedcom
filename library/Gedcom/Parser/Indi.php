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
class Indi extends \Gedcom\Parser\Component
{
    /**
     *
     *
     */
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $indi = new \Gedcom\Record\Indi();
        $indi->setId($identifier);
        
        $parser->getGedcom()->addIndi($indi);
        
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
                case 'NAME':
                    $name = \Gedcom\Parser\Indi\Name::parse($parser);
                    $indi->addName($name);
                break;
                
                case 'ALIA':
                    $indi->addAlia($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'SEX':
                    $indi->setSex(trim($record[2]));
                break;
                
                case 'RIN':
                    $indi->setRin(trim($record[2]));
                break;
                
                case 'RESN':
                    $indi->setResn(trim($record[2]));
                break;
                
                case 'RFN':
                    $indi->setRfn(trim($record[2]));
                break;
                
                case 'AFN':
                    $indi->setAfn(trim($record[2]));
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $indi->setChan($chan);
                break;
                
                case 'FAMS':
                    $fams = \Gedcom\Parser\Indi\Fams::parse($parser);
                    $indi->addFams($fams);
                break;
                
                case 'FAMC':
                    $famc = \Gedcom\Parser\Indi\Famc::parse($parser);
                    $indi->addFamc($famc);
                break;
                
                case 'ASSO':
                    $asso = \Gedcom\Parser\Indi\Asso::parse($parser);
                    $indi->addAsso($asso);
                break;
                
                case 'ANCI':
                    $indi->addAnci($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'DESI':
                    $indi->addDesi($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'SUBM':
                    $indi->addSubm($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'REFN':
                    $ref = \Gedcom\Parser\Refn::parse($parser);
                    $indi->addRefn($ref);
                break;
                
                case 'BAPL':
                case 'CONL':
                case 'ENDL':
                case 'SLGC':
                    $className = ucfirst(strtolower($recordType));
                    $class = '\\Gedcom\\Parser\\Indi\\' . $className;
                    
                    $lds = $class::parse($parser);
                    $indi->{'set' . $recordType}($lds);
                    break;
                
                case 'OBJE':
                    $obje = \Gedcom\Parser\ObjeRef::parse($parser);
                    $indi->addObje($obje);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $indi->addNote($note);
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $indi->addSour($sour);
                break;
                
                case 'ADOP':
                case 'BIRT':
                case 'BAPM':
                case 'BARM':
                case 'BASM':
                case 'BLES':
                case 'BURI':
                case 'CENS':
                case 'CHR':
                case 'CHRA':
                case 'CONF':
                case 'CREM':
                case 'DEAT':
                case 'EMIG':
                case 'FCOM':
                case 'GRAD':
                case 'IMMI':
                case 'NATU':
                case 'ORDN':
                case 'RETI':
                case 'PROB':
                case 'WILL':
                case 'EVEN':
                    $className = ucfirst(strtolower($recordType));
                    $class = '\\Gedcom\\Parser\\Indi\\' . $className;
                    
                    $event = $class::parse($parser);
                    $indi->addEven($event);
                    break;
                
                case 'CAST':
                case 'DSCR':
                case 'EDUC':
                case 'IDNO':
                case 'NATI':
                case 'NCHI':
                case 'NMR':
                case 'OCCU':
                case 'PROP':
                case 'RELI':
                case 'RESI':
                case 'SSN':
                case 'TITL':
                    $className = ucfirst(strtolower($recordType));
                    $class = '\\Gedcom\\Parser\\Indi\\' . $className;
                    
                    $att = $class::parse($parser);
                    $indi->addAttr($att);
                    break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $indi;
    }
}
