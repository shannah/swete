<?php
class tables_path_aliases {
	function __import__text(&$data, $defaultValues=array()) {
		$records = array();
		
		$rows = preg_split("/\\r\\n|\\r|\\n/", $data);
		foreach ($rows as $row) {
			if (!trim($row)) continue;
			$parts = preg_split("/\\s/", $row);
			if (count($parts) != 2) continue;
			$record = new Dataface_Record('path_aliases', array());
			$record->setValues($defaultValues);
			$record->setValues(array(
				'name' => $parts[0],
				'alias' => $parts[1]
			));
			$records[] = $record;
		}
		return $records;
	}
}