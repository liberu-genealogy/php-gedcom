<?php
/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace Gedcom;

class Parser implements \Gedcom\Parser\Interfaces\ParserInterface
{
    protected $_file;

    protected $_gedcom;

    protected $_errorLog = [];

    protected $_linesParsed = 0;

    protected $_line = '';

    protected $_lineRecord;

    protected $_linePieces = 0;

    protected $_returnedLine = '';

    public function __construct(Gedcom $gedcom = null)
    {
        $this->_gedcom = is_null($gedcom) ? new Gedcom() : $gedcom;
    }

    public function forward()
use Gedcom\Parser\Interfaces\ParserInterface;
    {
        // if there was a returned line by back(), set that as our current
        // line and blank out the returnedLine variable, otherwise grab
        // the next line from the file

        if (!empty($this->_returnedLine)) {
            $this->_line = $this->_returnedLine;
            $this->_returnedLine = '';
        } else {
            $this->_line = fgets($this->_file);
            $this->_lineRecord = null;
            $this->_linesParsed++;
        }

        return $this;
    }

    public function back()
    {
        // our parser object encountered a line it wasn't meant to parse
        // store this line for the previous parser to analyze

        $this->_returnedLine = $this->_line;

        return $this;
    }

    /**
     * Jump to the next level in the GEDCOM that is <= $level. This will leave the parser at the line above
     * this level, such that calling $parser->forward() will result in landing at the correct level.
     *
     * @param int $level
     */
    public function skipToNextLevel($level)
    {
        $currentDepth = 999;

        while ($currentDepth > $level) {
            $this->forward();
            $record = $this->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
        }

        $this->back();
    }

    public function getGedcom()
    {
        return $this->_gedcom;
    }

    public function eof()
    {
        return feof($this->_file);
    }

    /**
     * @return string
     */
    public function parseMultiLineRecord()
    {
        $record = $this->getCurrentLineRecord();

        $depth = (int) $record[0];
        $data = isset($record[2]) ? trim((string) $record[2]) : '';

        $this->forward();

        while (!$this->eof()) {
            $record = $this->getCurrentLineRecord();
            $recordType = strtoupper(trim((string) $record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $this->back();
                break;
            }

            switch ($recordType) {
            case 'DATA':
                $dataInstance = new \Gedcom\Record\Data();
                $this->forward();

                while (!$this->eof()) {
                    $record = $this->getCurrentLineRecord();
                    $recordTypeData = strtoupper(trim((string) $record[1]));
                    $dataDepth = (int) $record[0];

                    if ($dataDepth <= $currentDepth) {
                        $this->back();
                        break;
                    }

                    switch ($recordTypeData) {
                        case 'TEXT':
                            $textData = isset($record[2]) ? trim((string) $record[2]) : '';
                            $dataInstance->setText($textData);
                            break;
                        case 'CONT':
                            $contData = isset($record[2]) ? "\n" + trim((string) $record[2]) : "\n";
                            $dataInstance->setText($dataInstance->getText() + $contData);
                            break;
                        default:
                            $this->back();
                            break 2;
                    }

                    $this->forward();
                }

                // Logic to associate $dataInstance with its parent object goes here

                break;
            case 'CONT':
                $data .= "\n";

                if (isset($record[2])) {
                    $data .= trim((string) $record[2]);
                }
                break;
            case 'CONC':
                if (isset($record[2])) {
                    $data .= ' '.trim((string) $record[2]);
                }
                break;
            default:
                $this->back();
                break 2;
            }

            $this->forward();
        }

        return $data;
    }

    /**
     * @return string The current line
     */
    public function getCurrentLine()
    {
        return $this->_line;
    }

    public function getCurrentLineRecord($pieces = 3)
    {
        if (!is_null($this->_lineRecord) && $this->_linePieces == $pieces) {
            return $this->_lineRecord;
        }

        if (empty($this->_line)) {
            return false;
        }

        $line = trim((string) $this->_line);

        $this->_lineRecord = explode(' ', $line, $pieces);
        $this->_linePieces = $pieces;

        return $this->_lineRecord;
    }

    protected function logError($error)
    {
        $this->_errorLog[] = $error;
    }

    public function logUnhandledRecord($additionalInfo = '')
    {
        $this->logError(
            $this->_linesParsed.': (Unhandled) '.trim(implode('|', $this->getCurrentLineRecord())).
            (empty($additionalInfo) ? '' : ' - '.$additionalInfo)
        );
    }

    public function logSkippedRecord($additionalInfo = '')
    {
        $this->logError(
            $this->_linesParsed.': (Skipping) '.trim(implode('|', $this->getCurrentLineRecord())).
            (empty($additionalInfo) ? '' : ' - '.$additionalInfo)
        );
    }

    public function getErrors()
    {
        return $this->_errorLog;
    }

    public function normalizeIdentifier($identifier)
    {
        $identifier = trim((string) $identifier);

        return trim($identifier, '@');
    }

    /**
     * @param string $fileName
     *
     * @return Gedcom
     */
    public function parse($fileName)
    {
        $this->_file = fopen($fileName, 'r'); //explode("\n", mb_convert_encoding($contents, 'UTF-8'));

        if (!$this->_file) {
            return null;
        }

        $this->forward();

        while (!$this->eof()) {
            $record = $this->getCurrentLineRecord();

            if ($record === false) {
                continue;
            }

            $depth = (int) $record[0];

            // We only process 0 level records here. Sub levels are processed
            // in methods for those data types (individuals, sources, etc)

            if ($depth == 0) {
                // Although not always an identifier (HEAD,TRLR):
                if (isset($record[1])) {
                    $this->normalizeIdentifier($record[1]);
                }

                if (isset($record[1]) && trim((string) $record[1]) == 'HEAD') {
                    \Gedcom\Parser\Head::parse($this);
                } elseif (isset($record[2]) && trim((string) $record[2]) == 'SUBN') {
                    \Gedcom\Parser\Subn::parse($this);
                } elseif (isset($record[2]) && trim((string) $record[2]) == 'SUBM') {
                    \Gedcom\Parser\Subm::parse($this);
                } elseif (isset($record[2]) && $record[2] == 'SOUR') {
                    \Gedcom\Parser\Sour::parse($this);
                } elseif (isset($record[2]) && $record[2] == 'INDI') {
                    \Gedcom\Parser\Indi::parse($this);
                } elseif (isset($record[2]) && $record[2] == 'FAM') {
                    \Gedcom\Parser\Fam::parse($this);
                } elseif (isset($record[2]) && str_starts_with(trim((string) $record[2]), 'NOTE')) {
                    \Gedcom\Parser\Note::parse($this);
                } elseif (isset($record[2]) && $record[2] == 'REPO') {
                    \Gedcom\Parser\Repo::parse($this);
                } elseif (isset($record[2]) && $record[2] == 'OBJE') {
                    \Gedcom\Parser\Obje::parse($this);
                } elseif (isset($record[1]) && trim((string) $record[1]) == 'TRLR') {
                    // EOF
                    break;
                } else {
                    $this->logUnhandledRecord(self::class.' @ '.__LINE__);
                }
            } else {
                $this->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $this->forward();
        }

        return $this->getGedcom();
    }
}
