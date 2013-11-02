<?php

namespace Apartment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class LoginController extends AbstractActionController {
	protected $userTable;
	public function indexAction() {
		$request = $this->getRequest ();
		
		if ($request->isPost ()) {
			
			$subName = $_POST ['login'];
			
			// Auslesen des Namen, der vom User eingegeben wird
			$name = $request->getPost ( 'name', null );
			$password = $request->getPost ( 'password', null );
			
			// Wurde beides eingegeben?
			if ($name != null && $password != null) {
				// Gibt es so einen Eintrag in der Db?
				$user = $this->getUserTable ()->findUser ( $name, $password );
				// Ist dieser User ein Admin... dann immer zur AdminSicht, ohne Sichtunterscheidung
				if ($user && $user->admin == true) {
					// Setzen der Session Variablen.
					$user_session = new Container ( 'user_status' );
					$user_session->logged = "true";
					$user_session->admin = "true";
					
					return $this->redirect ()->toRoute ( 'apartment' );
				} else if ($user) {
					// oder nicht?
					$apartment_id = ( int ) $user->apartment_id;
					
					// Falls funktionale Sicht
					if ($subName == 'Funktionale Sicht') {
						
						// Setzen der Session Variablen.
						$user_session = new Container ( 'user_status' );
						$user_session->logged = "funktional";
						$user_session->admin = "false";
						$user_session->apartment_id = $apartment_id;
						
						return $this->redirect ()->toRoute ( 'enter', array (
								
								'id' => $apartment_id 
						) );
					} elseif ($subName == 'Zimmer Sicht') {
						
						$user_session = new Container ( 'user_status' );
						$user_session->logged = "zimmerbasiert";
						$user_session->admin = "false";
						$user_session->apartment_id = $apartment_id;
						
						return $this->redirect ()->toRoute ( 'enter-loc', array (
								
								'id' => $apartment_id 
						) );
					}
				} else {
					
					$_SESSION ['errors'] = "Falscher Name und/oder Passwort. Bitte ueberpruefen Sie Ihre Eingaben.";
				}
			} else {
				$_SESSION ['errors'] = "Bitte geben Sie Ihren Nutzernamen und Ihr Passwort ein.";
			}
		}
	}
	public function getUserTable() {
		if (! $this->userTable) {
			
			$sm = $this->getServiceLocator ();
			$this->userTable = $sm->get ( 'Apartment\Model\UserTable' );
		}
		
		return $this->userTable;
	}
}

?>