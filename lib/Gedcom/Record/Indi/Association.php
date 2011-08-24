<?php

namespace Gedcom\Record\Indi;

require_once realpath(__DIR__ . '/../../Record.php');

/**
 *
 */
class Association extends \Gedcom\Record
{
    public $individualId = null;
    public $rela = null;
}
