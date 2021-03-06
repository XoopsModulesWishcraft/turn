<?php
	ob_start();
	require_once('header.php');
	$GLOBALS['xoopsLogger']->activated = false;
	//header('Content-type: image/jpeg');
	
	$bid = (isset($_GET['bid'])?intval($_GET['bid']):0);
	$op = (isset($_GET['op'])?$_GET['op']:'thumbnail');
	
	if ($bid>0) {
		$books_handler = xoops_getmodulehandler('books', 'turn');
		$criteria = new CriteriaCompo(new Criteria('bid', $bid));
		$criteria->add(new Criteria('filetype', "('_MI_TURN_FILETYPE_JPG','_MI_TURN_FILETYPE_PNG','_MI_TURN_FILETYPE_GIF')", 'IN'));
		$criteria->setSort(`page`, `created`);
		$criteria->setOrder('ASC');
		$criteria->setStart(0);
		$criteria->setLimit(1);
		$books = $books_handler->getObjects($criteria, false);
		if (is_object($books[0])) {
			switch($books[0]->getVar('location')) {
				case '_MI_TURN_PATH_XOOPS_UPLOAD_PATH':
					$path = XOOPS_UPLOAD_PATH;
					break;
				case '_MI_TURN_PATH_XOOPS_VAR_PATH':
					$path = XOOPS_VAR_PATH;
					break;
				case '_MI_TURN_PATH_OTHER':
					$path = '';
					break;
			}
			
			$path .= (substr($books[0]->getVar('path'), 0, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$books[0]->getVar('path');
			$path .= (substr($path, strlen($path)-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'');
			$image = $path.$books[0]->getVar('filename');
		}
	}

	$image = str_replace(array('/','\\'), DIRECTORY_SEPARATOR, $image);
	
	if (file_exists($image)) {
		require_once($GLOBALS['xoops']->path(_MI_TURN_FRAMEWORK_WIDEIMAGE));
	
		if (file_exists($image)) {
			$img = WideImage::load($image);
			$newImage = $img->resize($GLOBALS['xoopsModuleConfig']['thumbnail_width'], $GLOBALS['xoopsModuleConfig']['thumbnail_height']);
			ob_end_clean();
			$newImage->output('jpg', 45);
			exit(0);
		}
	}	
?>