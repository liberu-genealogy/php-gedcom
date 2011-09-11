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
class Head extends \Gedcom\Parser\Component
{
    
    /**
     *
     * @param \Gedcom\Parser parser 
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $head = new \Gedcom\Record\Head();
        
        $parser->getGedcom()->setHead($head);
        
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
                case 'SOUR':
                    $sour = \Gedcom\Parser\Head\Sour::parse($parser);
                    $head->sour = $sour;
                break;
                
                case 'DEST':
                    $head->dest = trim($record[2]);
                break;
                
                case 'SUBM':
                    $head->subm = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'SUBN':
                    $head->subn = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'DEST':
                    $head->dest = trim($record[2]);
                break;
                
                case 'FILE':
                    $head->file = trim($record[2]);
                break;
                
                case 'COPR':
                    $head->copr = trim($record[2]);
                break;
                
                case 'LANG':
                    $head->lang = trim($record[2]);
                break;
            
                case 'DATE':
                    $head->date = \Gedcom\Parser\Head\Date::parse($parser);
                break;
                
                case 'GEDC':
                    $head->gedc = \Gedcom\Parser\Head\Gedc::parse($parser);
                break;
                
                case 'CHAR':
                    $head->char = \Gedcom\Parser\Head\Char::parse($parser);
                break;
                
                case 'PLAC':
                    $head->plac = \Gedcom\Parser\Head\Plac::parse($parser);
                break;
                
                case 'NOTE':
                    $head->note = $parser->parseMultiLineRecord();
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $head;
    }
}
