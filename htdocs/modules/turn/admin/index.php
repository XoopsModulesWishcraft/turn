<?php
	
	
	include('header.php');
	
	
	xoops_loadLanguage('admin', 'turn');
	
	
	xoops_cp_header();
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"campaign";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"list";
	$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
	$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
	$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
	$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
	$filter = !empty($_REQUEST['filter'])?''.$_REQUEST['filter'].'':'1,1';
	
	switch($op) {
		default:
		case "books":	
			switch ($fct)
			{
				default:
				case "list":				
					turn_adminMenu(1);
					
					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					include_once $GLOBALS['xoops']->path( "/class/template.php" );
					$GLOBALS['pfTpl'] = new XoopsTpl();
					
					
					$books_handler =& xoops_getmodulehandler('books', 'turn');
					
					
					$criteria = $books_handler->getFilterCriteria($filter);
					$ttl = $books_handler->getCount($criteria);
					$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
					
					
					$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op.'&fct='.$fct.'&filter='.$filter.'&fct='.$fct.'&filter='.$filter);
					$GLOBALS['pfTpl']->assign('pagenav', $pagenav->renderNav());
					
					foreach (array(	'bid','name','description','reference','language','default','pages','chapter','bookWidth','bookHeight','created','updated','actioned') as $id => $key) {
						$GLOBALS['pfTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'&filter='.$filter.'">'.(defined('_AM_TURN_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_TURN_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_TURN_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
						$GLOBALS['pfTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $books_handler->getFilterForm($filter, $key, $sort, $op, $fct));
					}
					
					$GLOBALS['pfTpl']->assign('limit', $limit);
					$GLOBALS['pfTpl']->assign('start', $start);
					$GLOBALS['pfTpl']->assign('order', $order);
					$GLOBALS['pfTpl']->assign('sort', $sort);
					$GLOBALS['pfTpl']->assign('filter', $filter);
					$GLOBALS['pfTpl']->assign('xoConfig', $GLOBALS['xoopsModuleConfig']);
					
					$criteria->setStart($start);
					$criteria->setLimit($limit);
					$criteria->setSort('`'.$sort.'`');
					$criteria->setOrder($order);
					
					$books = $books_handler->getObjects($criteria, true);
					foreach($books as $bid => $book) {
						$GLOBALS['pfTpl']->append('books', $book->toArray());
					}
					$GLOBALS['pfTpl']->assign('form', turn_books_get_form(false));
					$GLOBALS['pfTpl']->assign('php_self', $_SERVER['PHP_SELF']);
					$GLOBALS['pfTpl']->display('db:turn_cpanel_books_list.html');
					break;		
					
				case "new":
				case "edit":
					
					turn_adminMenu(1);
					
					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					include_once $GLOBALS['xoops']->path( "/class/template.php" );
					$GLOBALS['pfTpl'] = new XoopsTpl();
					
					$books_handler =& xoops_getmodulehandler('books', 'turn');
					if (isset($_REQUEST['id'])) {
						$books = $books_handler->get(intval($_REQUEST['id']));
					} else {
						$books = $books_handler->create();
					}
					
					$GLOBALS['pfTpl']->assign('form', $books->getForm());
					$GLOBALS['pfTpl']->assign('php_self', $_SERVER['PHP_SELF']);
					$GLOBALS['pfTpl']->display('db:turn_cpanel_books_edit.html');
					break;
				case "save":
					
					$books_handler =& xoops_getmodulehandler('books', 'turn');
					$id=0;
					if ($id=intval($_REQUEST['id'])) {
						$books = $books_handler->get($id);
					} else {
						$books = $books_handler->create();
					}
					$books->setVars($_POST[$id]);
						
					$uploader = new turnXoopsMediaUploader($GLOBALS['xoopsModuleConfig']['uploaddirtype'].DS.$GLOBALS['xoopsModuleConfig']['uploaddir'], explode('|', $GLOBALS['xoopsModuleConfig']['allowed_mimetypes']), $GLOBALS['xoopsModuleConfig']['maximum_filesize'], 0, 0, explode('|', $GLOBALS['xoopsModuleConfig']['allowed_file_extensions']));
					$uploader->setPrefix(substr(md5(microtime(true)), mt_rand(0,20), 11));
					  
					if ($uploader->fetchMedia('imagefile')) {
					  	if (!$uploader->upload()) {
					    	if ($books->isNew()) {	
					    		turn_adminMenu(2);
					       		echo $uploader->getErrors();
								turn_footer_adminMenu();
								xoops_cp_footer();
								exit(0);
					  		}     
					    } else {
					      	if (!$books->isNew())
					      		unlink(constant($books->getVar('location').'_VALUE').$books->getVar('path').(substr($books->getVar('path'), strlen($books->getVar('path'))-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$books->getVar('filename'));
					      	
					      	$books->setVar('path', $GLOBALS['xoopsModuleConfig']['uploaddirtype'].DS.$GLOBALS['xoopsModuleConfig']['uploaddir']);
					      	$books->setVar('filename', $uploader->getSavedFileName());
					      	
					      	$filename = explode('.', $uploader->getSavedFileName());
					      	$books->setVar('extension', $filename[sizeof($filename)]);
					      	
					      	if ($dimension = getimagesize($books->getVar('path').(substr($books->getVar('path'), strlen($books->getVar('path'))-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$books->getVar('filename'))) {
					      		$books->setVar('width', $dimension[0]);
					      		$books->setVar('height', $dimension[1]);
					      	} else {
					      		$books->setVar('width', 0);
					      		$books->setVar('height', 0);
					      	}
					    }      	
				  	} else {
				  		if ($books->isNew()) {	
				    		turn_adminMenu(2);
				       		echo $uploader->getErrors();
							turn_footer_adminMenu();
							xoops_cp_footer();
							exit(0);
				  		}
				  	}
					if (!$id=$books_handler->insert($books)) {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_BOOKS_FAILEDTOSAVE);
						exit(0);
					} else {
						switch($_REQUEST['mode']) {
							case 'new':
								redirect_header('index.php?op='.$op.'&fct=edit&id='.$id, 10, _AM_MSG_BOOKS_SAVEDOKEY);
								break;
							default:
							case 'edit':
								redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_BOOKS_SAVEDOKEY);
								break;
						}
						exit(0);
					}
					break;
				case "savelist":
					
					$books_handler =& xoops_getmodulehandler('books', 'turn');
					foreach($_REQUEST['id'] as $id) {
						$books = $books_handler->get($id);
						$books->setVars($_POST[$id]);
						if ($_POST[$id]['default']==true)
							$books->setVar('default', true);
						else 
							$books->setVar('default', false);
						if (!$books_handler->insert($books)) {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_BOOKS_FAILEDTOSAVE);
							exit(0);
						} 
					}
					redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_BOOKS_SAVEDOKEY);
					exit(0);
					break;				
				case "delete":	
								
					$books_handler =& xoops_getmodulehandler('books', 'turn');
					$id=0;
					if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
						$books = $books_handler->get($id);
						if (!$books_handler->delete($books)) {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_BOOKS_FAILEDTODELETE);
							exit(0);
						} else {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_BOOKS_DELETED);
							exit(0);
						}
					} else {
						$books = $books_handler->get(intval($_REQUEST['id']));
						xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), 'index.php', sprintf(_AM_MSG_BOOKS_DELETE, $books->getVar('name')));
					}
					break;
			}
			break;
		case "pages":	
			switch ($fct)
			{
				default:
				case "list":				
					turn_adminMenu(2);
					
					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					include_once $GLOBALS['xoops']->path( "/class/template.php" );
					$GLOBALS['pfTpl'] = new XoopsTpl();
					
					$pages_handler =& xoops_getmodulehandler('pages', 'turn');
						
					$criteria = $pages_handler->getFilterCriteria($filter);
					$ttl = $pages_handler->getCount($criteria);
					$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
										
					$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op.'&fct='.$fct.'&filter='.$filter.'&fct='.$fct.'&filter='.$filter);
					$GLOBALS['pfTpl']->assign('pagenav', $pagenav->renderNav());
			
					foreach (array(	'pid','bid','cid','page','mode','path','filename','width','height','extension','filetype','created','updated','actioned') as $id => $key) {
						$GLOBALS['pfTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'&filter='.$filter.'">'.(defined('_AM_TURN_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_TURN_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_TURN_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
						$GLOBALS['pfTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $pages_handler->getFilterForm($filter, $key, $sort, $op, $fct));
					}
					
					$GLOBALS['pfTpl']->assign('limit', $limit);
					$GLOBALS['pfTpl']->assign('start', $start);
					$GLOBALS['pfTpl']->assign('order', $order);
					$GLOBALS['pfTpl']->assign('sort', $sort);
					$GLOBALS['pfTpl']->assign('filter', $filter);
					$GLOBALS['pfTpl']->assign('xoConfig', $GLOBALS['xoopsModuleConfig']);
										
					$criteria->setStart($start);
					$criteria->setLimit($limit);
					$criteria->setSort('`'.$sort.'`');
					$criteria->setOrder($order);
						
					$pagess = $pages_handler->getObjects($criteria, true);
					foreach($pagess as $gid => $pages) {
						if (is_object($pages))					
							$GLOBALS['pfTpl']->append('pages', $pages->toArray());
					}
					$GLOBALS['pfTpl']->assign('form', turn_pages_get_form(false));
					$GLOBALS['pfTpl']->assign('php_self', $_SERVER['PHP_SELF']);
					$GLOBALS['pfTpl']->display('db:turn_cpanel_pages_list.html');
					break;		
					
				case "new":
				case "edit":
					
					turn_adminMenu(2);
					
					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					include_once $GLOBALS['xoops']->path( "/class/template.php" );
					$GLOBALS['pfTpl'] = new XoopsTpl();
					
					$pages_handler =& xoops_getmodulehandler('pages', 'turn');
					if (isset($_REQUEST['id'])) {
						$pages = $pages_handler->get(intval($_REQUEST['id']));
					} else {
						$pages = $pages_handler->create();
					}
					
					$GLOBALS['pfTpl']->assign('form', $pages->getForm());
					$GLOBALS['pfTpl']->assign('php_self', $_SERVER['PHP_SELF']);
					$GLOBALS['pfTpl']->display('db:turn_cpanel_pages_edit.html');
					break;
				case "save":
					
					$pages_handler =& xoops_getmodulehandler('pages', 'turn');
					if ($id=intval($_REQUEST['id'])) {
						$pages = $pages_handler->get($id);
					} else {
						$pages = $pages_handler->create();
					}
					$pages->setVars($_POST[$id]);

					$uploader = new turnXoopsMediaUploader($GLOBALS['xoopsModuleConfig']['uploaddirtype'].DS.$GLOBALS['xoopsModuleConfig']['uploaddir'], explode('|', $GLOBALS['xoopsModuleConfig']['allowed_mimetypes']), $GLOBALS['xoopsModuleConfig']['maximum_filesize'], 0, 0, explode('|', $GLOBALS['xoopsModuleConfig']['allowed_file_extensions']));
					$uploader->setPrefix(substr(md5(microtime(true)), mt_rand(0,20), 11));
					  
					if ($uploader->fetchMedia('imagefile')) {
					  	if (!$uploader->upload()) {
					    	if ($pages->isNew()) {	
					    		turn_adminMenu(2);
					       		echo $uploader->getErrors();
								turn_footer_adminMenu();
								xoops_cp_footer();
								exit(0);
					  		}     
					    } else {
					      	if (!$pages->isNew())
					      		unlink(constant($pages->getVar('location').'_VALUE').$pages->getVar('path').(substr($pages->getVar('path'), strlen($pages->getVar('path'))-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$pages->getVar('filename'));
					      	
					      	$pages->setVar('path', $GLOBALS['xoopsModuleConfig']['uploaddirtype'].DS.$GLOBALS['xoopsModuleConfig']['uploaddir']);
					      	$pages->setVar('filename', $uploader->getSavedFileName());
					      	
					      	$filename = explode('.', $uploader->getSavedFileName());
					      	$pages->setVar('extension', $filename[sizeof($filename)]);
					      	
					      	if ($dimension = getimagesize($pages->getVar('path').(substr($pages->getVar('path'), strlen($pages->getVar('path'))-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$pages->getVar('filename'))) {
					      		$pages->setVar('width', $dimension[0]);
					      		$pages->setVar('height', $dimension[1]);
					      	} else {
					      		$pages->setVar('width', 0);
					      		$pages->setVar('height', 0);
					      	}
					    }      	
				  	} else {
				  		if ($pages->isNew()) {	
				    		turn_adminMenu(2);
				       		echo $uploader->getErrors();
							turn_footer_adminMenu();
							xoops_cp_footer();
							exit(0);
				  		}
				  	}
					
				  	$pages = $pages_handler->get($id=$pages_handler->insert($pages));
				  						
				  	if (!is_object($pages)) {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_PAGES_FAILEDTOSAVE);
						exit(0);
					} else {
						if (is_object($chapter)) {
							$chapter = $chapters_handler->get($cid);
							$chapter->setVar('page', $pages->getVar('page'));
							$chapter->setVar('pid', $pages->getVar('pid'));
							$chapters_handler->insert($chapter, true);
						}
						switch($_REQUEST['mode']) {
							case 'new':
								redirect_header('index.php?op='.$op.'&fct=edit&id='.$id, 10, _AM_MSG_PAGES_SAVEDOKEY);
								break;
							default:
							case 'edit':
								redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_PAGES_SAVEDOKEY);
								break;
						}
						exit(0);
					}
					break;
				case "savelist":
					
					$pages_handler =& xoops_getmodulehandler('pages', 'turn');
					foreach($_REQUEST['id'] as $id) {
						$pages = $pages_handler->get($id);
						$pages->setVars($_POST[$id]);
						if (!$pages_handler->insert($pages)) {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_PAGES_FAILEDTOSAVE);
							exit(0);
						} 
					}
					redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_PAGES_SAVEDOKEY);
					exit(0);
					break;				
				case "delete":	
								
					$pages_handler =& xoops_getmodulehandler('pages', 'turn');
					$id=0;
					if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
						$pages = $pages_handler->get($id);
						if (!$pages_handler->delete($pages)) {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_PAGES_FAILEDTODELETE);
							exit(0);
						} else {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_PAGES_DELETED);
							exit(0);
						}
					} else {
						$pages = $pages_handler->get(intval($_REQUEST['id']));
						xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), 'index.php', sprintf(_AM_MSG_PAGES_DELETE, $pages->getVar('filename')));
					}
					break;
			}
			break;
	}
	
	turn_footer_adminMenu();
	xoops_cp_footer();
?>