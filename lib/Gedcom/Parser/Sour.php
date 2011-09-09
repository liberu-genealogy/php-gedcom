<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 * 
 * 
 */
class Sour extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $sour = new \Gedcom\Record\Sour();
        $sour->refId = $identifier;
        
        $parser->getGedcom()->addSour($sour);
        
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
                case 'TITL':
                    $sour->titl = $parser->parseMultilineRecord();
                break;
                
                case 'RIN':
                    $sour->rin = trim($record[2]);
                break;
                
                case 'AUTH':
                    $sour->auth = $parser->parseMultilineRecord();
                break;
                
                case 'TEXT':
                    $sour->text = $parser->parseMultilineRecord();
                break;
                
                case 'PUBL':
                    $sour->publ = $parser->parseMultilineRecord();
                break;
                
                case 'ABBR':
                    $sour->abbr = trim($record[2]);
                break;
                
                case 'REPO':
                    $sour->repo = \Gedcom\Parser\SourceRepositoryCitation::parse($parser);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $sour->addNoteRef($note);
                    else
                        $sour->addNote($note);
                break;
                
                case 'DATA':
                    $sour->data = \Gedcom\Parser\Source\Data::parse($parser);
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\ObjeRef::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Obje\Ref'))
                        $sour->addObjeRef($object);
                    else
                        $sour->addObje($object);
                break;
                
                case 'REFN':
                    $refn = \Gedcom\Parser\Refn::parse($parser);
                    $sour->addRefn($refn);
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $sour->chan = $chan;
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $sour;
    }
}
