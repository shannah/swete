
<?php 

include("googleTranslatePlugin.php");

class MyListener implements googleTranslatePlugin_ProgressListener
{
	public   $total=0,
			 $current=0;

	public function setProgress($message, $numComplete, $numTotal)
	{
		$this->total = $numTotal;
		$this->current = $numComplete;
		print htmlspecialchars($message) . " (" . $numComplete . "/" . $numTotal . ")<br>";	
	}

};

class gtpTester
{
	private $gtp,
			$gtp2,
			$gtp3,
			$listener;
			
	const apiKey = '';

	public function __construct()
	{
		$this->gtp = new googleTranslatePlugin("en", "fr");
		$this->gtp2 = new googleTranslatePlugin("en", "fr");
		$this->gtp3 = new googleTranslatePlugin("en", "fr");
		$this->listener = new MyListener();
		$this->gtp->addProgressListener($this->listener);	
	}
	
	public function testTranslate()
	{
		//$expectedTranslations1 = array("Hello"=>"Bonjour");
	
		$this->gtp->setGoogleAPIKey(self::apiKey);
		
		$this->gtp->addSourceString("Hello");
		$this->gtp->addSourceString("My name is Kevin.");
		$this->gtp->addSourceString("My name is Joe.");
		$this->gtp->addSourceString("My name is Dave.");
		$this->gtp->addSourceString("The date is June 13, 2012.");
		$this->gtp->addSourceString("\"");
		$this->gtp->addSourceString("!@#$%^&*()<>");
		
		$startTime = microtime(true);
		$translations = $this->gtp->getTranslations();
		$endTime = microtime(true);
		print "Translation took " . (($endTime - $startTime)*1000) . " milliseconds<br>";
		if($translations["Hello"] != "Bonjour")
			return "Test case 1 failed for \"Hello\". Expected \"Bonjour\" but received \"" .$translations["Hello"] . "\"";
			
		if($translations["\""] != "\"")
			return "Test case 2 failed for \"\"\". Expected \"\"\" but received \"" .htmlspecialchars($translations["\""]) . "\"";
		
		print "<br>Translations:<br>";
		foreach($translations as $s=>$t)
			print  $s . " => "	. $t . "<br>";
		
		return 'OK';
	}
	
