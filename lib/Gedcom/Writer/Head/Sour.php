<?php
/**
 *
 */

namespace Gedcom\Writer\Head;

/**
 *
 */
class Sour
{   
    /**
     *
     * @param \Gedcom\Gedcom $gedcom The GEDCOM object
     * @param string $format The format to convert the GEDCOM object to
     * @return string The contents of the document in the converted format
     */
    public static function convert(\Gedcom\Record\Head\Sour &$sour, $format = self::GEDCOM55, $level = 0)
    {
        $output = "1 SOUR " . $sour->sour . "\n" .
            "2 VERS " . $sour->vers . "\n" .
            \Gedcom\Writer\Head\Sour\Corp::convert($sour->corp, $format, 2) . 
            // TODO DATA;
            "";

/*        
      +2 DATA <NAME_OF_SOURCE_DATA>  {0:1}
        +3 DATE <PUBLICATION_DATE>  {0:1}
        +3 COPR <COPYRIGHT_SOURCE_DATA>  {0:1}
*/
        
        return $output;
    }
}

