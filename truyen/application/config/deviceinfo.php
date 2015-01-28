<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// device pattern
$config['device_type']   = array(
	'smartphone' => '_sp'
	,'tablet' => '_tab'
	,'mobile' => '_sp'
	,'default' => ''
);

// robot pattern
$config['rules'] = array (
	'mobile' =>
		array(
			'/^DoCoMo\/[\w\-\._]+[ \/]([^\(\)\/]+)/i' => array('DoCoMo', 1),
			'/^UP.Browser\/[\w\-\._]+\-([^\s]+)/i' => array('au', 1),
			'/^KDDI\-([^\s]+)/i' => array('au', 1),
			'/^SoftBank\/[^\/]+\/([^\/]+)/i' => array('SoftBank', 1)
		),
	'smartphone' =>
		array(
			'/^Opera\/([\d\.]+)\s*\(Android\s?([^;]*);.*Version\/([\d\.]+)/' => array('Android', 2),
			'/^Mozilla\/([\d\.]+)\s*\(Android\s?([^;]*);/' => array('Android', 2),
			'/Android\s*([^;]*);\s*([^;]+)\sBuild.*Chrome\/([^\s]+).*Mobile\sSafari.*/i' => array('Android', 2),
			'/Android\s*([^;]*);\s*([^;]+);\s*([\w\s\-\._]+)Build.*Mobile\sSafari.*/i' => array('Android', 3),
			'/iPhone(\sOS\s(\w+))?/i' => array('iPhone', 2),
		),
	'tablet' =>
		array(
			'/Android\s*([^;]*);\s*([^;]+)\sBuild.*Chrome\/([^\s]+).*((?!.*Mobile)\sSafari)+.*/i' => array('Android', 2),
			'/Android\s*([^;]+);\s*([^;]+);\s*([\w\s\.\-\_]+)Build.*((?!.*Mobile)\sSafari)+.*/i' => array('Android', 3),
			'/iPad.+CPU OS\s(\w+)/i' => array('iPad', 1) 
		)
);

$config['robots'] = array(
	'/(\w+)bot/' => array('robot', 1),
	'/(facebook)external/' => array('robot', 1),
	'/(mixi)-check/' => array('robot', 1)
);

