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
class Note extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord(4);
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $note = new \Gedcom\Record\Note();
        $note->setId($identifier);
        
        if(isset($record[3]))
            $note->setNote($record[3]);
        
        $parser->getGedcom()->addNote($note);
        
        if(isset($record[3]))
            $note->note = $record[3];
        
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
                case 'RIN':
                    $note->setRin(trim($record[2]));
                break;
                
                case 'CONT':
                    $note->setNote($note->getNote() . "\n");
                    
                    if(isset($record[2]))
                        $note->setNote($note->getNote() . $record[2]);
                break;
                
                case 'CONC':
                    if(isset($record[2]))
                        $note->setNote($note->getNote() . $record[2]);
                break;
               
                case 'REFN':
                    $refn = \Gedcom\Parser\Refn::parse($parser);
                    $note->addRefn($refn);
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $note->setChan($chan);
                break;
                
                case 'SOUR':
                    $sour = \Gedcom\Parser\SourRef::parse($parser);
                    $note->addSour($sour);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $note;
    }
}
