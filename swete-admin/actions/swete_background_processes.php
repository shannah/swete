<?php
/**
 * SWeTE Server: Simple Website Translation Engine
 * Copyright (C) 2012  Web Lite Translation Corp.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class actions_swete_background_processes {
	function handle($params){
		set_time_limit(0);
		@session_write_close();
		header('Connection: close');
		$out = '//OK';
		header('Content-Type: text/javascript; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
		header('Content-Length: '.strlen($out));
		echo $out;
		flush();
		
		require_once 'inc/BackgroundProcess.php';
		BackgroundProcess::runProcesses();
		
	}
}