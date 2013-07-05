<?php /* Smarty version 2.6.18, created on 2013-07-03 00:35:24
         compiled from RecordNavMenu.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'RecordNavMenu.html', 54, false),array('modifier', 'urlencode', 'RecordNavMenu.html', 76, false),array('block', 'translate', 'RecordNavMenu.html', 150, false),)), $this); ?>
<?php if ($this->_tpl_vars['ENV']['mode'] == 'browse' && $this->_tpl_vars['record']): ?>
<?php $this->assign('table', $this->_tpl_vars['record']->table()); ?>
<?php $this->assign('relationships', $this->_tpl_vars['table']->getRelationshipsAsActions()); ?>
<?php if (count($this->_tpl_vars['relationships']) > 0): ?>	
<script language="javascript" type="text/javascript"><!--
	<?php echo '
	require(DATAFACE_URL+\'/js/ajax.js\');
		// loads the ajax library
	if ( !window.Dataface_Record ){
		require(DATAFACE_URL+\'/js/Dataface/Record.js\');
			// A utility class for working with records.
	}
	
	/**
	 * Given the id for a record of the form tablename?key1=val1&key2=val2/relationshipname
	 * or tablename?key1=val1&key2=val2, this will produce a URL to link to the record\'s
	 * view tab.
	 * @param string id The id of the record.
	 * @returns string URL for the record.
	 */
	function getNavTreeUrl(id){
		var parts = id.split(\'/\');
		var last = parts[parts.length-1];
		if ( last.indexOf(\'?\') >= 0 ){
			var record = new Dataface_Record(document.recordIndex[last]);
			return record.getURL(\'-action=ajax_nav_tree_node\')+\'&--orig-table='; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['table'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
<?php echo '\';
		} else {
			var nextlast = parts[parts.length-2];
			var record = new Dataface_Record(document.recordIndex[nextlast]);
			return record.getURL(\'-action=ajax_nav_tree_node&-relationship=\'+escape(last))+\'&--orig-table='; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['table'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
<?php echo '\';
		}
	}
	
	/**
	 * Expands a node specified by the given id.  This is smart.  If the node is already
	 * loaded then it just displays it.  If it is not yet loaded, it will send an 
	 * ajax request for the node data then display the node.
	 *
	 * @param DOM_Object img The img tag that was clicked to expand the node (treeCollapsed.gif)
	 * @param string id The id for the node.  The id is of the form:
	 *			relationship.name/recordid/relationship.name/recordid/...
	 * where relationship.name is the name of a relationship, and recordid is a recordid of one
	 * of the related records in that relationship.
	 * e.g.
	 *        courses/Course?CourseID=10/students/People?PersonID=20
	 */
	function expandNode(img,id){
		var ul = document.getElementById(\'navtree-\'+id);
		ul.style.display = \'\';
		img.src = DATAFACE_URL+\'/images/treeExpanded.gif\';
		img.onclick = function(){
			collapseNode(img,id);
		}
		if ( !ul.menuLoaded ){
			document.http = getHTTPObject();
			document.http.open(\'GET\', getNavTreeUrl(id));
			document.http_vars.ul = ul;
			document.http_vars.basepath = id;
			document.http.onreadystatechange = handleLoadNode;
			document.http.send(null);
		}
		
	}
	
	/**
	 * Collapses the given node.
	 * @param DOM_Object img The img tag that was clicked to collapse the node (treeExpanded.gif)
	 * @param string id The id for the node to be expanded.  See docs for expandNode() for examples
	 *		of the format of this parameter.
	 */
	function collapseNode(img,id){
		var ul = document.getElementById(\'navtree-\'+id);
		ul.style.display = \'none\';
		img.src = DATAFACE_URL+\'/images/treeCollapsed.gif\';
		img.onclick = function(){
			expandNode(img,id);
		}
	}
	
	/**
	 * Function called by HTTPRequest object to handle the response to the AJAX call to get the node 
	 * data.  This will load the node with the retrieved data from the ajax_nav_tree_node action
	 * in JSON format, and format the contents in HTML.
	 */
	function handleLoadNode(){
		if ( document.http.readyState == 4 ){
			//alert(document.http.responseText);
			var data = eval(\'(\'+document.http.responseText+\')\');
			//var ul = document.getElementById(\'navtree-\'+document.http_vars.basepath+\'/\'+id);
			var ul = document.http_vars.ul;
			var out = \'\';
			for (var id in data){
				out += \'<li><img \'+((data[id][\'__expandable__\']==0)?\' style="display:none" \':\'\')+\'src="\'+DATAFACE_URL+\'/images/treeCollapsed.gif" alt="Expand node"  onclick="expandNode(this,\\\'\'+document.http_vars.basepath+\'/\'+id+\'\\\')"/>&nbsp;\'+((data[id][\'__url__\'])?(\'<a href="\'+data[id][\'__url__\']+\'">\'):\'\')+data[id][\'__title__\']+((data[id][\'__url__\'])?\'</a>\':\'\')+\'<ul style="display:none" id="navtree-\'+document.http_vars.basepath+\'/\'+id+\'">\';
				if ( id.indexOf(\'?\') >= 0 ){
					
				
					if ( !document.recordIndex[id] ){
						document.recordIndex[id] = data[id];
					}
					'; ?>
out += '<li><?php $this->_tag_stack[] = array('translate', array('id' => "templates.RecordNavMenu.MESSAGE_LOADING")); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Loading ...<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></li>';<?php echo '
					
				} else {
					
					for (recid in data[id][\'records\']){
						out += \'<li><img src="\'+DATAFACE_URL+\'/images/treeCollapsed.gif" alt="Expand node"  onclick="expandNode(this,\\\'\'+document.http.basepath+\'/\'+id+\'/\'+recid+\'\\\')"/>&nbsp;<a href="\'+data[id][\'records\'][recid][\'__url__\']+\'">\'+data[id][\'records\'][recid][\'__title__\']+\'</a><ul style="display:none" id="navtree-\'+document.http.basepath+\'/\'+recid+\'"><li>Loading ...</li></ul></li>\';
					}
				}
				out += \'</ul>\';
				
				
			}
			document.http_vars.ul.innerHTML = out;
			document.http_vars.ul.menuLoaded = true;
		}
	
	}
	
	/**
	 * We will maintain an associative array of all record objects that have been loaded.
	 */
	if (!document.recordIndex){
		document.recordIndex = {};
	}
	document.http_vars = {};
	'; ?>

	document.recordIndex['<?php echo $this->_tpl_vars['record']->getId(); ?>
'] = <?php echo $this->_tpl_vars['record']->toJS(); ?>
;
	
		// The current record.

//--></script>
<div class="portlet">
<div>
<h5><?php $this->_tag_stack[] = array('translate', array('id' => "templates.RecordNavMenu.HEADING_THIS_RECORD")); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>This Record<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h5>
<div class="portletBody">
<ul id="navtree-<?php echo $this->_tpl_vars['record']->getId(); ?>
" class="navtree">

	<?php $_from = $this->_tpl_vars['relationships']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['relationship']):
?>
	<li class="level1" >
		<img src="<?php echo $this->_tpl_vars['ENV']['DATAFACE_URL']; ?>
/images/treeCollapsed.gif" alt="Show details" onclick="expandNode(this,'<?php echo $this->_tpl_vars['record']->getId(); ?>
/<?php echo $this->_tpl_vars['relationship']['name']; ?>
')" />&nbsp;
		<?php echo $this->_tpl_vars['relationship']['label']; ?>

		<ul style="display: none" id="navtree-<?php echo $this->_tpl_vars['record']->getId(); ?>
/<?php echo $this->_tpl_vars['relationship']['name']; ?>
">
			<li><?php $this->_tag_stack[] = array('translate', array('id' => "templates.RecordNavMenu.MESSAGE_LOADING")); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Loading ...<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></li>
		
		</ul>
	
	</li>
	<?php endforeach; endif; unset($_from); ?>
	

</ul>
</div>
</div>
</div>
<?php endif; ?>
<?php endif; ?>