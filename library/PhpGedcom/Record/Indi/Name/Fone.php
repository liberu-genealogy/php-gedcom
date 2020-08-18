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
 * @license         GPL-3.0
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom\Record\Indi\Name;

use PhpGedcom\Record;

/**
 * Class Refn
 * @package PhpGedcom\Record
 */
class Fone extends Record
{
    /**
     * @var string phonetic_variation
     */
    protected $fone;

    /**
     * @var string phonetic_type
     */
    protected $type;
    /**
	 * string name_piece_prefix
	 */
	protected $_npfx = null;
	/**
	 * string name_piece_given
	 */
	protected $_givn = null;
	/**
	 * string name_piece_nickname
	 */
	protected $_nick = null;
	/**
	 * strign name_piece_surname_prefix
	 */
	protected $_spfx = null;
	/**
	 * string name_piece_surname
	 */
	protected $_surn = null;
	/**
	 * string name_piece_suffix
	 */
	protected $_nsfx = null;

	/**
	 * PhpGedcom\Record\NoteRef
	 */
	protected $_note = array();

	/**
	 * PhpGedcom\Record\SourRef
	 */
	protected $_sour = array();

	/**
	 *
	 */
	public function addSour($sour = []) {
		$this->_sour[] = $sour;
	}

	/**
	 *
	 */
	public function addNote($note = []) {
		$this->_note[] = $note;
	}
}
