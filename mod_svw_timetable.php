<?php

//don't allow other scripts to grab and execute our file
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

//include helper file,

require_once (dirname(__FILE__).DS.'helper.php');
//require_once __DIR__ . 'helper.php';

$doc =& JFactory::getDocument();
$doc->addStyleSheet("/media/mod_svw_timetable/css/mod_svw_timetable.css");
//This is the parameter we get from our xml file above

jimport('joomla.application.component.helper');    

$from = $params->get('event_start');
$to = $params->get('event_end');

//include template
require(JModuleHelper::getLayoutPath('mod_svw_timetable'));


?>