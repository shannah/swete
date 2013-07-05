<?php /* Smarty version 2.6.18, created on 2013-07-03 00:35:25
         compiled from Dataface_MoveUpForm.html */ ?>
<?php echo '
<script language="javscript" type="text/javascript"><!--

function moveUp(id){
	var form = document.moveRecordForm;
	form.elements[\'-reorder:index\'].value = id;
	form.elements[\'-reorder:direction\'].value = \'up\';
	form.submit();
}

function moveDown(id){
	var form = document.moveRecordForm;
	form.elements[\'-reorder:index\'].value = id;
	form.elements[\'-reorder:direction\'].value = \'down\';
	form.submit();
}

//--></script>



'; ?>

<form style="display: none" name="moveRecordForm" method="POST" action="<?php echo $this->_tpl_vars['ENV']['APPLICATION_OBJECT']->url('-action=reorder_related_records'); ?>
">
	<input type="hidden" name="-redirect" value="<?php echo $this->_tpl_vars['ENV']['SERVER']['PHP_SELF']; ?>
?<?php echo $this->_tpl_vars['ENV']['SERVER']['QUERY_STRING']; ?>
"/>
	<input type="hidden" name="-reorder:index"/>
	<input type="hidden" name="-reorder:direction"/>
</form>