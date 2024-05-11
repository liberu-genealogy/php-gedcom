<?php

namespace Gedcom\Parser\Interfaces;

use Gedcom\Gedcom;

/**
 * Interface for parsers within the GEDCOM project.
 *
 * Outlines the essential methods required for parsing operations, 
 * including file handling and navigation through the parsed content.
 */
interface ParserInterface
{
    /**
     * Initiates the parsing of a GEDCOM file.
     *
     * @param string $fileName The path to the GEDCOM file to be parsed.
     * @return Gedcom|null
     */
    public function parse($fileName);

    /**
     * Advances the parser to the next line in the GEDCOM file.
     *
     * @return void
     */
    public function forward();

    /**
     * Moves the parser back to the previously read line in the GEDCOM file.
     *
     * @return void
     */
    public function back();

    /**
     * Checks if the end of the GEDCOM file has been reached.
     *
     * @return bool True if the end of the file has been reached, false otherwise.
     */
    public function eof();
}
