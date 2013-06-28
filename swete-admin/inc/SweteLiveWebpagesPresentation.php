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
class SweteLiveWebpagesPresentation {

	public function tableContent($job){
	
		$webpages = $job->getLiveWebpageTranslatables();
		$compiled = $job->isCompiled();
		if (count($webpages)==0){
			$content = "";
			
		}else{
	
			$content = 
					'<tr>
						<th>Url</th>
						<th># Words</th>
						<th>Actions</th>';
			if ($compiled){
				$content .= '   <th colspan=3>Preview</th>';
			}
			$content .='		</tr>';
			
			foreach( $webpages as $webpage ){
			
				$content .= '<tr class="listing" data-http-request-log-id="'.$webpage['http_request_log_id'].'" />';
				
				
				//job_translatable_id, word_count, url
				$job_translatable_id = $webpage['job_translatable_id'];
				$words = $webpage['word_count'];
				$url = $webpage['url'];
				
				$content .= '<td class="field-content"><a href="'.$url.'" target="_blank">'.$url.'</a>'.
					'</td><td class="field-content">'.$words.
					'</td>';
					
				
				if ($compiled){
					//translate button
					if (!$job->isClosed()){
						$content .= '<td class="translate webpage-action">
							<a href="index.php?-action=translate&-table=job_translatable&-nowrapper=1&job_translatable_id='.$job_translatable_id.
							'" target="_blank">Translate</a>
							</td>';
					}else{
						$content .= '<td></td>';
					}
					
					//preview
					$content .= '<td class="preview-source webpage-action">
							<a href="'.$job->getSite()->getProxyWriter()->proxifyUrl($url).'" target="_blank">Source</a>
						</td>';
					
					//preview buttons
					/*
					$content .= '<td class="preview-source webpage-action">
							<a href="index.php?-action=swete_preview_job_page&&-job_translatable_id='.$job_translatable_id.
							'&&-translation=source" target="_blank">Source</a>
						</td>
						
						<td class="preview-previous webpage-action">
							<a href="index.php?-action=swete_preview_job_page&&-job_translatable_id='.$job_translatable_id.
							'&&-translation=previous" target="_blank">Previous Translation</a>
						</td>
						
						<td class="preview-new webpage-action">
							<a href="index.php?-action=swete_preview_job_page&&-job_translatable_id='.$job_translatable_id.
							'&&-translation=new" target="_blank">New Translation</a>
						</td>';
					*/
					
				}else{
					$content .= '<td class="remove webpage-action">Remove</td>';
				}
				
				
				
				$content .= '</tr>';
				
			}
			
		}
		
		return $content;
		

	}
}