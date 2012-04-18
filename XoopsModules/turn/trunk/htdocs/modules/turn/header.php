<?php
	require_once (dirname(dirname(dirname(__FILE__))).'/mainfile.php');

	require_once('include/functions.php');
	require_once('include/formobjects.turn.php');
	require_once('include/forms.turn.php');
	
	xoops_loadLanguage('modinfo', 'turn');
	
	$config_handler = xoops_gethandler('config');
	$module_handler = xoops_gethandler('module');
	
	$GLOBALS['xoopsModule'] = $module_handler->getByDirname('turn');
	$GLOBALS['xoopsModuleConfig'] = $config_handler->getConfigList($GLOBALS['xoopsModule']->getVar('mid'));
		
?>