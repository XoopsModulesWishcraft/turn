<?php

	function turn_books_get_form($object, $as_array=false) {
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('books', 'turn');
			$object = $handler->create(); 
		}
		
		xoops_loadLanguage('forms', 'turn');
		$ele = array();
		
		if ($object->isNew()) {
			$sform = new XoopsThemeForm(_FRM_TURN_FORM_ISNEW_BOOK, 'book', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'new');
		} else {
			$sform = new XoopsThemeForm(_FRM_TURN_FORM_EDIT_BOOK, 'book', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'edit');
		}
		
		$sform->setExtra( "enctype='multipart/form-data'" ) ;
		
		$id = $object->getVar('bid');
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'books');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		if ($as_array==false)
			$ele['id'] = new XoopsFormHidden('id', $id);
		else 
			$ele['id'] = new XoopsFormHidden('id['.$id.']', $id);
		$ele['sort'] = new XoopsFormHidden('sort', isset($_REQUEST['sort'])?$_REQUEST['sort']:'created');
		$ele['order'] = new XoopsFormHidden('order', isset($_REQUEST['order'])?$_REQUEST['order']:'DESC');
		$ele['start'] = new XoopsFormHidden('start', isset($_REQUEST['start'])?intval($_REQUEST['start']):0);
		$ele['limit'] = new XoopsFormHidden('limit', isset($_REQUEST['limit'])?intval($_REQUEST['limit']):30);
		$ele['filter'] = new XoopsFormHidden('filter', isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1');
							
		$ele['name'] = new XoopsFormText(($as_array==false?_FRM_TURN_FORM_BOOKS_NAME:''), $id.'[name]', ($as_array==false?35:21),128, $object->getVar('name'));
		$ele['name']->setDescription(($as_array==false?_FRM_TURN_FORM_BOOKS_NAME_DESC:''));
		$ele['description'] = new XoopsFormText(($as_array==false?_FRM_TURN_FORM_BOOKS_DESCRIPTION:''), $id.'[description]', ($as_array==false?35:21), 500, $object->getVar('description'));
		$ele['description']->setDescription(($as_array==false?_FRM_TURN_FORM_BOOKS_DESCRIPTION_DESC:''));
		$ele['reference'] = new XoopsFormText(($as_array==false?_FRM_TURN_FORM_BOOKS_REFERENCE:''), $id.'[reference]', ($as_array==false?35:21), 128, $object->getVar('reference'));
		$ele['reference']->setDescription(($as_array==false?_FRM_TURN_FORM_BOOKS_REFERENCE_DESC:''));
		$ele['language'] = new turnFormSelectLanguage(($as_array==false?_FRM_TURN_FORM_TEXT_LANGUAGE:''), $id.'[language]', $object->getVar('language'));
		$ele['language']->setDescription(($as_array==false?_FRM_TURN_FORM_TEXT_LANGUAGE_DESC:''));
		$ele['bookWidth'] = new XoopsFormText(($as_array==false?_FRM_TURN_FORM_BOOKS_BOOKWIDTH:''), $id.'[bookWidth]', ($as_array==false?5:5), 5, $object->getVar('bookWidth'));
		$ele['bookWidth']->setDescription(($as_array==false?_FRM_TURN_FORM_BOOKS_BOOKWIDTH_DESC:''));
		$ele['bookHeight'] = new XoopsFormText(($as_array==false?_FRM_TURN_FORM_BOOKS_BOOKHEIGHT:''), $id.'[bookHeight]', ($as_array==false?5:5), 5, $object->getVar('bookHeight'));
		$ele['bookHeight']->setDescription(($as_array==false?_FRM_TURN_FORM_BOOKS_BOOKHEIGHT_DESC:''));
		$ele['background_colour'] = new XoopsFormText(($as_array==false?_FRM_TURN_FORM_BOOKS_BACKGROUNDCOLOUR:''), $id.'[background_colour]', ($as_array==false?7:7), 6, $object->getVar('backgroundColor'));	
		$ele['background_colour']->setDescription(($as_array==false?_FRM_TURN_FORM_BOOKS_BACKGROUNDCOLOUR_DESC:''));
		if ($object->isNew()) {
			$ele['file'] = new XoopsFormFile(($as_array==false?_FRM_TURN_FORM_PAGES_FILE:''), 'imagefile', $GLOBALS['xoopsModuleConfig']['maximum_filesize']);
			$ele['file']->setDescription(($as_array==false?_FRM_TURN_FORM_PAGES_FILE_DESC:''));
			$required = array('bid', 'page', 'file');
		} else {
			$ele['thumbnail'] = new XoopsFormLabel(($as_array==false?_FRM_TURN_FORM_PAGES_THUMBNAIL:''), '<img src="'.XOOPS_URL.'/modules/turn/bakground.php?bid='.$object->getVar('bid').'" />');
			$ele['thumbnail']->setDescription(($as_array==false?_FRM_TURN_FORM_PAGES_THUMBNAIL_DESC:''));
			$ele['file'] = new XoopsFormFile(($as_array==false?_FRM_TURN_FORM_PAGES_REPLACEFILE:''), 'imagefile', $GLOBALS['xoopsModuleConfig']['maximum_filesize']);
			$ele['file']->setDescription(($as_array==false?_FRM_TURN_FORM_PAGES_REPLACEFILE_DESC:''));
			$required = array('bid', 'page');
		}
		
		if ($object->getVar('pages')>0) {
			$ele['pages'] = new XoopsFormLabel(($as_array==false?_FRM_TURN_FORM_BOOKS_PAGES:''), $object->getVar('pages'));
		}
		
		if ($object->getVar('created')>0) {
			$ele['created'] = new XoopsFormLabel(($as_array==false?_FRM_TURN_FORM_BOOKS_CREATED:''), date(_DATESTRING, $object->getVar('created')));
		}
		
		if ($object->getVar('updated')>0) {
			$ele['updated'] = new XoopsFormLabel(($as_array==false?_FRM_TURN_FORM_BOOKS_UPDATED:''), date(_DATESTRING, $object->getVar('updated')));
		}
		
		if ($object->getVar('actioned')>0) {
			$ele['actioned'] = new XoopsFormLabel(($as_array==false?_FRM_TURN_FORM_BOOKS_ACTIONED:''), date(_DATESTRING, $object->getVar('actioned')));
		}	
				
		if ($as_array==true)
			return $ele;
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		$required = array('name');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}

	
	function turn_pages_get_form($object, $as_array=false) {
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('pages', 'turn');
			$object = $handler->create(); 
		}
		
		xoops_loadLanguage('forms', 'turn');
		$ele = array();
		
		if ($object->isNew()) {
			$sform = new XoopsThemeForm(_FRM_TURN_FORM_ISNEW_PAGES, 'pages', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'new');
		} else {
			$sform = new XoopsThemeForm(_FRM_TURN_FORM_EDIT_PAGES, 'pages', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'edit');
		}
		
		$components = turn_getFilterURLComponents(isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1', '', 'created');
		
		$sform->setExtra( "enctype='multipart/form-data'" ) ;
		
		$id = $object->getVar('pid');
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'pages');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		if ($as_array==false)
			$ele['id'] = new XoopsFormHidden('id', $id);
		else 
			$ele['id'] = new XoopsFormHidden('id['.$id.']', $id);
		$ele['sort'] = new XoopsFormHidden('sort', isset($_REQUEST['sort'])?$_REQUEST['sort']:'created');
		$ele['order'] = new XoopsFormHidden('order', isset($_REQUEST['order'])?$_REQUEST['order']:'DESC');
		$ele['start'] = new XoopsFormHidden('start', isset($_REQUEST['start'])?intval($_REQUEST['start']):0);
		$ele['limit'] = new XoopsFormHidden('limit', isset($_REQUEST['limit'])?intval($_REQUEST['limit']):30);
		$ele['filter'] = new XoopsFormHidden('filter', isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1');

		if ($object->isNew()) {
			$ele['file'] = new XoopsFormFile(($as_array==false?_FRM_TURN_FORM_PAGES_FILE:''), 'imagefile', $GLOBALS['xoopsModuleConfig']['maximum_filesize']);
			$ele['file']->setDescription(($as_array==false?_FRM_TURN_FORM_PAGES_FILE_DESC:''));
			$required = array('bid', 'cid', 'page', 'file');
		} else {
			$ele['thumbnail'] = new XoopsFormLabel(($as_array==false?_FRM_TURN_FORM_PAGES_THUMBNAIL:''), '<img src="'.XOOPS_URL.'/modules/turn/thumbnail.php?pid='.$object->getVar('pid').'" />');
			$ele['thumbnail']->setDescription(($as_array==false?_FRM_TURN_FORM_PAGES_THUMBNAIL_DESC:''));
			$ele['file'] = new XoopsFormFile(($as_array==false?_FRM_TURN_FORM_PAGES_REPLACEFILE:''), 'imagefile', $GLOBALS['xoopsModuleConfig']['maximum_filesize']);
			$ele['file']->setDescription(($as_array==false?_FRM_TURN_FORM_PAGES_REPLACEFILE_DESC:''));
			$required = array('bid', 'cid', 'page');
		}
		$ele['bid'] = new turnFormSelectBooks(($as_array==false?_FRM_TURN_FORM_PAGES_BOOKS:''), $id.'[bid]', $object->getVar('bid'), 1, false, true);
		$ele['bid']->setDescription(($as_array==false?_FRM_TURN_FORM_PAGES_BOOKS_DESC:''));
		$ele['bid']->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&bid=\'+this.options[this.selectedIndex].value'.',\'_self\')"');
		$ele['page'] = new XoopsFormText(($as_array==false?_FRM_TURN_FORM_PAGES_PAGE:''), $id.'[page]', 5, 7, $object->getVar('page'));
		$ele['page']->setDescription(($as_array==false?_FRM_TURN_FORM_PAGES_PAGE_DESC:''));		
		
		$html_configs = array();
		$html_configs['name'] = $id.'[html]';
		$html_configs['value'] = $object->getVar('html');
		$html_configs['rows'] = 35;
		$html_configs['cols'] = 60;
		$html_configs['width'] = "100%";
		$html_configs['height'] = "400px";
		$html_configs['editor'] = $GLOBALS['xoopsModuleConfig']['editor'];
		$ele['html'] = new XoopsFormEditor(($as_array==false?_FRM_TURN_FORM_PAGES_HTML:''), $html_configs['name'], $html_configs);
		$ele['html']->setDescription(($as_array==false?_FRM_TURN_FORM_PAGES_HTML_DESC:''));
		
		if ($object->getVar('created')>0) {
			$ele['created'] = new XoopsFormLabel(($as_array==false?_FRM_TURN_FORM_PAGES_CREATED:''), date(_DATESTRING, $object->getVar('created')));
		}
		
		if ($object->getVar('updated')>0) {
			$ele['updated'] = new XoopsFormLabel(($as_array==false?_FRM_TURN_FORM_PAGES_UPDATED:''), date(_DATESTRING, $object->getVar('updated')));
		}

		if ($object->getVar('actioned')>0) {
			$ele['actioned'] = new XoopsFormLabel(($as_array==false?_FRM_TURN_FORM_PAGES_ACTIONED:''), date(_DATESTRING, $object->getVar('actioned')));
		}
		
		if ($as_array==true)
			return $ele;
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}
	
?>