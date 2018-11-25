<?php
/*
 * Copyright (C) 2019+ MasterkinG32 <https://masterking32.com>
 * Copyright (C) 2017+ AzerothCore <www.azerothcore.org>, released under GNU GPL v2 license: http://github.com/azerothcore/azerothcore-wotlk/LICENSE-GPL2
 * Copyright (C) 2008-2016 TrinityCore <http://www.trinitycore.org/>
 * Copyright (C) 2005-2009 MaNGOS <http://getmangos.com/>
*/

class User {
	private $_db,
			$_data,
			$_sessionName,
			$_isLoggedIn;

	public function __construct($user = null) {
		$this->_db = DB::getInstance();

		$this->_sessionName = Config::get('session/session_name');

		if(!$user) {
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);

				if($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					//Logout
				}
			}
		} else {
			$this->find($user);
		}
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('account', $fields)) {
			throw new Exception('There was a problem creating your account.');
		}
	}

	public function find($user = null) {
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data  = $this->_db->get('account', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();

				return true;
			}
		}
	}

	public function login($username = null, $password = null) {
		$user = $this->find($username);
		$hash = strtoupper(sha1(strtoupper(($username)).":".strtoupper(($password))));

		if($user) {
			if($this->data()->sha_pass_hash === $hash) {
				$_SESSION["id"] = $this->data()->id;
				Session::put($this->_sessionName, $this->data()->id);

				return true;
			}
		}

		return false;
	}

	public function logout() {
		Session::delete($this->_sessionName);
	}

	public function data() {
		return $this->_data;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}
}