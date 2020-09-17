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

namespace PhpGedcom\Record\ObjeRef\File;

use PhpGedcom\Record;

/**
 * Class Refn.
 */
class Form extends Record
{
    /**
     * @var string multimedia_format
     */
    protected $_form;

    /**
     * @var string source_media_type
     *             for only obje
     */
    protected $_type;

    /**
     * @var string source_media_type
     *             for only objeref
     */
    protected $_medi;
}
