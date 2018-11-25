<?php
/*
 * Copyright (C) 2019+ MasterkinG32 <https://masterking32.com>
 * Copyright (C) 2017+ AzerothCore <www.azerothcore.org>, released under GNU GPL v2 license: http://github.com/azerothcore/azerothcore-wotlk/LICENSE-GPL2
 * Copyright (C) 2008-2016 TrinityCore <http://www.trinitycore.org/>
 * Copyright (C) 2005-2009 MaNGOS <http://getmangos.com/>
*/

class Hash {
	public static function make($string, $salt = '') {
		return	 hash('sha256', $string . $salt);
	}

	public static function salt($length) {
		return mcrypt_create_iv($length);
	}

	public static function unique() {
		return self::make(uniqid());
	}

	public static function marble($username, $password) {
		return SHA1($username .':'. $password);
	}
}