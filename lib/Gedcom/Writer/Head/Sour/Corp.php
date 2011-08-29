<?php
/**
 *
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

