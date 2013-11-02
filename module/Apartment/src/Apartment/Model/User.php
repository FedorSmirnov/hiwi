<?php

namespace Apartment\Model;

class User {
	public $name;
	public $password;
	public $admin;
	public $apartment_id;
	public function exchangeArray($data) {
		if (! empty ( $data ['name'] )) {
			$this->name = $data ['name'];
		} else {
			$this->name = null;
		}
		
		if (! empty ( $data ['password'] )) {
			$this->password = $data ['password'];
		} else {
			$this->password = null;
		}
		
		if (! empty ( $data ['admin'] )) {
			$this->admin = $data ['admin'];
		} else {
			$this->admin = null;
		}
		
		if (! empty ( $data ['apartment_id'] )) {
			$this->apartment_id = $data ['apartment_id'];
		} else {
			$this->apartment_id = null;
		}
	}
}

?>