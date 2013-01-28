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
class Caln extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[2]);
        $depth = (int)$record[0];
        
        $caln = new \Gedcom\Record\Caln();
        $caln->caln = $identifier;
        
        $parser->forward();
        
        while(!$parser->eof())
        {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtolower(trim($record[1]));
            $lineDepth = (int)$record[0];
            
            if($lineDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            if($caln->hasAttribute($recordType))
                $caln->{'set' . $recordType}(trim($record[2]));
            else
                $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            
            $parser->forward();
        }
        
        return $caln;
    }
}
