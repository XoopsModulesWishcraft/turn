<?php

function b_TURN_block_book_show( $options )
{
	if (empty($options[0])||$options[0]==0)
		return false;
				
	$books_handler =& xoops_getmodulehandler('books', 'turn');
	
	$book = $books_handler->get($options[0]);
	
	$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $book->getJS(true));
	
	return array('reference'=>$turn->getReference(true));

}


function b_TURN_block_book_edit( $options )
{
	include_once($GLOBALS['xoops']->path('/modules/turn/include/formobjects.turn.php'));

	$turn = new turnFormSelectBooks('', 'options[0]', $options[0], 1, false);
	$form = ""._BL_TURN_BID."&nbsp;".$turn->render();

	return $form ;
}

?>