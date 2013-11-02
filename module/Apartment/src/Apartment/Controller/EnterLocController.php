<?php

namespace Apartment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Config\Config;
use Apartment\SharedFunc\SharedFunctions;

class EnterLocController extends AbstractActionController {
	protected $apartmentTable;
	protected $roomTable;
	public function indexAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		
		// Holen und Ueberpruefen der Session Variablen
		$user_session = new Container ( 'user_status' );
		$logged = $user_session->logged;
		$admin = $user_session->admin;
		
		// Rausschmeissen wenn nicht eingeloggt
		if ($logged != "zimmerbasiert") {
			$this->redirect ()->toRoute ( 'login' );
		}
		
		// Wenn nicht admin und in der falschen Wohnung auch raus
		if ($admin != "true") {
			$apartment_id = $user_session->apartment_id;
			if ($apartment_id != $id) {
				$this->redirect ()->toRoute ( 'login' );
			}
		}
		
		$apartment = $this->getApartmentTable ()->getApartment ( $id );
		$rooms = $this->getRoomTable ()->getApartmentRooms ( $apartment->id );
		
		$vars = array (
				
				'apartment' => $apartment,
				'rooms' => $rooms,
				'admin' => $admin 
		);
		
		return new ViewModel ( $vars );
	}
	public function logoutAction() {
		$sf = new SharedFunctions ();
		
		$sf->logOut ( $this );
	}
	public function getApartmentTable() {
		if (! $this->apartmentTable) {
			$sm = $this->getServiceLocator ();
			$this->apartmentTable = $sm->get ( 'Apartment\Model\ApartmentTable' );
		}
		
		return $this->apartmentTable;
	}
	public function getRoomTable() {
		if (! $this->roomTable) {
			
			$sm = $this->getServiceLocator ();
			$this->roomTable = $sm->get ( 'Apartment\Model\RoomTable' );
		}
		
		return $this->roomTable;
	}
}

?>
