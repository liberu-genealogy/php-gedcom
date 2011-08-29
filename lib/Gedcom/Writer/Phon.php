<?php
/**
 *
 */

namespace Gedcom\Writer;

/**
 *
 */
class Phon
{   
    /**
     *
     * @param \Gedcom\Gedcom $gedcom The GEDCOM object
     * @param string $format The format to convert the GEDCOM object to
     * @return string The contents of the document in the converted format
     */
    public static function convert($phon, $format = self::GEDCOM55, $level = 1)
    {
        $output = "{$level} PHON " . $phon . "\n";
        
        return $output;
    }
}
