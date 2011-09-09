<?php
/**
 *
 */

/**
 *
 */
class ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     */
    protected $_parser  = null;
    
    /**
     *
     */
    protected $_gedcom  = null;
    
    /**
     *
     */
    public function setUp()
    {
        $this->_parser = new \Gedcom\Parser();
        $this->_gedcom = $this->_parser->parse('stresstestfiles/TGC551LF.ged');
    }
    
    /**
     *
     */
    public function testRecordCounts()
    {
        $this->assertEquals(count($this->_gedcom->getIndi()), 15);
        $this->assertEquals(count($this->_gedcom->getFam()), 7);
        $this->assertEquals(count($this->_gedcom->getSour()), 2);
        $this->assertEquals(count($this->_gedcom->getNote()), 33);
        $this->assertEquals(count($this->_gedcom->getObje()), 1);
        $this->assertEquals(count($this->_gedcom->getRepo()), 1);
    }
    
    /**
     *
     */
    public function testIndi()
    {
        
    }
    
    /**
     *
     */
    public function testFam()
    {
        
    }
    
    /**
     *
     */
    public function testObje()
    {
        
    }
    
    /**
     *
     */
    public function testRepo()
    {
        
    }
    
    /**
     *
     */
    public function testSour()
    {
        $sour = $this->_gedcom->getSour();
        
        $secondSource = $sour['SR2'];
        
        $this->assertEquals($secondSource->refId, 'SR2');
        $this->assertEquals($secondSource->titl, 'All I Know About GEDCOM, I Learned on the Internet');
        $this->assertEquals($secondSource->abbr, 'What I Know About GEDCOM');
        $this->assertEquals($secondSource->auth, 'Second Source Author');
        $this->assertEquals($secondSource->chan->date, '11 Jan 2001');
        $this->assertEquals($secondSource->chan->time, '16:21:39');
        $this->assertEquals($secondSource->rin, '2');
    }
    
    /**
     *
     */
    public function testNote()
    {
        $firstNote = current($this->_gedcom->getNote());
        
        $this->assertEquals($firstNote->note, 'Test link to a graphics file about the main Submitter of this file.');
        
        $this->assertEquals($firstNote->chan->date, '24 May 1999');
        $this->assertEquals($firstNote->chan->time, '16:39:55');
    }
}
