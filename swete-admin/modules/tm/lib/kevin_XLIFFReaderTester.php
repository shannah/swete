<?php 

include ("XLIFFReader.php");

class MyListener implements XLIFFListener
{
	public $s,
	$expected;	//keeps track of the order these functions were called in by adding characters to the string.
	
	public function __construct()
	{
		$this->expected = "SsttestttestttteE";
	}
	
	//called when starting to parse the XLIFF file.
	public function onStart(XLIFFEvent_Start $start)
	{
		$this->s .= "S";	
	}
	
	//called when finished parsing the XLIFF file.
	public function onEnd(XLIFFEvent_End $end)
	{
		$this->s .= "E";
	}
	
	//called when starting a new translation file
	public function onStartFile(XLIFFEvent_StartFile $startFile)
	{
		$this->s .= "s";
	}
	
	//called when ending a translation file
	public function onEndFile(XLIFFEvent_EndFile $endFile)
	{
		$this->s .= "e";
	}
	
	//called when a translation is read.
	public function onTranslation(XLIFFEvent_Translation $translation)
	{
		$this->s .= "t";
	}
	
	//checks to make sure that the callback functions were called in the expected order
	public function isOK()
	{
		return $this->s === $this->expected;
	}

};

class XLIFFReaderTester
{
	private $reader, $listener;

	public function __construct()
	{
		$this->reader = new XLIFFReader();
		$this->listener = new MyListener();
	}
	
	public function testParse()
	{
		$this->reader->addXliffListener($this->listener);
		$this->reader->parse("output.xliff");
		
		if(!$this->listener->isOK())
			return 'The callbacks were not called in the correct order. Expected ' . $this->listener->expected . " but received " . $this->listener->s . ".";
		return 'OK';
		//else return 'FAILED';
	}
	
	public function testaddRemoveListeners()
	{
		$ok = false;
		$this->reader->addXLIFFListener($this->listener);
		$this->reader->parse("output.xliff");
		
		if($this->listener->s == "")
			return "Listener was not added successfully.";
		
		$this->listener->s = "";
			
		$this->reader->removeXliffListener($this->listener);
		$this->reader->parse("output.xliff");
		
		if($this->listener->s == "") 	//listener should not be called.
			$ok = true;
		
		$this->listener->s = "";
		
		if($ok) return 'OK';
		else return 'The listener was not removed successfully.' . $this->listener->s;
		
	}
};

//TESTING CODE:
$tester = new XLIFFReaderTester();
print "testing add/remove listeners(): " . $tester->testaddRemoveListeners() . "<br>";
print "testing parse(): " . $tester->testParse() . "<br>";
print "Done.<br>";








?>