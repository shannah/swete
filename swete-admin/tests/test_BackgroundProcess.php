<?php
import('PHPUnit.php');
require_once 'inc/ProxyServer.php';

class test_BackgroundProcess extends PHPUnit_TestCase {

	function test_ProxyServer( $name = 'test_BackgroundProcess'){
		$this->PHPUnit_TestCase($name);
		
		
		
		
	}

	function setUp(){
		
		
		
	}
	
	
	
	
	
	
	
	function tearDown(){
		
		
	}
	
	
		

		
	function testProcesses(){
		SweteDb::q('delete from background_processes');
		require_once 'inc/BackgroundProcess.php';
		$process = new BackgroundProcess();
		$this->assertEquals(null, $process->getProcessId());
		$this->assertEquals(false, $process->isClean());
		$process->save();
		$prec = df_get_record('background_processes', array('process_id'=>'='.$process->getProcessId()));
		$this->assertTrue($prec instanceof Dataface_Record);
		$this->assertEquals($process->getProcessId(), $prec->val('process_id'));
		$this->assertEquals('BackgroundProcess', $prec->val('process_class'));
		$this->assertEquals(0, intval($prec->val('running')));
		$this->assertEquals(0, intval($prec->val('complete')));
		$this->assertEquals(0, intval($prec->val('error')));
		
		$process->start();
		$prec = df_get_record('background_processes', array('process_id'=>'='.$process->getProcessId()));
		$this->assertTrue($prec instanceof Dataface_Record);
		$this->assertTrue(isset($prec));
		if ( $prec ){
			$this->assertEquals($process->getProcessId(), $prec->val('process_id'));
			$this->assertEquals('BackgroundProcess', $prec->val('process_class'));
			$this->assertEquals(0, intval($prec->val('running')));
			$this->assertEquals(1, intval($prec->val('complete')));
			$this->assertEquals(0, intval($prec->val('error')));
		}
		
		
		
		$process = new BackgroundProcess();
		$this->assertEquals(null, $process->getProcessId());
		$this->assertEquals(false, $process->isClean());
		$process->save();
		$prec = df_get_record('background_processes', array('process_id'=>'='.$process->getProcessId()));
		$this->assertTrue($prec instanceof Dataface_Record);
		$this->assertEquals($process->getProcessId(), $prec->val('process_id'));
		$this->assertEquals('BackgroundProcess', $prec->val('process_class'));
		$this->assertEquals(0, intval($prec->val('running')));
		$this->assertEquals(0, intval($prec->val('complete')));
		$this->assertEquals(0, intval($prec->val('error')));
		
		BackgroundProcess::runProcess($process->getProcessId());
		$prec = df_get_record('background_processes', array('process_id'=>'='.$process->getProcessId()));
		$this->assertTrue($prec instanceof Dataface_Record);
		$this->assertTrue(isset($prec));
		if ( $prec ){
			$this->assertEquals($process->getProcessId(), $prec->val('process_id'));
			$this->assertEquals('BackgroundProcess', $prec->val('process_class'));
			$this->assertEquals(0, intval($prec->val('running')));
			$this->assertEquals(1, intval($prec->val('complete')));
			$this->assertEquals(0, intval($prec->val('error')));
		}
		
		$process = new BackgroundProcess();
		$this->assertEquals(null, $process->getProcessId());
		$this->assertEquals(false, $process->isClean());
		$process->save();
		$prec = df_get_record('background_processes', array('process_id'=>'='.$process->getProcessId()));
		$this->assertTrue($prec instanceof Dataface_Record);
		$this->assertEquals($process->getProcessId(), $prec->val('process_id'));
		$this->assertEquals('BackgroundProcess', $prec->val('process_class'));
		$this->assertEquals(0, intval($prec->val('running')));
		$this->assertEquals(0, intval($prec->val('complete')));
		$this->assertEquals(0, intval($prec->val('error')));
		
		$res = BackgroundProcess::runProcesses();
		$this->assertTrue($res);
		if ( $res ){
			$prec = df_get_record('background_processes', array('process_id'=>'='.$process->getProcessId()));
			
			$this->assertTrue($prec instanceof Dataface_Record);
			$this->assertTrue(isset($prec));
			if ( $prec ){
				$this->assertEquals($process->getProcessId(), $prec->val('process_id'));
				$this->assertEquals('BackgroundProcess', $prec->val('process_class'));
				$this->assertEquals(0, intval($prec->val('running')));
				$this->assertEquals(1, intval($prec->val('complete')));
				$this->assertEquals(0, intval($prec->val('error')));
			}
		}
			
		
	
	}
		
		
			
	
	static function q($sql){
		$res = xf_db_query($sql, df_db());
		if ( !$res ) throw new Exception(xf_db_error(df_db()));
		return $res;
	}
	
		


}


// Add this test to the suite of tests to be run by the testrunner
Dataface_ModuleTool::getInstance()->loadModule('modules_testrunner')
		->addTest('test_BackgroundProcess');
