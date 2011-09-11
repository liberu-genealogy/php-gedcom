<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kwilson@shuttlebox.net>
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
class SourRef extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $sour = new \Gedcom\Record\SourRef();
        $sour->sour = $record[2];
        
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
                case 'CONT':
                    $sour->sour .= "\n";
                    
                    if(isset($record[2]))
                        $sour->sour .= trim($record[2]);
                break;
                
                case 'CONC':
                    if(isset($record[2]))
                        $sour->sour .= ' ' . trim($record[2]);
                break;
            
                case 'TEXT':
                    $sour->text = $parser->parseMultiLineRecord();
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    $sour->addNote($note);
                break;
                
                case 'DATA':
                    $sour->data = \Gedcom\Parser\Source\Data::parse($parser);
                break;
                
                case 'QUAY':
                    $sour->quay = trim($record[2]);
                break;
                
                case 'PAGE':
                    $sour->page = trim($record[2]);
                break;
                
                case 'EVEN':
                    $even = \Gedcom\Parser\SourRef\Even::parse($parser);
                    $sour->even = $even;
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $sour;
    }
}
