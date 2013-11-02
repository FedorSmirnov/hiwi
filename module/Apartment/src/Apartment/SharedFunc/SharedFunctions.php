<?php

namespace Apartment\SharedFunc;

use Zend\Session\Container;

class SharedFunctions {
	public function logOut($controller) {
		
		// Die Session Variablen zuruecksetzen und zurueck zum Login Bildschirm
		$user_session = new Container ( 'user_status' );
		$user_session->getManager ()->getStorage ()->clear ( 'user_status' );
		
		$controller->redirect ()->toRoute ( 'login' );
	}
}

?>