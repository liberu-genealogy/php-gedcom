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
    public function testNoErrors()
    {
        $this->assertEquals(1, count($this->_parser->getErrors()));
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
        
        $this->assertEquals($secondSource->getId(), 'SR2');
        $this->assertEquals($secondSource->getTitl(), 'All I Know About GEDCOM, I Learned on the Internet');
        $this->assertEquals($secondSource->getAbbr(), 'What I Know About GEDCOM');
        $this->assertEquals($secondSource->getAuth(), 'Second Source Author');
        $this->assertEquals($secondSource->getChan()->getDate(), '11 Jan 2001');
        $this->assertEquals($secondSource->getChan()->getTime(), '16:21:39');
        $this->assertEquals($secondSource->getRin(), '2');
    }
    
    /**
     *
     */
    public function testNote()
    {
        $firstNote = current($this->_gedcom->getNote());
        
        $this->assertEquals($firstNote->getNote(), 'Test link to a graphics file about the main Submitter of this file.');
        
        $this->assertEquals($firstNote->getChan()->getDate(), '24 May 1999');
        $this->assertEquals($firstNote->getChan()->getTime(), '16:39:55');
    }
}
