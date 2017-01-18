<?php
/*
 * Xataface Translation Memory Module
 * Copyright (C) 2011  Steve Hannah <steve@weblite.ca>
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Library General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Library General Public License for more details.
 * 
 * You should have received a copy of the GNU Library General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA  02110-1301, USA.
 *
 */
import('PHPUnit.php');
import('modules/tm/lib/XFTranslationMemory.php');
class modules_tm_XFTranslationMemoryTest extends PHPUnit_TestCase {

	private $mod = null;
	
	function modules_calendar_RepeatEventTest( $name = 'modules_tm_XFTranslationMemoryTest'){
		$this->PHPUnit_TestCase($name);
		
	}

	function setUp(){
		$base = 'xf_tm_';
		$tables = array(
			$base.'records',
			$base.'record_strings',
			$base.'strings',
			$base.'translations',
			$base.'translations_comments',
			$base.'translations_score',
			$base.'translations_status',
			$base.'translation_memories',
			$base.'translation_memories_managers',
			$base.'translation_memory_translations',
			$base.'translation_statuses',
			$base.'workflows',
			$base.'workflow_records',
			$base.'workflow_steps',
			$base.'workflow_step_changes',
			$base.'workflow_step_panels',
			$base.'workflow_step_panel_actions',
			$base.'workflow_step_panel_members',
			$base.'workflow_strings'
			
		
		);
		$numTables = count($tables);
		$count = 0;
		$missed = $tables;
		while ($count < $numTables*$numTables and $missed ){
			$missed = array();
			foreach ($tables as $table){
				try {
					self::q("delete from `".$table."`");
				} catch (Exception $ex){
					error_log("Failed to delete $table : ".$ex->getMessage());
					$missed[] = $table;
				}	
			}
			$tables = $missed;
			$count++;
		}
		
	}
	

	
	
	
	
	
	function tearDown(){
		
		

	}
	
