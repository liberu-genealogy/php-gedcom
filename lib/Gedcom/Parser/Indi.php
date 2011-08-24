<?php

namespace Gedcom\Parser;

require_once __DIR__ . '/Indi/Name.php';
require_once __DIR__ . '/Indi/Even.php';
require_once __DIR__ . '/Indi/Even/Birt.php';
require_once __DIR__ . '/../Record/Indi.php';

/**
 *
 *
 */
class Indi extends \Gedcom\Parser\Component
{
    protected static $_eventTypes = array('ADOP','BIRT','BAPM','BARM','BASM','BLES','BURI',
        'CENS','CHR','CHRA','CONF','CREM','DEAT','EMIG','FCOM','GRAD','IMMI','NATU','ORDN',
        'RETI','PROB','WILL','EVEN');
    
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
        $indi->refId = $identifier;
        
        $parser->getGedcom()->addIndi($indi);
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
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
                
                case 'BIRT':
                    $birth = \Gedcom\Parser\Indi\Even\Birt::parse($parser);
                    $indi->addEven($birth);
                break;
                
                case 'ADOP':
                    $adoption = \Gedcom\Parser\Indi\Even\Adop::parse($parser);
                    $indi->addEven($adoption);
                break;
                
                case 'CHR':
                    $chr = \Gedcom\Parser\Indi\Even\Chr::parse($parser);
                    $indi->addEven($chr);
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
                    $fams = \Gedcom\Parser\Indi\Fam\Spouse::parse($parser);
                    $indi->addSpouseFamily($fams);
                break;
                
                case 'FAMC':
                    $famc = \Gedcom\Parser\Indi\Fam\Child::parse($parser);
                    $indi->addChildFamily($famc);
                break;
                
                case 'ASSO':
                    $asso = \Gedcom\Parser\Indi\Association::parse($parser);
                    $indi->addAssociation($asso);
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
                    $object = \Gedcom\Parser\ObjeRef::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Obje\Ref'))
                        $indi->addObjeRef($object);
                    else
                        $indi->addObje($object);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $indi->addNoteRef($note);
                    else
                        $indi->addNote($note);
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Ref'))
                        $indi->addSourceCitationRef($citation);
                    else
                        $indi->addSourceCitation($citation);
                break;
                
                default:
                    if($recordType == 'EVEN' || in_array($recordType, self::$_eventTypes))
                    {
                        $event = \Gedcom\Parser\Indi\Even::parse($parser);
                        $indi->addEven($event);
                    }
                    else if(in_array($recordType, self::$_attrTypes))
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
