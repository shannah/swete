<?php 

/*	XLIFFListener interface - the event handler for XLIFFReader events. Users should implement an XLIFFListener class to listen for events, 
 * 	add it using addXliffListener() in the XLIFFReader class, then call parse() to begin parsing the XLIFF file. The appropriate XLIFFListener 
 * 	callback functions will automatically be called on an event.
 *  Author: Kevin Chow
 *  Date: June 26, 2012
 */

class XLIFFEvent_Start 
{

};

class XLIFFEvent_End 
{

};

class XLIFFEvent_StartFile
{
	public  $file, 
		   	$sourceLanguage, 
			$targetLanguage, 
			$datatype;
};

class XLIFFEvent_EndFile 
{

};

class XLIFFEvent_Translation
{
	public 	$source,
			$target;
};

interface XLIFFListener
{
	//called when starting to parse the XLIFF file. Takes an XLIFFEvent_Start event as a parameter.
	//Available members in $eStart: none
	public function onStart(XLIFFEvent_Start $eStart);
	
	//called when finished parsing the XLIFF file. Takes an XLIFFEvent_End event as a parameter.
	//Available members in $eEnd: none
	public function onEnd(XLIFFEvent_End $eEnd);
	
	//called when starting a new translation file. Takes an XLIFFEvent_StartFile event as a parameter.
	//Available members in $eStartFile: file (String), sourceLanguage (String), targetLanguage (String), datatype (String)
	public function onStartFile(XLIFFEvent_StartFile $eStartFile);
	
	//called when ending a translation file. Takes an XLIFFEvent_EndFile event as a parameter.
	//Available members in $eEndFile: none
	public function onEndFile(XLIFFEvent_EndFile $eEndFile);
	
	//called when a translation is read. Takes an XLIFFEvent_Translation event as a parameter.
	//Available members in $eTranslation: source (String), target (String)
	public function onTranslation(XLIFFEvent_Translation $eTranslation);
};

?>