<?php

namespace Apartment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Apartment\Model\RoomTable;
use Apartment\Model\ApartmentTable;
use Apartment\SharedFunc\SharedFunctions;

class RoomLocController extends AbstractActionController {
	protected $apartmentTable;
	protected $roomTable;
	public function indexAction() {
		$ap_id = $this->params ()->fromRoute ( 'apartment', 0 );
		$room_name = $this->params ()->fromRoute ( 'room', '' );
		
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
			if ($apartment_id != $ap_id) {
				$this->redirect ()->toRoute ( 'login' );
			}
		}
		
		// Besetzen der Variablen, die an den View uebergeben werden
		
		$apartment = $this->getApartmentTable ()->getApartment ( $ap_id );
		$room = $this->getRoomTable ()->getRoom ( $ap_id, $room_name );
		
		$vars = array (
				
				'room' => $room,
				'apartment' => $apartment 
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