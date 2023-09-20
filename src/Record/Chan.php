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

namespace Gedcom\Record;

use Gedcom\Record;

/**
 * Class Chan.
 */
class Chan extends \Gedcom\Record
{
    private array $months = [
        'JAN' => '01', 'FEB' => '02', 'MAR' => '03', 'APR' => '04', 'MAY' => '05', 'JUN' => '06',
        'JUL' => '07', 'AUG' => '08', 'SEP' => '09', 'OCT' => '10', 'NOV' => '11', 'DEC' => '12',
    ];

    /**
     * @var string
     */
    protected $date;

    /**
     * @var string
     */
    protected $time;

    /**
     * @var string
     */
    protected $datetime = '';

    /**
     * @var array
     */
    protected $note = [];

    /**
     * @param string $date
     *
     * @return Chan
     */
    public function setDate($date = '')
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param Record\NoteRef $note
     *
     * @return Chan
     */
    public function addNote($note = [])
    {
        $this->note[] = $note;

        return $this;
    }

    /**
     * @return array
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $time
     *
     * @return Chan
     */
    public function setTime($time = '')
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    public function setDatetime($date = '')
    {
        $this->datetime = $date.' '.$this->time;

        return $this;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function getMonth()
    {
        $record = explode(' ', $this->date);
        if ($this->isPrefix($record[0])) {
            unset($record[0]);
        }
        foreach ($record as $part) {
            if (isset($this->months[trim($part)])) {
                return $this->months[trim($part)];
            }
        }

        return null;
    }

    /**
     * Return year part of date.
     *
     * @return int|null
     */
    public function getYear()
    {
        $record = explode(' ', $this->date);
        if ($this->isPrefix($record[0])) {
            unset($record[0]);
        }

        return (int) end($record);
    }

    /**
     * Return day part of date.
     *
     * @return int|null
     */
    public function getDay()
    {
        $record = explode(' ', $this->date);
        if (isset($record[0]) && $record[0] !== '') {
            if ($this->isPrefix($record[0])) {
                unset($record[0]);
            }
            if ($record !== []) {
                $day = (int) reset($record);
                if ($day >= 1 && $day <= 31) {
                    return substr("0{$day}", -2);
                }
            }
        }

        return null;
    }

    /**
     * Check if the first part is a prefix (eg 'BEF', 'ABT',).
     *
     * @param string $datePart Date part to be checked
     *
     * @return bool
     */
    private function isPrefix($datePart)
    {
        return in_array($datePart, ['FROM', 'TO', 'BEF', 'AFT', 'BET', 'AND', 'ABT', 'EST', 'CAL', 'INT']);
    }
}
