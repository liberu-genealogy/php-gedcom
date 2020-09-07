<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @package         php-gedcom 
 * @license         MIT
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom\Record\Sour\Repo;

/**
 *
 */
class Caln extends \PhpGedcom\Record
{
    /**
     * string source_call_number
     */
    protected $_caln = null;
    /**
     * string source_media_type
     */
    protected $_medi = null;
}
