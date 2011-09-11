<?php
/**
 *
 */

namespace Gedcom\Parser\Indi;

/**
 *
 *
 */
class Even extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $even = null;
        
        if(strtoupper(trim($record[1])) != 'EVEN')
        {
            $className = '\\Gedcom\\Record\\Indi\\' . ucfirst(strtolower(trim($record[1])));
            $even = new $className();
        }
        else
        {
            $even = new \Gedcom\Record\Indi\Even();
        }
        
        if(isset($record[1]) && strtoupper(trim($record[1])) != 'EVEN')
            $even->type = trim($record[1]);
        
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
                    $even->type = trim($record[2]);
                break;
                
                case 'DATE':
                    $even->date = trim($record[2]);
                break;
                
                case 'PLAC':
                    $place = \Gedcom\Parser\Indi\Even\Place::parse($parser);
                    $even->place = $place;
                break;
                
                case 'ADDR':
                    $even->addr = \Gedcom\Parser\Addr::parse($parser);
                break;
                
                case 'PHON':
                    $phone = \Gedcom\Parser\Phone::parse($parser);
                    $even->addPhone($phone);
                break;
                
                case 'CAUS':
                    $even->caus = trim($record[2]);
                break;
                
                case 'AGE':
                    $even->age = trim($record[2]);
                break;
                
                case 'AGNC':
                    $even->agnc = trim($record[2]);
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $even->addSour($sour);
                break;
                
                case 'OBJE':
                    $obje = \Gedcom\Parser\ObjeRef::parse($parser);
                    $even->addObje($obje);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $even->addNote($note);
                break;
                
                default:
                    $self = get_called_class();
                    $method = 'parse' . $recordType;
                    
                    if(method_exists($self, $method))
                        $self::$method($parser, $even);
                    else
                        $parser->logUnhandledRecord($self . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $even;
    }
}