	//tests to see whether the plugin can handle large strings. The google API specifications allow for up to 5K chars.
	public function testLargeTranslate()
	{
		$s = "Mumia Abu-Jamal (born Wesley Cook[1] on April 24, 1954) is an African-American convict who murdered a Philadelphia police officer, Daniel Faulkner, in 1981 and is serving a life sentence.[2] He was sentenced to death at his first trial in July 1982, and his case became an international cause célèbre.[3]
				Born in Philadelphia, Abu-Jamal became involved in black nationalism in his youth, and was a member of the Black Panther Party until October 1970. Alongside his political activism, he became a radio journalist, eventually becoming President of the Philadelphia Association of Black Journalists.
				Supporters and opponents disagreed on the appropriateness of the death penalty, his guilt, and whether he received a fair trial.[4][5][6] He was described as perhaps the worlds best known death-row inmate.[7] During his imprisonment he has published several books and other commentaries, notably Live from Death Row (1995).
				In 2008, a three-judge panel of the U.S. Third Circuit Court of Appeals upheld the murder conviction but ordered a new capital sentencing hearing because the jury was improperly instructed.[8] Subsequently, the United States Supreme Court also allowed his conviction to stand[8] but ordered the appeals court to reconsider its decision as to the sentence.[9] In 2011, the Third Circuit again affirmed the conviction as well as its decision to vacate the death sentence,[10] and the District Attorney of Philadelphia announced that prosecutors would no longer seek the death penalty.[11] He was removed from death row in January 2012.
				Mumia Abu-Jamal (born Wesley Cook[1] on April 24, 1954) is an African-American convict who murdered a Philadelphia police officer, Daniel Faulkner, in 1981 and is serving a life sentence.[2] He was sentenced to death at his first trial in July 1982, and his case became an international cause célèbre.[3]
				Born in Philadelphia, Abu-Jamal became involved in black nationalism in his youth, and was a member of the Black Panther Party until October 1970. Alongside his political activism, he became a radio journalist, eventually becoming President of the Philadelphia Association of Black Journalists.
				Supporters and opponents disagreed on the appropriateness of the death penalty, his guilt, and whether he received a fair trial.[4][5][6] He was described as perhaps the worlds best known death-row inmate.[7] During his imprisonment he has published several books and other commentaries, notably Live from Death Row (1995).
				In 2008, a three-judge panel of the U.S. Third Circuit Court of Appeals upheld the murder conviction but ordered a new capital sentencing hearing because the jury was improperly instructed.[8] Subsequently, the United States Supreme Court also allowed his conviction to stand[8] but ordered the appeals court to reconsider its decision as to the sentence.[9] In 2011, the Third Circuit again affirmed the conviction as well as its decision to vacate the death sentence,[10] and the District Attorney of Philadelphia announced that prosecutors would no longer seek the death penalty.[11] He was removed from death row in January 2012.
				Mumia Abu-Jamal (born Wesley Cook[1] on April 24, 1954) is an African-American convict who murdered a Philadelphia police officer, Daniel Faulkner, in 1981 and is serving a life sentence.[2] He was sentenced to death at his first trial in July 1982, and his case became an international cause célèbre.[3]
				Born in Philadelphia, Abu-Jamal became involved in black nationalism in his youth, and was a member of the Black Panther Party until October 1970. Alongside his political activism, he became a radio journalist, eventually becoming President of the Philadelphia Association of Black Journalists.
				Supporters and opponents disagreed on the appropriateness of the death penalty, his guilt, and whether he received a fair trial.[4][5][6] He was described as perhaps the worlds best known death-row inmate.[7] During his imprisonment he has published several books and other commentaries, notably Live from Death Row (1995).
				In 2008, a three-judge panel of the U.S. Third Circuit Court of Appeals upheld the murder conviction but ordered a new capital sentencing hearing because the jury was improperly instructed.[8] Subsequently, the United States Supreme Court also allowed his conviction to stand[8] but ordered the appeals court to reconsider its decision as to the sentence.[9] In 2011, the Third Circuit again affirmed the conviction as well as its decision to vacate the death sentence,[10] and the District Attorney of Philadelphia announced that prosecutors would no longer seek the death penalty.[11] He was removed from death row in January 2012.
				Mumia Abu-Jamal (born Wesley Cook[1] on April 24, 1954) is an African-American convict who murdered a Philadelphia police officer, Daniel Faulkner, in 1981 and is serving a life sentence.[2] He was sentenced to death at his first trial in July 1982, and his case became an international cause célèbre.[3]
				Born in Philadelphia, Abu-Jamal became involved in black nationalism in his youth, and was a member of the Black Panther Party until October 1970. Alongside his political activism, he became a radio journalist, eventually becoming President of the Philadelphia Association of Black Journalists.
				Supporters and opponents disagreed on the appropriateness of the death penalty, his guilt, and whether he received a fair trial.[4][5][6] He was described as perhaps the worlds best known death-row inmate.[7] During his imprisonment he has published several books and other commentaries, notably Live from Death Row (1995).
				In 2008, a three-judge panel of the U.S. Third Circuit Court of Appeals upheld the murder conviction but ordered a new capital sentencing hearing because the jury was improperly instructed.[8] Subsequently, the United States Supreme Court also allowed his conviction to stand[8] but ordered the appeals court to reconsider its decision as to the sentence.[9] In 2011, the Third Circuit again affirmed the conviction as well as its decision to vacate the death sentence,[10] and the District Attorney of Philadelphia announced that prosecutors would no longer seek the death penalty.[11] He was removed from death row in January 2012.
				Mumia Abu-Jamal (born Wesley Cook[1] on April 24, 1954) is an African-American convict who murdered a Philadelphia police officer, Daniel Faulkner, in 1981 and is serving a life sentence.[2] He was sentenced to death at his first trial in July 1982, and his case became an international cause célèbre.[3]
				Born in Philadelphia, Abu-Jamal became involved in black nationalism in his youth, and was a member of the Black Panther Party until October 1970. Alongside his political activism, he became a radio journalist, eventually becoming President of the Philadelphia Association of Black Journalists.
				Supporters and opponents disagreed on the appropriateness of the death penalty, his guilt, and whether he received a fair trial.[4][5][6] He was described as perhaps the worlds best known death-row inmate.[7] During his imprisonment he has published several books and other commentaries, notably Live from Death Row (1995).
				In 2008, a three-judge panel of the U.S. Third Circuit Court of Appeals upheld the murder conviction but ordered a new capital sentencing hearing because the jury was improperly instructed.[8] Subsequently, the United States Supreme Court also allowed his conviction to stand[8] but ordered the appeals court to reconsider its decision as to the sentence.[9] In 2011, the Third Circuit again affirmed the conviction as well as its decision to vacate the death sentence,[10] and the District Attorney of Philadelphia announced that prosecutors would no longer seek the death penalty.[11] He was removed from death row in January 2012.
				Mumia Abu-Jamal (born Wesley Cook[1] on April 24, 1954) is an African-American convict who murdered a Philadelphia police officer, Daniel Faulkner, in 1981 and is serving a life sentence.[2] He was sentenced to death at his first trial in July 1982, and his case became an international cause célèbre.[3]
				Born in Philadelphia, Abu-Jamal became involved in black nationalism in his youth, and was a member of the Black Panther Party until October 1970. Alongside his political activism, he became a radio journalist, eventually becoming President of the Philadelphia Association of Black Journalists.
				Supporters and opponents disagreed on the appropriateness of the death penalty, his guilt, and whether he received a fair trial.[4][5][6] He was described as perhaps the worlds best known death-row inmate.[7] During his imprisonment he has published several books and other commentaries, notably Live from Death Row (1995).
				In 2008, a three-judge panel of the U.S. Third Circuit Court of Appeals upheld the murder conviction but ordered a new capital sentencing hearing because the jury was improperly instructed.[8] Subsequently, the United States Supreme Court also allowed his conviction to stand[8] but ordered the appeals court to reconsider its decision as to the sentence.[9] In 2011, the Third Circuit again affirmed the conviction as well as its decision to vacate the death sentence,[10] and the District Attorney of Philadelphia announced that prosecutors would no longer seek the death penalty.[11] He was removed from death row in January 2012.
				";
		$this->gtp2->setGoogleAPIKey(self::apiKey);
		$this->gtp2->addSourceString($s);
		
		$translations = $this->gtp2->getTranslations();
		
		if(!array_key_exists($s, $translations) || $translations[$s] == NULL || strlen($translations[$s]) < 1000)
			return "Failed";
			
		return 'OK';
	
	}
	
	public function testListener()
	{
		if($this->listener->current != 7 || $this->listener->total != 7 )
			return "Listener was not called 7 times!";
		else return "OK";
	
	}
	
	//test a large number of requests.
	public function testManyRequests()
	{
		$this->gtp3->setGoogleAPIKey(self::apiKey);
		$s = "Hello World";
		
		for($i=0;$i<500;$i++)
			$this->gtp3->addSourceString($s . $i);
		
		$startTime = microtime(true);
		$translations = $this->gtp3->getTranslations();
		$endTime = microtime(true);
		print "500 Translations took " . (($endTime - $startTime)*1000) . " milliseconds<br>";
		
		foreach($translations as $t)
			if($t == NULL)
				return "One or more of the translations failed!";
			
		return 'OK';
	}

}


$tester = new gtpTester();

//print 'Testing getTranslations(): ' . $tester->testTranslate() . "<br>";
//print 'Testing getLargeTranslations(): ' . $tester->testLargeTranslate() . "<br>";
//print 'Testing listener: ' . $tester->testListener() . "<br>";
print 'Testing Many Requests: ' . $tester->testManyRequests() . "<br>";
print 'Done<br>';

?>