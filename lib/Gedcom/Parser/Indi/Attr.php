<?php
/**
 *
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
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $className = '\\Gedcom\\Record\\Indi\\' . ucfirst(strtolower(trim($record[1])));
        $attr = new $className();
        
        $attr->type = trim($record[1]);
        $attr->attr = isset($record[2]) ? trim($record[2]) : null;
        
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
                    $attr->type = trim($record[2]);
                break;
                
                case 'DATE':
                    $attr->date = trim($record[2]);
                break;
                
                case 'PLAC':
                    $place = \Gedcom\Parser\Indi\Even\Place::parse($parser);
                    $attr->place = $place;
                break;
                
                case 'ADDR':
                    $attr->addr = \Gedcom\Parser\Addr::parse($parser);
                break;
                
                case 'PHON':
                    $phone = \Gedcom\Parser\Phon::parse($parser);
                    $attr->addPhon($phone);
                break;
                
                case 'CAUS':
                    $attr->caus = trim($record[2]);
                break;
                
                case 'AGE':
                    $attr->age = trim($record[2]);
                break;
                
                case 'AGNC':
                    $attr->agnc = trim($record[2]);
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
