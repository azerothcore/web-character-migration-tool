<?php
/*
 * Copyright (C) 2019+ MasterkinG32 <https://masterking32.com>
 * Copyright (C) 2017+ AzerothCore <www.azerothcore.org>, released under GNU GPL v2 license: http://github.com/azerothcore/azerothcore-wotlk/LICENSE-GPL2
 * Copyright (C) 2008-2016 TrinityCore <http://www.trinitycore.org/>
 * Copyright (C) 2005-2009 MaNGOS <http://getmangos.com/>
*/

class Token {
	public static function generate() {
		return Session::put(Config::get('session/token_name'), md5(uniqid()));
	}

	public static function check($token) {
		$tokenName = Config::get('session/token_name');

		if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
			Session::delete($tokenName);

			return true;
		}

		return false;
	}
}