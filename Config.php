<?php
class Config {
    static $CUSTOM_AUTH_SECRET   = '';
    static $HIPCHAT_ROOM_ID      = '';
    static $HIPCHAT_AUTH_TOKEN   = '';
    static $USE_BITLY            = False;
    static $BITLY_LOGIN          = '';
    static $BITLY_APP_KEY        = '';
   
    static function load_config($filepath) {
        $ini_result = parse_ini_file($filepath, true);
	Config::$CUSTOM_AUTH_SECRET = $ini_result['custom_auth_secret'];
	Config::$HIPCHAT_ROOM_ID = $ini_result['hipchat_room_id'];
	Config::$HIPCHAT_AUTH_TOKEN = $ini_result['hipchat_auth_token'];
	Config::$USE_BITLY = $ini_result['use_bitly'];
	Config::$BITLY_LOGIN = $ini_result['bitly_login'];
	Config::$BITLY_APP_KEY = $ini_result['bitly_app_key'];
    } 
 
    static function UseBitly() {
	return Config::$USE_BITLY;
    }

    static function getCustomAuthSecret() {
	return Config::$CUSTOM_AUTH_SECRET;
    }

    static function getHipChatRoomId() {
	return Config::$HIPCHAT_ROOM_ID;
    }

    static function getHipChatAuthToken() {
	return Config::$HIPCHAT_AUTH_TOKEN;
    }

    static function getBitlyLogin() {
	return Config::$BITLY_LOGIN;
    }
  
    static function getBitlyAppKey() {
	return Config::$BITLY_APP_KEY;
    }
}
?>
