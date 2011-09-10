<?php
/**
 *
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
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $fam = new \Gedcom\Record\Fam();
        $fam->id = $identifier;
        
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
                    $fam->husbandId = $parser->normalizeIdentifier($record[2]);
                break;    
                
                case 'WIFE':
                    $fam->wifeId = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'CHIL':
                    $fam->children[] = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'NCHI':
                    $fam->nchi = trim($record[2]);
                break;
                
                case 'SUBM':
                    $fam->addSubmitter($parser->normalizeIdentifier($record[2]));
                break;
                
                case 'RIN':
                    $fam->rin = trim($record[2]);
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $fam->chan = $chan;
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
                
                default:
                    if($recordType == 'EVEN' || in_array($recordType, self::$_eventTypes))
                    {
                        $even = \Gedcom\Parser\Fam\Even::parse($parser);
                        $fam->addEven($even);
                    }
                    else
                    {
                        $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
                    }
                break;
            }
            
            $parser->forward();
        }
        
        return $fam;
    }
}
