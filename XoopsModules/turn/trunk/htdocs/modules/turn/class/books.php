<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for Blue Room Xcenter
 * @author Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package kernel
 */
class turnBooks extends XoopsObject
{

	var $_pHandler = NULL;
	var $_ModConfig = NULL;
	var $_Mod = NULL;
	
	// Fields that are a setting

    function turnBooks($id = null)
    {
    	$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$this->_Mod = $module_handler->getByDirname('turn');
		$this->_ModConfig = $config_handler->getConfigList($this->_Mod->getVar('mid'));
		
		$this->_pHandler = xoops_getmodulehandler('pages', 'turn');
		
        $this->initVar('bid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('pids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('reference', XOBJ_DTYPE_TXTBOX, 'fb_%id%_', false, 128);
		$this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 500);
		$this->initVar('language', XOBJ_DTYPE_TXTBOX, $GLOBALS['xoopsConfig']['language'], false, 64);
		$this->initVar('default', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('pages', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('bookWidth', XOBJ_DTYPE_INT, 640, false);
		$this->initVar('bookHeight', XOBJ_DTYPE_INT, 640, false);
		$this->initVar('background_colour', XOBJ_DTYPE_TXTBOX, '#ccc', false, 7);
        $this->initVar('location', XOBJ_DTYPE_ENUM, '_MI_TURN_PATH_XOOPS_UPLOAD_PATH', false, false, false, array('_MI_TURN_PATH_XOOPS_UPLOAD_PATH','_MI_TURN_PATH_XOOPS_VAR_PATH','_MI_TURN_PATH_OTHER'));        
		$this->initVar('path', XOBJ_DTYPE_OTHER, '', false, 128);
		$this->initVar('filename', XOBJ_DTYPE_OTHER, '', false, 128);
		$this->initVar('filetype', XOBJ_DTYPE_ENUM, '_MI_TURN_FILETYPE_JPG', false, false, false, array('_MI_TURN_FILETYPE_JPG','_MI_TURN_FILETYPE_PNG','_MI_TURN_FILETYPE_GIF','_MI_TURN_FILETYPE_SWF'));
		$this->initVar('extension', XOBJ_DTYPE_TXTBOX, 'jpg', false, 5);
		$this->initVar('default', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('width', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('height', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('size', XOBJ_DTYPE_INT, 100, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('actioned', XOBJ_DTYPE_INT, 0, false);
    }
    
    function setPids() {
    	$criteria = new Criteria('bid', $this->getVar('bid'));
    	$pids = array();
    	foreach($this->_pHandler->getObjects($criteria, true) as $pid => $obj)
    		$pids[$pid] = $pid;
    	$this->setVar('pids', $pids);
    	$this->setVar('pages', count($pids));
    }

    function getCSS() {
    	$ret = array();
    	$ret['background_colour'] = $this->getVar('background_colour');
    	$ret['background_image'] = $this->getImageURL();
    	$ret['book_width'] = $this->getVar('bookWidth');
    	$ret['book_height'] = $this->getVar('bookHeight');
    	$ret['page_width'] = $this->getVar('bookWidth')/2;
    	$ret['page_height'] = $this->getVar('bookHeight');
    	return $ret;
    }
    
    function getPages() {
    	$criteria = new CriteriaCompo(new Criteria('bid', $this->getVar('bid')));
    	$criteria->setSort('`page`, `created`');
    	$criteria->setOrder('ASC');
    	$pages = array();
    	$total = $this->_pHandler->getCount($criteria);
    	$i=0;
    	foreach($this->_pHandler->getObjects($criteria, true) as $pid => $page) {
    		$i++;
    		$pages[$pid]['html'] = $GLOBALS['myts']->displayTarea($this->getVar('html'), true, true, true, true, true);
    		$pages[$pid]['image'] = $page->getImageURL();
    		$pages[$pid]['last'] = ($total==$i?true:false);
    	}
    	return (count($pages)>0?$pages:false);
    }
    
	function getPDFPages() {
    	$criteria = new CriteriaCompo(new Criteria('bid', $this->getVar('bid')));
    	$criteria->setSort('`page`, `created`');
    	$criteria->setOrder('ASC');
    	$pages = array();
    	$total = $this->_pHandler->getCount($criteria);
    	$i=0;
    	foreach($this->_pHandler->getObjects($criteria, true) as $pid => $page) {
    		$i++;
    		$pages[$pid]['html'] = $GLOBALS['myts']->displayTarea($this->getVar('html'), true, true, true, true, true);
    		$pages[$pid]['image'] = $page->getImageURL();
    		$pages[$pid]['last'] = ($total==$i?true:false);
       	}
    	return (count($pages)>0?$pages:false);
    }
    
    function getURL()
    {
    	return XOOPS_URL.'/modules/'._MI_TURN_DIRNAME.'/?bid='.$this->getVar('bid');
    }
    
    function getPDFURL()
    {
    	return XOOPS_URL.'/modules/'._MI_TURN_DIRNAME.'/pdf.php?bid='.$this->getVar('bid');
    }
    
    function getForm() {
    	return turn_books_get_form($this, false);
    }
    
    function toArray() {
    	$ret = parent::toArray();
    	foreach($ret as $field => $value) {
    		if (defined($value))
    			$ret[$field] = constant($value);
    	}
    	$ele = turn_books_get_form($this, true);
    	foreach($ele as $key => $field)
    		$ret['form'][$key] = $field->render();
    	$ret['url'] = $this->getURL();
    	$ret['pdf_url'] = $this->getPDFURL();
    	return $ret;
    }
     
    
    function getJS($block=false) {
    	static $_loadedJS = array();
    	static $_loadedCSS = array();
    	
    	xoops_loadLanguage('modinfo', 'turn');
    	
		if (is_object($GLOBALS['xoTheme'])&&$_loadedJS[$this->getVar('bid')]==false) {
			
			if ($_loadedJS[0]==false) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_TURN_JS_JQUERY, array('type'=>'text/javascript'));
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_TURN_JS_turnJS, array('type'=>'text/javascript'));
				$_loadedJS[0] = true;
			}
			
			if (!isset($_loadedCSS[$this->getVar('bid')])||$_loadedCSS[$this->getVar('bid')]==false) {
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL.sprintf(_MI_TURN_CSS, $this->getVar('bid'), $block), array('type'=>'text/css'));
				$_loadedCSS[$this->getVar('bid')] = true;
			}
		}
    	return "$('#".$this->getReference($block)."_magazine').turn();";
    }
    
    function getReference($block=false) {
    	if ($block==true)
    		return str_replace('%id%', $this->getVar('bid'), 'block_'.$this->getVar('reference'));
    	else
    		return str_replace('%id%', $this->getVar('bid'), $this->getVar('reference'));
    }

 	function getImageURL() {
 		if (strlen($this->getVar('filename'))==0)
 			return false;
 			
    	$url = XOOPS_URL.'/modules/'._MI_TURN_DIRNAME.'/pages/background-'.$this->getVar('bid').'-'.$this->getVar('pid').'-'.$this->getVar('page');
    	switch($this->getVar('filetype')) {
    		default:
    		case '_MI_TURN_FILETYPE_JPG':
    			$url .= '.jpg';
    			break;
    		case '_MI_TURN_FILETYPE_PNG':
    			$url .= '.png';
    			break;
    		case '_MI_TURN_FILETYPE_GIF':
    			$url .= '.gif';
    			break;
    		case '_MI_TURN_FILETYPE_SWF':
    			$url .= '.swf';
    			break;
    	}	
    	$param = array();
    	foreach(parent::toArray() as $field => $value) {
    		if (in_array($field, $this->_settingsfield)) {
    			if ($value!=0&&$value!='_MI_TURN_DEFAULT') {
    				if (defined($value.'_VALUE'))
    					$param[] = "$field=".constant($value.'_VALUE');
    				else 
    					$param[] = "$field=".$value;
    			}
    		}
    	}
    	if (count($param)>0) {
    		$url .= '?'.implode('&', $param);
    	}
    	return $url;
    }
    
	function runInsertPlugin() {
		
		xoops_loadLanguage('plugins', 'turn');
		
		include_once($GLOBALS['xoops']->path('/modules/turn/plugins/'.constant('_MI_TURN_BOOKS_PLUGINFILE').'.php'));

 		switch ('_MI_TURN_BOOKS_PLUGIN') {
			case '_MI_TURN_BOOKS_PLUGIN':
				$func = ucfirst(constant('_MI_TURN_BOOKS_PLUGIN')).'InsertHook';
				break;
			default:
				return $this->getVar('bid');
				break;
		}
		
		if (function_exists($func))  {
			return @$func($this);
		}
		return $this->getVar('bid');
	}
	
	function runGetPlugin() {
				
		xoops_loadLanguage('plugins', 'turn');
		
		include_once($GLOBALS['xoops']->path('/modules/turn/plugins/'.constant('_MI_TURN_BOOKS_PLUGINFILE').'.php'));

 		switch ('_MI_TURN_BOOKS_PLUGIN') {
			case '_MI_TURN_BOOKS_PLUGIN':
				$func = ucfirst(constant('_MI_TURN_BOOKS_PLUGIN')).'GetHook';
				break;
			default:
				return $this;
				break;
		}
				
		if (function_exists($func))  {
			return @$func($this);
		}
		return $this;
	}
}


/**
* XOOPS policies handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.coop>
* @package kernel
*/
class turnBooksHandler extends XoopsPersistableObjectHandler
{

	var $_ModConfig = NULL;
	var $_Mod = NULL;

	var $_cHandler = NULL;
	var $_cssHandler = NULL;
	var $_pHandler = NULL;
	
	function __construct(&$db) 
    {
    	
		$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$this->_Mod = $module_handler->getByDirname('turn');
		$this->_ModConfig = $config_handler->getConfigList($this->_Mod->getVar('mid'));
	
    	$this->db = $db;
        parent::__construct($db, 'turn_books', 'turnBooks', "bid", "name");
    }
 	
    private function runGetArrayPlugin($row) {
		
		xoops_loadLanguage('plugins', 'turn');
		
		include_once($GLOBALS['xoops']->path('/modules/turn/plugins/'.constant('_MI_TURN_BOOKS_PLUGINFILE').'.php'));

 		switch ('_MI_TURN_BOOKS_PLUGIN') {
			case '_MI_TURN_BOOKS_PLUGIN':
				$func = ucfirst(constant('_MI_TURN_BOOKS_PLUGIN')).'GetArrayHook';
				break;
			default:
				return $row;
				break;
		}		
		if (function_exists($func))  {
			return @$func($row);
		}
		return $row;
	}
		
	private function resetDefault($object) {
		$sql = "UPDATE " . $GLOBALS['xoopsDB']->prefix('turn_books') . ' SET `default` = 0 WHERE `language` = "'.$object->getVar('language').'"';
		return $GLOBALS['xoopsDB']->queryF($sql);
	}
    
    function insert($obj, $force=true, $run_plugin = false) {
    	
    	$this->_cHandler = xoops_getmodulehandler('chapters', 'turn');
		$this->_cssHandler = xoops_getmodulehandler('css', 'turn');
		$this->_pHandler = xoops_getmodulehandler('pages', 'turn');
    	
       	if ($obj->isNew()) {
    		$obj->setVar('created', time());
    		if (is_object($GLOBALS['xoopsUser']))
    			$obj->setVar('uid', $GLOBALS['xoopsUser']->getVar('uid'));
    		$run_plugin = true;
    	} else {
    		$obj->setVar('updated', time());
    	}
    	if ($obj->vars['default']['changed']==true&&$obj->getVar('default')==true) {
    		$this->resetDefault($obj);
    	}
    	$obj->setCids();
    	$obj->setPids();
   		if ($run_plugin == true) {
    		$id = parent::insert($obj, $force);
    		$obj = parent::get($id);
    		if (is_object($obj)) {
	    		$ret = $obj->runInsertPlugin();
	    		return ($ret!=0)?$ret:$id;
    		} else {
    			return $id;
    		}
    	} else {
    		return parent::insert($obj, $force);
    	}
    }

    function delete($object, $force=true) {
		$this->_cHandler = xoops_getmodulehandler('chapters', 'turn');
		$this->_cssHandler = xoops_getmodulehandler('css', 'turn');
		$this->_pHandler = xoops_getmodulehandler('pages', 'turn');
    	$criteria = new Criteria('bid', $object->getVar('bid'));
    	foreach($this->_cHandler->getObjects($criteria, true) as $id => $obj)
    		$this->_cHandler->delete($obj);
    	foreach($this->_cssHandler->getObjects($criteria, true) as $id => $obj)
    		$this->_cssHandler->delete($obj);
    	foreach($this->_pHandler->getObjects($criteria, true) as $id => $obj)
    		$this->_pHandler->delete($obj);
    	return parent::delete($object, $force);
    }
    
    function get($id, $fields = '*', $run_plugin = true) {
    	$obj = parent::get($id, $fields);
    	$obj->setCids();
	    $obj->setPids();
    	if (is_object($obj)&&$run_plugin==true) {
    		return @$obj->runGetPlugin(false);
    	} elseif (is_array($obj)&&$run_plugin==true)
    		return $this->runGetArrayPlugin($obj);
    	else
    		return $obj;
    }
    
    function getObjects($criteria, $id_as_key=false, $as_object=true, $run_plugin = true) {
       	$objs = parent::getObjects($criteria, $id_as_key, $as_object);
    	foreach($objs as $id => $obj) {
    		$objs[$id]->setCids();
	    	$objs[$id]->setPids();
    		if (is_object($obj)&&$run_plugin==true) {
    			$objs[$id] = @$obj->runGetPlugin();   			
    		} elseif (is_array($obj)&&$run_plugin==true)
    			$objs[$id] = @$this->runGetArrayPlugin($obj);
    		if (empty($objs[$id])||($as_object==true&&!is_object($objs[$id])))
    			unset($objs[$id]);
    	}
    	return $objs;
    }
    
       
    function getFilterCriteria($filter) {
    	$parts = explode('|', $filter);
    	$criteria = new CriteriaCompo();
    	foreach($parts as $part) {
    		$var = explode(',', $part);
    		if (!empty($var[1])&&!is_numeric($var[0])) {
    			$object = $this->create();
    			if (		$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_TXTBOX || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_TXTAREA) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', '%'.$var[1].'%', (isset($var[2])?$var[2]:'LIKE')));
    			} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_INT || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_DECIMAL || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_FLOAT ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', $var[1], (isset($var[2])?$var[2]:'=')));			
				} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_ENUM ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', $var[1], (isset($var[2])?$var[2]:'=')));    				
				} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_ARRAY ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', '%"'.$var[1].'";%', (isset($var[2])?$var[2]:'LIKE')));    				
				}
    		} elseif (!empty($var[1])&&is_numeric($var[0])) {
    			$criteria->add(new Criteria("'".$var[0]."'", $var[1]));
    		}
    	}
    	return $criteria;
    }
        
	function getFilterForm($filter, $field, $sort='created', $op = '', $fct = '') {
    	$ele = turn_getFilterElement($filter, $field, $sort, $op, $fct);
    	if (is_object($ele))
    		return $ele->render();
    	else 
    		return '&nbsp;';
    }
}

?>