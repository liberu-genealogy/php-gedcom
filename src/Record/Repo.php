<?php

declare(strict_types=1);

namespace Gedcom\Record;

final class Repo extends \Gedcom\Record implements Noteable
{
    private string $repo = '';
    private array $uid = [];
    private array $uid7 = [];
    private string $name = '';
    private ?Addr $addr = null;
    private array $phon = [];
    private array $email = [];
    private array $fax = [];
    private array $www = [];
    private string $rin = '';
    private ?Chan $chan = null;
    private array $refn = [];
    private array $note = [];

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
     * @param null
     *
     * @return Repo
     */
    public function addEmail($email = null)
    {
        $this->email[] = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null
     *
     * @return Repo
     */
    public function addFax($fax = null)
    {
        $this->fax[] = $fax;

        return $this;
    }

    /**
     * @return array
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param null
     *
     * @return Repo
     */
    public function addWww($www = null)
    {
        $this->www[] = $www;

        return $this;
    }

    /**
     * @return array
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * @param null|\Gedcom\Record\Refn $refn
     *
     * @return Repo
     */
    public function addRefn($refn = null)
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
    public function getRefn()
    {
        return $this->refn;
    }

    /**
     * @param null|\Gedcom\Record\NoteRef $note
     *
     * @return Repo
     */
    public function addNote($note = null)
    {
        if (empty($node)) {
            $note = new \Gedcom\Record\NoteRef();
        }
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
     * @param string $repo
     *
     * @return Repo
     */
    public function setRepo($repo = '')
    {
        $this->repo = $repo;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param string $name
     *
     * @return Repo
     */
    public function setName($name = '')
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|\Gedcom\Record\Addr $addr
     *
     * @return Repo
     */
    public function setAddr($addr = null)
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
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * @param string $rin
     *
     * @return Repo
     */
    public function setRin($rin = '')
    {
        $this->rin = $rin;

        return $this;
    }

    /**
     * @return string
     */
    public function getRin()
    {
        return $this->rin;
    }

    /**
     * @param \Gedcom\Record\Chan $chan
     *
     * @return Repo
     */
    public function setChan($chan = [])
    {
        $this->chan = $chan;

        return $this;
    }

    /**
     * @return \Gedcom\Record\Chan
     */
    public function getChan()
    {
        return $this->chan;
    }

    /**
     * Add a _UID value (GEDCOM 5.5.1)
     *
     * @param string $uid
     *
     * @return Repo
     */
    public function addUid(string $uid): self
    {
        $this->uid[] = $uid;
        return $this;
    }

    /**
     * Get all _UID values
     *
     * @return array
     */
    public function getAllUid(): array
    {
        return $this->uid;
    }

    /**
     * Add a UID value (GEDCOM 7.0)
     *
     * @param string $uid7
     *
     * @return Repo
     */
    public function addUid7(string $uid7): self
    {
        $this->uid7[] = $uid7;
        return $this;
    }

    /**
     * Get all UID values (GEDCOM 7.0)
     *
     * @return array
     */
    public function getAllUid7(): array
    {
        return $this->uid7;
    }
}