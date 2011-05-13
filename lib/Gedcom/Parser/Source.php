<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Source
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = str_replace('@', '', $record[2]);
        
        $source = &$parser->getGedcom()->createSource($identifier);
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord('S');
            
            if($record[0] == '0')
            {
                $parser->back();
                break;
            }
            else if($record[0] == '1' && trim($record[1]) == 'TITL')
            {
                $source->title = $parser->parseMultilineRecord();
            }
            else if($record[0] == '1' && trim($record[1]) == 'RIN')
            {
                $source->rin = trim($record[2]);
            }
            else if($record[0] == '1' && trim($record[1]) == 'AUTH')
            {
                $source->author = $parser->parseMultilineRecord();
            }
            else if($record[0] == '1' && trim($record[1]) == 'TEXT')
            {
                $source->text = $parser->parseMultilineRecord();
            }
            else if($record[0] == '1' && trim($record[1]) == 'PUBL')
            {
                $source->published = $parser->parseMultilineRecord();
            }
            else if($record[0] == '1' && trim($record[1]) == 'NOTE')
            {
                if(isset($record[2]) && preg_match('/\@N([0-9]*)\@/i', $record[2]) > 0)
                {
                    $source->addNote($parser->normalizeIdentifier($record[2], 'N'));
                }
                else
                {
                    $inlineNote = $record[2];
                    
                    //$this->_currentLine++;
                    $parser->forward();
                    
                    while($parser->getCurrentLine() < $parser->getFileLength())
                    {
                        $record = $parser->getCurrentLineRecord();
                        
                        if((int)$record[0] <= 1)
                        {
                            //$this->_currentLine--;
                            $parser->back();
                            break;
                        }
                        
                        switch($record[1])
                        {
                            case 'CONT':
                                if(isset($record[2]))
                                    $inlineNote .= "\n" . trim($record[2]);
                            break;
                            
                            case 'CONC':
                                if(isset($record[2]))
                                    $inlineNote .= ' ' . trim($record[2]);
                            break;
                        }
                        
                        //$this->_currentLine++;
                        $parser->forward();
                    }
                    
                    $source->addInlineNote($inlineNote);
                }
            }
            else if((int)$record[0] == 1 && trim($record[1]) == 'CHAN')
            {
                //$this->_currentLine++;
                $parser->forward();
                
                $source->change = new \Gedcom\Record\Change();
                
                while($parser->getCurrentLine() < $parser->getFileLength())
                {
                    $record = $parser->getCurrentLineRecord();
                    
                    if((int)$record[0] <= 1)
                    {
                        //$this->_currentLine--;
                        $parser->back();
                        break;
                    }
                    else if((int)$record[0] == 2 && trim($record[1] == 'DATE'))
                    {
                        if(isset($record[2]))
                            $source->date = trim($record[2]);
                    }
                    else if((int)$record[0] == 3 && trim($record[1] == 'TIME'))
                    {
                        if(isset($record[2]))
                            $source->time = trim($record[2]);
                    }
                    else
                    {
                        $parser->logUnhandledRecord(__LINE__);
                    }
                    
                    //$this->_currentLine++;
                    $parser->forward();
                }
            }
            /*else if((int)$record[0] > 1)
            {
                // do nothing, this should be handled in cases above by
                // passing off code execution to other classes
            }*/
            else
            {
                $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            //$this->_currentLine++;
            $parser->forward();
        }
    }
}
