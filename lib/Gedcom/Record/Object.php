<?php

namespace Gedcom\Record;

require_once __DIR__ . '/../Record.php';

class Object extends \Gedcom\Record
{
    public $form = null;
    public $titl = null;
    public $blob = null;
    public $obje = null;
    public $refn = array();
    public $rin = null;
    public $chan = null;
    
    public $referenceNumbers = array();
    
    /**
     *
     */
    public function addReferenceNumber(\Gedcom\Record\ReferenceNumber &$refn)
    {
        $this->referenceNumbers[] = $refn;
    }
}

/*
  n @<XREF:OBJE>@ OBJE  {1:1}
    +1 FORM <MULTIMEDIA_FORMAT>  {1:1}
    +1 TITL <DESCRIPTIVE_TITLE>  {0:1}
    +1 <<NOTE_STRUCTURE>>  {0:M}
    +1 <<SOURCE_CITATION>>  {0:M}
    +1 BLOB        {1:1}
      +2 CONT <ENCODED_MULTIMEDIA_LINE>  {1:M}
    +1 OBJE @<XREF:OBJE>@     // chain to continued object //  {0:1}
    +1 REFN <USER_REFERENCE_NUMBER>  {0:M}
      +2 TYPE <USER_REFERENCE_TYPE>  {0:1}
    +1 RIN <AUTOMATED_RECORD_ID>  {0:1}
    +1 <<CHANGE_DATE>>  {0:1}
*/