	function testImport(){
		
		$mod = Dataface_ModuleTool::loadModule('modules_tm');
		
		// Create a translation memory from thin air.
		$tm1 = XFTranslationMemory::createTranslationMemory('TM 1', 'en', 'fr');
		$tm2 = XFTranslationMemory::createTranslationMemory('TM 2', 'en', 'fr');
		
		$tm1->addTranslation('dog', 'chien');
		$tm1->setTranslationStatus('dog', 'chien', XFTranslationMemory::TRANSLATION_SUBMITTED);
		$tm1->addTranslation('cat', 'chat');
		$tm1->setTranslationStatus('cat', 'chat', XFTranslationMemory::TRANSLATION_SUBMITTED);
		$tm1->addTranslation('car', 'voiture');
		$tm1->setTranslationStatus('car', 'voiture', XFTranslationMemory::TRANSLATION_SUBMITTED);
		sleep(2);
		$tm2->addTranslation('man', 'homme');
		$tm2->setTranslationStatus('man','homme', XFTranslationMemory::TRANSLATION_APPROVED);
		$tm2->addTranslation('cat', 'chat');
		$tm2->setTranslationStatus('cat', 'chat', XFTranslationMemory::TRANSLATION_APPROVED);
		$tm2->addTranslation('city', 'ville');
		$tm2->setTranslationStatus('city', 'ville', XFTranslationMemory::TRANSLATION_APPROVED);
		
		$query = array('dog', 'cat', 'car', 'man', 'city');
		$expected = array('chien', 'chat', 'voiture', null, null);
		$res = $tm1->getTranslations($query);
		$this->assertEquals($expected, $res);
		
		$res = $tm1->import($tm2);
		$this->assertTrue($res);
		if ( !$res ){
			error_log($tm1->error_message);
		}
		
		$query = array('dog', 'cat', 'car', 'man', 'city');
		$expected = array('chien', 'chat', 'voiture', 'homme', 'ville');
		$res = $tm1->getTranslations($query);
		$this->assertEquals($expected, $res);
		
		$expected = array(null, 'chat', null, 'homme', 'ville');
		// Now get only approved strings.
		// This will include the strings that were imported from tm2 because they were
		// approved and the approvals should have been copied too.
		// but it won't include the original strings because they have status submitted
		$res = $tm1->getTranslations($query, XFTranslationMemory::TRANSLATION_APPROVED, XFTranslationMemory::TRANSLATION_APPROVED);
		$this->assertEquals($expected, $res);
		
		
		
	}
	
	
	function testTranslationMemory(){
		$mod = Dataface_ModuleTool::loadModule('modules_tm');
		
		// Create a translation memory from thin air.
		$rec = new Dataface_Record('xf_tm_translation_memories', array());
		$rec->setValues(array(
			'translation_memory_name'=>"Test",
			'source_language'=>'en',
			'destination_language'=>'es'
		));
		
		$tm = new XFTranslationMemory($rec);
		
		$this->assertEquals('en', $tm->getSourceLanguage());
		$this->assertEquals('es', $tm->getDestinationLanguage());
		$this->assertEquals($rec, $tm->getRecord());
		
		$dtm = XFTranslationMemory::getDefaultTranslationMemory('en','es');
		$this->assertTrue($dtm instanceof XFTranslationMemory);
		
		$res = self::q("select count(*) from xf_tm_translation_memories");
		list($count) = mysql_fetch_row($res);
		@mysql_free_result($res);
		$this->assertEquals(1, $count, "Should only be one translation memory: the default one we just inserted.");
		
		
		$this->assertEquals("en", $dtm->getSourceLanguage());
		$this->assertEquals("es", $dtm->getDestinationLanguage());
		
		$res = self::q("select count(*) from xf_tm_records");
		list($count) = mysql_fetch_row($res);
		@mysql_free_result($res);
		$this->assertEquals(1, $count, "Should be one row in records table: the default one we just added.");
		$row = df_get_record('xf_tm_records', array('translation_memory_id'=>'='.$dtm->getRecord()->val('translation_memory_id')));
		$this->assertTrue($row instanceof Dataface_Record);
		$this->assertEquals('*', $row->val('record_id'));
		
		
		$dtm2 = XFTranslationMemory::getDefaultTranslationMemory('en','es');
		$this->assertEquals($dtm, $dtm2, 'Default translation memory should be cached so should always return the same object for the same pair.');
		
		
		$str = $dtm->addString('Test string', 'en');
		
		$this->assertTrue($str instanceof Dataface_Record);
		$this->assertEquals('xf_tm_strings', $str->table()->tablename);
		$this->assertEquals('Test string', $str->val('string_value'));
		$this->assertEquals('Test string', $str->val('normalized_value'));
		$this->assertEquals(md5('Test string'), $str->val('hash'));
		
		$str2 = df_get_record('xf_tm_strings', array('string_id'=>'='.$str->val('string_id')));
		$this->assertTrue($str2 instanceof Dataface_Record);
		$this->assertEquals('Test string', $str2->val('string_value'));
		$this->assertEquals($str->val('string_id'), $str2->val('string_id'));
		
		$str3 = XFTranslationMemory::findString('Test string', 'en');
		$this->assertTrue($str3 instanceof Dataface_Record);
		$this->assertEquals($str->val('string_id'), $str3->val('string_id'));
		
		$str4 = XFTranslationMemory::findString('Test string', 'es');
		$this->assertEquals(null, $str4, "String exists in english but not spanish so should return null.");
		
		
		$tr = $dtm->findTranslation('Test string', 'String teste');
		$this->assertEquals(null, $tr);
		
		$tr = $dtm->addTranslation('Test string', 'String teste', 'shannah');
		$this->assertTrue($tr instanceof Dataface_Record);
		$this->assertEquals('xf_tm_translations', $tr->table()->tablename);
		$this->assertEquals($str->val('string_id'), $tr->val('string_id'));
		$this->assertEquals('String teste', $tr->val('translation_value'));
		$this->assertEquals('String teste', $tr->val('normalized_translation_value'));
		$this->assertEquals(md5('String teste'), $tr->val('translation_hash'));
		
		
		
		// Now make sure we can't add the same string twice
		$str5 = XFTranslationMemory::addString('Test string', 'en');
		$this->assertEquals($str->val('string_id'), $str5->val('string_id'));
		
		
		// Try adding a translation using string id only
		$tr2 = $dtm->addTranslation($str->val('string_id'), 'String teste2', 'shannah');
		$this->assertEquals($str->val('string_id'), $tr2->val('string_id'));
		$this->assertEquals('String teste2', $tr2->val('translation_value'));
		
		$trf = $dtm->findTranslation('Test string', 'String teste');
		$this->assertEquals($trf->val('translation_id'), $tr->val('translation_id'));
		
		$trf2 = $dtm->findTranslation($str->val('string_id'), 'String teste');
		$this->assertEquals($trf2->val('translation_id'), $tr->val('translation_id'));
		$this->assertEquals($trf2->val('string_id'), $str->val('string_id'));
		
		// Try adding the same translation
		//echo "About to add our troubled string";
		$tr3 = $dtm->addTranslation('Test string', 'String teste', 'shannah');
		$this->assertEquals($tr->val('translation_id'), $tr3->val('translation_id'));
		
		// Make sure strings are case sensitive
		$tr4 = $dtm->addTranslation('Test String', 'String Teste', 'shannah');
		$this->assertTrue($tr4->val('translation_id') != $tr->val('translation_id'));
		$this->assertTrue($tr4->val('string_id') != $tr->val('string_id'));
		
		
		$this->assertTrue($dtm->containsTranslation('Test String', 'String Teste'));
		$this->assertTrue($dtm->containsTranslation('Test string', 'String teste'));
		$this->assertTrue(!$dtm->containsTranslation('Test2', '2Teste'));
		$this->assertTrue(!$dtm->containsTranslation('Test String', 'String teste'));
		$this->assertTrue(!$dtm->containsTranslation('Test string', 'foo'));
		
		$sources = array('foo','bar');
		$translations = $dtm->getTranslations($sources);
		$this->assertEquals(
			array(
				0 => null,
				1 => null
			),
			$translations
		);
		
		$sources = array('Test String','Test string');
		$translations = $dtm->getTranslations($sources);
		$this->assertEquals(
			array(
				0 => null,
				1 => null
			),
			$translations
		);
		
		$dtm->setTranslationStatus('Test String', 'String Teste', XFTranslationMemory::TRANSLATION_APPROVED, 'shannah');
		$translations = $dtm->getTranslations($sources);
		$this->assertEquals(
			array(
				0 => 'String Teste',
				1 => null
			),
			$translations
		);
		
		$translations = $dtm->getTranslations($sources, 3,4); // Only get the submitted but not the approved
		$this->assertEquals(
			array(
				0 => null,
				1 => null
			),
			$translations
		);
		sleep(1); // Necessary so that the approval statuses get different timestamps
		$dtm->setTranslationStatus('Test String', 'String Teste', XFTranslationMemory::TRANSLATION_SUBMITTED, 'shannah');
		$translations = $dtm->getTranslations($sources, 5); // Only get the submitted but not the approved
		$this->assertEquals(
			array(
				0 => null,
				1 => null
			),
			$translations
		);
		
		
		
		// Now let's test some variable strings
		
		$vstr = 'My name is <v id="1">Steve</v>';
		$vstr2 = 'My name is <v id="1">Ruth</v>';
		$vstrRec = $dtm->addString($vstr, 'en');
		
		$vstrRec2 = $dtm->findString($vstr2, 'en');
		$this->assertEquals($vstrRec->val('string_id'), $vstrRec2->val('string_id'));
		$this->assertEquals($vstr2, $vstrRec2->val('string_value'));
		
		$vtr1 = 'Mon nom est <v id="1">Steve</v>';
		$vtr2 = 'Mon nom est <v id="1">Ruth</v>';
		$vtrRec1 = $dtm->addTranslation($vstr, $vtr1, 'shannah');
		
		$vtrRec2 = $dtm->findTranslation($vstr2, $vtr2);
		$this->assertEquals($vtrRec1->val('translation_id'), $vtrRec2->val('translation_id'));
		$this->assertEquals($vtr2, $vtrRec2->val('translation_value'));
		
		$dtm->addTranslation('foo', 'foofrench');
		$dtm->setTranslationStatus('foo', 'foofrench', 3);
		$dtm->addTranslation('bar', 'barfrench');
		$dtm->setTranslationStatus('bar', 'barfrench', 3);
		
		$result = $dtm->getFlaggedTranslations(array('foo', 'bar'));
		$expected = array(0=>null, 1=>null);
		$this->assertEquals($expected, $result);
		
		$dtm->flag('foo', 'steve');
		$result = $dtm->getFlaggedTranslations(array('foo', 'bar'));
		$expected = array(0 => 'foofrench', 1=>null);
		$this->assertEquals($expected, $result);
		
		$dtm->flag('foo', 'steve', 0);
		$result = $dtm->getFlaggedTranslations(array('foo', 'bar'));
		$expected = array(0=>null, 1=>null);
		$this->assertEquals($expected, $result);
		
		
		$dtm->flag('bar', 'steve');
		$result = $dtm->getFlaggedTranslations(array('foo', 'bar'));
		$expected = array(0 => null, 1=>'barfrench');
		$this->assertEquals($expected, $result);
		
		
		
		
		
		
		
	
		
		
	}
	
	
	public function testFillVars(){
	
		$strs = array(
			array(
				'template'=> 'Mon nom est <v id="1"></v>',
				'string'=> 'My name is <v id="1">Steve</v>',
				'out' => 'Mon nom est <v id="1">Steve</v>'
			),
			
			array(
				'template'=>'<v id="1"></v> est le mieux jour de la semaine pour <v id="2"></v>',
				'string'=>'<v id="1">Saturday</v> is the best day of the week for <v id="2">Steve</v>',
				'out'=>'<v id="1">Saturday</v> est le mieux jour de la semaine pour <v id="2">Steve</v>'
			)
		
		);
		
		foreach ($strs as $str){
		
			$this->assertEquals($str['out'], TMTools::fillVars($str['template'], $str['string']));
		}
	}
	
