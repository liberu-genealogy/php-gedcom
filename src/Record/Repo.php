<?php

declare(strict_types=1);

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

/**
 * Class Repo.
 */
readonly class Repo extends \Gedcom\Record implements Noteable
{
    /**
     * @var string
     */
    private string $repo;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var ?Addr
     */
    private ?Addr $addr;

    /**
     * @var array
     */
    private array $phon = [];

    /**
     * @var array
     */
    private array $email = [];

    /**
     * @var array
     */
    private array $fax = [];

    /**
     * @var array
     */
    private array $www = [];

    /**
     * @var string
     */
    private string $rin;

    /**
     * @var ?Chan
     */
    private ?Chan $chan;

    /**
     * @var array
     */
    private array $refn = [];

    /**
     * @var array
     */
    private array $note = [];

    /**
     * @return Repo
     */
    public function __construct()
    {
        $this->phon = [];
        $this->email = [];
        $this->fax = [];
        $this->www = [];
        $this->refn = [];
        $this->note = [];
    }

    /**
     * @param string $phon
     *
     * @return Repo
     */
    public function addPhon(string $phon): self
    {
        $this->phon[] = $phon;

        return $this;
    }

    /**
     * @return array
     */
    public function getPhon(): array
    {
        return $this->phon;
    }

    /**
     * @param string $email
     *
     * @return Repo
     */
    public function addEmail(string $email): self
    {
        $this->email[] = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getEmail(): array
    {
        return $this->email;
    }

    /**
     * @param string $fax
     *
     * @return Repo
     */
    public function addFax(string $fax): self
    {
        $this->fax[] = $fax;

        return $this;
    }

    /**
     * @return array
     */
    public function getFax(): array
    {
        return $this->fax;
    }

    /**
     * @param string $www
     *
     * @return Repo
     */
    public function addWww(string $www): self
    {
        $this->www[] = $www;

        return $this;
    }

    /**
     * @return array
     */
    public function getWww(): array
    {
        return $this->www;
    }

    /**
     * @param null|\Gedcom\Record\Refn $refn
     *
     * @return Repo
     */
    public function addRefn(\Gedcom\Record\Refn $refn = null): self
    {
        if (empty($refn)) {
            $refn = new \Gedcom\Record\Refn();
        }
        $this->refn[] = $refn;

        return $this;
    }

    /**
     * @return array
     */
    public function getRefn(): array
    {
        return $this->refn;
    }

    /**
     * @param null|\Gedcom\Record\NoteRef $note
     *
     * @return Repo
     */
    public function addNote(\Gedcom\Record\NoteRef $note = null): self
    {
        if (empty($note)) {
            $note = new \Gedcom\Record\NoteRef();
        }
        $this->note[] = $note;

        return $this;
    }

    /**
     * @return array
     */
    public function getNote(): array
    {
        return $this->note;
    }

    /**
     * @param string $repo
     *
     * @return Repo
     */
    public function setRepo(string $repo = ''): self
    {
        $this->repo = $repo;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepo(): string
    {
        return $this->repo;
    }

    /**
     * @param string $name
     *
     * @return Repo
     */
    public function setName(string $name = ''): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param null|\Gedcom\Record\Addr $addr
     *
     * @return Repo
     */
    public function setAddr(\Gedcom\Record\Addr $addr = null): self
    {
        if (empty($addr)) {
            $addr = new \Gedcom\Record\Addr();
        }
        $this->addr = $addr;

        return $this;
    }

    /**
     * @return \Gedcom\Record\Addr
     */
    public function getAddr(): Addr
    {
        return $this->addr;
    }

    /**
     * @param string $rin
     *
     * @return Repo
     */
    public function setRin(string $rin = ''): self
    {
        $this->rin = $rin;

        return $this;
    }

    /**
     * @return string
     */
    public function getRin(): string
    {
        return $this->rin;
    }

    /**
     * @param \Gedcom\Record\Chan $chan
     *
     * @return Repo
     */
    public function setChan(\Gedcom\Record\Chan $chan = null): self
    {
        $this->chan = $chan;

        return $this;
    }

    /**
     * @return \Gedcom\Record\Chan
     */
    public function getChan(): Chan
    {
        return $this->chan;
    }
}