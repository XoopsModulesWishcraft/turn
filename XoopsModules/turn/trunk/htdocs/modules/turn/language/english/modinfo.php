<?php
	
	//Xoops version
	define('_MI_TURN_NAME', 'turn.js Books');
	define('_MI_TURN_DESCRIPTION', 'Module made from the resources of http://www.turnjs.com/ - turn.js');
	define('_MI_TURN_DIRNAME', 'turn');
	
	//preferences
	define('_MI_TURN_EDITOR','Editor to Use');
	define('_MI_TURN_EDITOR_DESC','This is the Editor to Use.');	
	define('_MI_TURN_UPLOADDIRTYPE','Upload Prefix Path');
	define('_MI_TURN_UPLOADDIRTYPE_DESC','This is the prefix for the path for the upload');
	define('_MI_TURN_UPLOADDIR','Upload Postfix Path');
	define('_MI_TURN_UPLOADDIR_DESC','This is the postfix for the upload.');
	define('_MI_TURN_SCALE_IMAGES','Scale Images');
	define('_MI_TURN_SCALE_IMAGES_DESC','Scale images for book?');
	define('_MI_TURN_AUTO_CROP_IMAGES','Autocrop Images');
	define('_MI_TURN_AUTO_CROP_IMAGES_DESC','Autocrop images for book?');
	define('_MI_TURN_ALLOWED_FILE_EXTENSION','Allowed file extenstions for upload');
	define('_MI_TURN_ALLOWED_FILE_EXTENSION_DESC','This is the allowed file extension for uploading to the module.');
	define('_MI_TURN_ALLOWED_MIMETYPES','Allowed mime-types for the upload');
	define('_MI_TURN_ALLOWED_MIMETYPES_DESC','This is the the allowed mime types for the uploading to the module.');
	define('_MI_TURN_MAXIMUM_FILESIZE','Maxmimum file size');
	define('_MI_TURN_MAXIMUM_FILESIZE_DESC','Maximum file size in bytes to be uploaded');
	define('_MI_TURN_THUMBNAIL_WIDTH','Thumbnail width');
	define('_MI_TURN_THUMBNAIL_WIDTH_DESC','The width in pixels of a thumbnail');
	define('_MI_TURN_THUMBNAIL_HEIGHT','Thumbnail height');
	define('_MI_TURN_THUMBNAIL_HEIGHT_DESC','This height in pixels of a thumbnail');
	define('_MI_TURN_AUTO_GENERATE','Auto Generate PDF download for each book?');
	define('_MI_TURN_AUTO_GENERATE_DESC','Enables PDF Generation for each book.');
	define('_MI_TURN_PRINT_WIDTH', 'Print Width Size');
	define('_MI_TURN_PRINT_WIDTH_DESC', 'The print width size for images.');
	define('_MI_TURN_PRINT_HEIGHT', 'Print Height Size');
	define('_MI_TURN_PRINT_HEIGHT_DESC', 'The print height size for images.');
	
	//Admin Menus
	define('_MI_TURN_TITLE_ADMENU1', 'Books');
	define('_MI_TURN_ICON_ADMENU1', '');
	define('_MI_TURN_LINK_ADMENU1', 'admin/index.php?op=books&fct=list');
	define('_MI_TURN_TITLE_ADMENU2', 'Pages');
	define('_MI_TURN_ICON_ADMENU2', '');
	define('_MI_TURN_LINK_ADMENU2', 'admin/index.php?op=pages&fct=list');
	
	// Enumeration for Books
	define('_MI_TURN_TRUE', _YES);
	define('_MI_TURN_FALSE', _NO);
	define('_MI_TURN_DEFAULT', 'Default');
	define('_MI_TURN_ASYMMETRIC', 'Asymmetrical');
	define('_MI_TURN_SYMMETRIC', 'Symmetrical');
	define('_MI_TURN_PROGESSBAR', 'Progress Bar');
	define('_MI_TURN_ROUND', 'Round');
	define('_MI_TURN_THIN', 'Thin');
	define('_MI_TURN_DOTS', 'Dots');
	define('_MI_TURN_GRADIENTWHEEL', 'Gradient Wheel');
	define('_MI_TURN_GEARWHEEL', 'Gear Wheel');
	define('_MI_TURN_LINE', 'Line');
	define('_MI_TURN_ANIMATEDBOOK', 'Animated Book');
	define('_MI_TURN_NONE', 'None');
	define('_MI_TURN_FIRSTPAGEONLY', 'First Page Only');
	define('_MI_TURN_EACHPAGE', 'Each Page');
	define('_MI_TURN_MANUALLY', 'Manually');
	define('_MI_TURN_BOTTOMRIGHT', 'Bottom right');
	define('_MI_TURN_TOPRIGHT', 'Top right');
	define('_MI_TURN_BOTTOMLEFT', 'Bottom left');
	define('_MI_TURN_TOPLEFT', 'Top left');
	define('_MI_TURN_FIT', 'Fit to size');
	define('_MI_TURN_TOP_LEFT', 'Top left');
	define('_MI_TURN_CENTER', 'Center');

	// Enumeration Values for Books
	define('_MI_TURN_TRUE_VALUE', 'true');
	define('_MI_TURN_FALSE_VALUE', 'false');
	define('_MI_TURN_DEFAULT_VALUE', 'Default');
	define('_MI_TURN_ASYMMETRIC_VALUE', 'Asymmetric');
	define('_MI_TURN_SYMMETRIC_VALUE', 'Symmetric');
	define('_MI_TURN_PROGESSBAR_VALUE', 'Progress Bar');
	define('_MI_TURN_ROUND_VALUE', 'Round');
	define('_MI_TURN_THIN_VALUE', 'Thin');
	define('_MI_TURN_DOTS_VALUE', 'Dots');
	define('_MI_TURN_GRADIENTWHEEL_VALUE', 'Gradient Wheel');
	define('_MI_TURN_GEARWHEEL_VALUE', 'Gear Wheel');
	define('_MI_TURN_LINE_VALUE', 'Line');
	define('_MI_TURN_ANIMATEDBOOK_VALUE', 'Animated Book');
	define('_MI_TURN_NONE_VALUE', 'None');
	define('_MI_TURN_FIRSTPAGEONLY_VALUE', 'first page only');
	define('_MI_TURN_EACHPAGE_VALUE', 'each page');
	define('_MI_TURN_MANUALLY_VALUE', 'manually');
	define('_MI_TURN_BOTTOMRIGHT_VALUE', 'bottom-right');
	define('_MI_TURN_TOPRIGHT_VALUE', 'top-right');
	define('_MI_TURN_BOTTOMLEFT_VALUE', 'bottom-left');
	define('_MI_TURN_TOPLEFT_VALUE', 'top-left');
	define('_MI_TURN_FIT_VALUE', 'fit');
	define('_MI_TURN_TOP_LEFT_VALUE', 'top left');
	define('_MI_TURN_CENTER_VALUE', 'center');

	// Enumeration for Pages
	define('_MI_TURN_MODE_LANDSCAPE', 'Landscape');
	define('_MI_TURN_MODE_PORTTRAIT', 'Porttrait');
	define('_MI_TURN_PATH_XOOPS_UPLOAD_PATH', 'Upload Path');
	define('_MI_TURN_PATH_XOOPS_VAR_PATH', 'Secure Data Path');
	define('_MI_TURN_PATH_OTHER', 'Other Path');
	define('_MI_TURN_FILETYPE_SWF', 'Shockwave Flash');
	define('_MI_TURN_FILETYPE_GIF', 'GIF File');
	define('_MI_TURN_FILETYPE_PNG', 'PNG File');
	define('_MI_TURN_FILETYPE_JPG', 'JPG File');
	
	// Enumeration Values for Pages
	define('_MI_TURN_MODE_LANDSCAPE_VALUE', 'Landscape');
	define('_MI_TURN_MODE_PORTTRAIT_VALUE', 'Portrait');
	define('_MI_TURN_PATH_XOOPS_UPLOAD_PATH_VALUE', XOOPS_UPLOAD_PATH);
	define('_MI_TURN_PATH_XOOPS_VAR_PATH_VALUE', XOOPS_VAR_PATH);
	define('_MI_TURN_PATH_OTHER_VALUE', DIRECTORY_SEPARATOR);
	define('_MI_TURN_FILETYPE_SWF_VALUE', 'Adobe Flash');
	define('_MI_TURN_FILETYPE_GIF_VALUE', 'GIF File');
	define('_MI_TURN_FILETYPE_PNG_VALUE', 'PNG File');
	define('_MI_TURN_FILETYPE_JPG_VALUE', 'JPEG File');
	
	//Javascript Sprintf Statements (Do Not Change)
	define('_MI_TURN_JS_JQUERY', '/browse.php?Frameworks/jquery/jquery.js');
	define('_MI_TURN_JS_turnJS', '/modules/turn/js/turn.min.js');
	
	//CSS Sprintf Statements (Do Not Change)
	define('_MI_TURN_CSS', '/modules/turn/css/css.php?bid=%s&block=%s');
	
	//Framework Includes
	define('_MI_TURN_FRAMEWORK_WIDEIMAGE', '/Frameworks/wideimage/WideImage.php');
	define('_MI_TURN_FRAMEWORK_TCPF', '/Frameworks/tcpdf/tcpdf.php');
	
?>
	
	