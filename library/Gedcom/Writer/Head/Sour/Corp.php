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

namespace Gedcom\Writer\Head\Sour;

/**
 *
 */
class Corp
{   
    /**
     *
     * @param \Gedcom\Gedcom $gedcom The GEDCOM object
     * @param string $format The format to convert the GEDCOM object to
     * @return string The contents of the document in the converted format
     */
    public static function convert(\Gedcom\Record\Head\Sour\Corp &$corp, $format = self::GEDCOM55, $level = 2)
    {
        $output = "{$level} CORP " . $corp->corp . "\n" .
            \Gedcom\Writer\Addr::convert($corp->addr, $format, $level + 1);
        
        foreach($corp->phon as $phon)
            $output .= \Gedcom\Writer\Phon::convert($phon, $format, $level + 1);
        
        return $output;
    }
}