	public function testNormalize(){
	
		$strs = array(
		
		
			'Steve  Hannah' => 'Steve Hannah',
			'<span> Steve hannah</span>'=>'<span> Steve hannah</span>',
			' <span>Steve </span>  hannah ' => '<span>Steve </span> hannah',
			'Today\'s date is <v id="1">July 24th</v>' => 'Today\'s date is <v id="1"></v>',
			'Welcome to <vl>Mars</vl>' => 'Welcome to <vl>Mars</vl>',
			'Welcome to <vl id="1">Mars</vl>' => 'Welcome to <vl id="1">Mars</vl>'
		);
		
		foreach ($strs as $k=>$v){
			$this->assertEquals($v, TMTools::normalize($k));
		}
	}
	
	
	public function testParsing(){
	
		$strs = array(
			'<span id="2">*</span> First name' => '<g id="1">*</g> First name',
			'Hello <span class="foo">Steve</span>' => 'Hello <g id="1">Steve</g>',
			'<span class="hello">Hello</span> <span class="steve">Steve</span>'=>'<g id="1">Hello</g> <g id="2">Steve</g>',
			'<a href="#"><span class="hello">Hello</span> World</a>'=>'<g id="1"><g id="2">Hello</g> World</g>',
			'John was here: <img src="foo"/> Now'=>'John was here: <x id="1"/> Now',
			'John < Bill' => 'John < Bill',
			'John <Bill' => 'John <Bill',
			'John > Bill' => 'John > Bill',
			'John < <span>Bill</span>' => 'John &lt; <g id="1">Bill</g>',
			'John < <span data-swete-translate="1">Bill</span>' => 'John &lt; <v id="1">Bill</v>'
		);
	
		foreach ($strs as $source=>$dest){
			try {
				unset($params);
				$this->assertEquals(
					$dest,
					TMTools::encode($source, $params)
				);
				//echo '[encode: '.TMTools::encode($source, $params).']';
				
				$this->assertEquals(
					$source,
					TMTools::decode($dest, $params)
				);
				
				$this->assertEquals(
					TMTools::encode(
						TMTools::encode($source, $params),
						$params
					),
					TMTools::encode(
						TMTools::encode(TMTools::encode($source, $params), $params),
						$params
					)
				);
				
			} catch (Exception $ex){
				echo "Failed: $source to $dest";
				throw $ex;
			}
		}
		
	
	}
	
	
	static function q($sql){
		$res = mysql_query($sql, df_db());
		if ( !$res ) throw new Exception(mysql_error(df_db()));
		return $res;
	}
	
		


}


// Add this test to the suite of tests to be run by the testrunner
Dataface_ModuleTool::getInstance()->loadModule('modules_testrunner')
		->addTest('modules_tm_XFTranslationMemoryTest');

$mod = Dataface_ModuleTool::loadModule('modules_tm');
$jt = Dataface_JavascriptTool::getInstance();
$jt->addPath(dirname(__FILE__).'/../js', $mod->getBaseURL().'/js');
$jt->import('xataface/modules/tm/tests/test_string_parser.js');