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

namespace Gedcom;

/**
 *
 */
class Writer
{
    const GEDCOM55 = 'gedcom5.5';
    
    protected $_output = null;
    
    /**
     *
     * @param \Gedcom\Gedcom $gedcom The GEDCOM object
     * @param string $format The format to convert the GEDCOM object to
     * @return string The contents of the document in the converted format
     */
    public static function convert(\Gedcom\Gedcom &$gedcom, $format = self::GEDCOM55)
    {
        $head = $gedcom->getHead();
        
        $output = \Gedcom\Writer\Head::convert($head, $format);
        
        return $output;
    }
}
