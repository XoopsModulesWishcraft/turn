<?php 

	$bid = (isset($_REQUEST['bid'])?intval($_REQUEST['bid']):exit(0));
	$block = (isset($_REQUEST['block'])?intval($_REQUEST['block']):0);
	
	require_once(dirname(dirname(__FILE__)).'/header.php');
	$GLOBALS['xoopsLogger']->activated = false;
	
	$books_handler =& xoops_getmodulehandler('books', 'turn');
	$book = $books_handler->get($bid);
	
	require_once($GLOBALS['xoops']->path('class/template.php'));
	$GLOBAL['xoopsTpl'] = new xoopsTpl();
	
	$GLOBAL['xoopsTpl']->assign('reference', $book->getReference($block));
	$GLOBAL['xoopsTpl']->assign('css', $book->getCSS());
	
	header('Content-type: text/css');
	$GLOBAL['xoopsTpl']->display('db:turn_css.html');
	
?>