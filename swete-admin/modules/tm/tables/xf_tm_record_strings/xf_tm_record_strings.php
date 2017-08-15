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
class tables_xf_tm_record_strings {
	const TRANSLATION_STATUS_APPROVED=1;
	function __sql__(){
	
		$lang = substr(Dataface_Application::getInstance()->_conf['lang'], 0, 2);
		$query = Dataface_Application::getInstance()->getQuery();
		if ( @$query['-tm_language'] ) $lang = substr($query['-tm_language'], 0, 2);
	
		return "select
			rs.*,
			s.string_value,
			(
				select 
					translation_value
				from 
					xf_tm_translations tt
					inner join xf_tm_translations_log ttl on tt.translation_id=ttl.translation_id
				where 
					tt.string_id=rs.string_id
					and tt.language='".addslashes($lang)."'
					and ttl.translation_status_id=".self::TRANSLATION_STATUS_APPROVED."
					and ttl.translation_memory_id=r.translation_memory_id
				order by 
					ttl.date_created desc
				limit 1
			) as translation_value
			
			from 
				xf_tm_record_strings rs
				left join xf_tm_records r on r.record_id=rs.record_id
			";
					
				
	}
}