<?php
/*
 * Copyright (C) 2019+ MasterkinG32 <https://masterking32.com>
 * Copyright (C) 2017+ AzerothCore <www.azerothcore.org>, released under GNU GPL v2 license: http://github.com/azerothcore/azerothcore-wotlk/LICENSE-GPL2
 * Copyright (C) 2008-2016 TrinityCore <http://www.trinitycore.org/>
 * Copyright (C) 2005-2009 MaNGOS <http://getmangos.com/>
*/

class Session {
	public static function exists($name) {
		return (isset($_SESSION[$name])) ? true : false;
	}

	public static function put($name, $value) {
		return $_SESSION[$name] = $value;
	}

	public static function get($name) {
		return $_SESSION[$name];
	}

	public static function delete($name) {
		if(self::exists($name)) {
			unset($_SESSION[$name]);
		}
	}

	public static function flash($name, $string = '') {
		if(self::exists($name)) {
			$session = self::get($name);
			self::delete($name);

			return $session;	
		} else {
			self::put($name, $string);
		}
	}
}