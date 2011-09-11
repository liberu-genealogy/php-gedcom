<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 *
 *
 */
class Indi extends \Gedcom\Parser\Component
{
    /*
    protected static $_eventTypes = array('ADOP','BIRT','BAPM','BARM','BASM','BLES','BURI',
        'CENS','CHR','CHRA','CONF','CREM','DEAT','EMIG','FCOM','GRAD','IMMI','NATU','ORDN',
        'RETI','PROB','WILL','EVEN');
    */
    
    protected static $_attrTypes = array('CAST','EDUC','NATI','OCCU','PROP','RELI','RESI',
        'TITL','SSN','IDNO','DSCR','NCHI','NMR');
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $indi = new \Gedcom\Record\Indi();
        $indi->id = $identifier;
        
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
                    $indi->addAlias($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'SEX':
                    $indi->sex = trim($record[2]);
                break;
                
                case 'RIN':
                    $indi->rin = trim($record[2]);
                break;
                
                case 'RESN':
                    $indi->resn = trim($record[2]);
                break;
                
                case 'RFN':
                    $indi->rfn = trim($record[2]);
                break;
                
                case 'AFN':
                    $indi->afn = trim($record[2]);
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $indi->chan = $chan;
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
                    $indi->addSubmitter($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'REFN':
                    $ref = \Gedcom\Parser\Refn::parse($parser);
                    $indi->addRefn($ref);
                break;
                
                case 'BAPL':
                case 'CONL':
                case 'ENDL':
                case 'SLGC':
                    $ordinance = \Gedcom\Parser\Indi\LdsIndividualOrdinance::parse($parser);
                    $indi->addLdsIndividualOrdinance($ordinance);
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
                
                default:
                    if(in_array($recordType, self::$_attrTypes))
                    {
                        $att = \Gedcom\Parser\Indi\Attr::parse($parser);
                        $indi->addAttr($att);
                    }
                    else
                    {
                        $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
                    }
                break;
            }
            
            $parser->forward();
        }
        
        return $indi;
    }
}
