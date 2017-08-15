<?php
interface XFTranslationDictionary {
	public function getTranslations(array $sources, $minStatus=3, $maxStatus=5);
}