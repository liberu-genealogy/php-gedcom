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

namespace PhpGedcom\Record;

class Plac extends \PhpGedcom\Record implements Noteable
{
    /**
     * string plac.
     */
    protected $_plac = null;
    /**
     * string place_hierarchy.
     */
    protected $_form = null;
    /**
     * array PhpGedcom\Record\Plac\Fone.
     */
    protected $_fone = null;
    /**
     * array PhpGedcom\Record\Plac\Romn.
     */
    protected $_romn = null;
    /**
     * PhpGedcom\Record\Plac\Map.
     */
    protected $_map = null;
    /**
     * array PhpGedcom\Record\NoteRef.
     */
    protected $_note = null;

    /**
     * @param PhpGedcom\Record\NoteRef $note
     *
     * @return Plac
     */
    public function addNote($note = null)
    {
        $this->_note[] = $note;

        return $this;
    }
}
