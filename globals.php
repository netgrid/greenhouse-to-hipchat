<?php
// IT IS NOT SAFE TO MAKE CHANGES BELOW THIS LINE -------------------------------
// ################### PLUGIN

define("CONFIG_FILE","config.ini");

function netgrid_load_config() 
{
	$defaults = array(
		"CUSTOM_AUTH_SECRET" => "",
		"HIPCHAT_ROOM_ID" => "",
		"HIPCHAT_AUTH_TOKEN" => "",
		"USE_BITLY" => True,
		"BITLY_LOGIN" => "",
		"BITLY_APP_KEY" => ""
	);
	
	
	$customs = @parse_ini_file(CONFIG_FILE);
	foreach(array_keys($defaults) as $key) 
	{
		if($customs && array_key_exists($key, $customs)) 
		{
			if($customs[$key] !== null)
				define($key,$customs[$key]);
		} else {
			if($defaults[$key] !== null)
				define($key,$defaults[$key]);
		}
	}
}

netgrid_load_config();
