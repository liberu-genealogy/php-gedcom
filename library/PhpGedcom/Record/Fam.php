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

namespace PhpGedcom\Record;

/**
 *
 *
 */
class Fam extends \PhpGedcom\Record implements Noteable, Sourceable, Objectable {
	/**
	 *
	 */
	protected $_id = null;

	/**
	 *
	 */
	protected $_chan = null;

	/**
	 *
	 */
	protected $_husb = null;

	/**
	 *
	 */
	protected $_wife = null;

	/**
	 *
	 */
	protected $_nchi = null;

	/**
	 *
	 */
	protected $_chil = array();

	/**
	 *
	 */
	protected $_even = array();

	/**
	 *
	 */
	protected $_slgs = array();

	/**
	 *
	 */
	protected $_subm = array();

	/**
	 *
	 */
	protected $_refn = array();

	/**
	 *
	 */
	protected $_rin = null;

	/**
	 *
	 */
	protected $_note = array();

	/**
	 *
	 */
	protected $_sour = array();

	/**
	 *
	 */
	protected $_obje = array();

	/**
	 *
	 */
	public function addEven($even = []) {
		$this->_even[$even->getType()] = $even;
	}

	/**
	 * @return array
	 */
	public function getAllEven() {
		return $this->_even;
	}
	/**
	 * @return void|\PhpGedcom\Record\Fam\Even
	 */
	public function getEven($key = '') {
		if (isset($this->_even[strtoupper($key)])) {
			return $this->_even[strtoupper($key)];
		}

	}
	/**

	/**
	 *
	 */
	public function addSlgs($slgs = []) {
		$this->_slgs[] = $slgs;
	}

	/**
	 *
	 *
	 */
	public function addRefn($refn = []) {
		$this->_refn[] = $refn;
	}

	/**
	 *
	 */
	public function addNote($note = []) {
		$this->_note[] = $note;
	}

	/**
	 *
	 */
	public function addSour($sour = []) {
		$this->_sour[] = $sour;
	}

	/**
	 *
	 */
	public function addObje($obje = []) {
		$this->_obje[] = $obje;
	}
}
