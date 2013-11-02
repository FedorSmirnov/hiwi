<?php

namespace Apartment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Apartment\Model\Apartment;
use Apartment\Form\ApartmentForm;
use Apartment\Model\Room;
use Zend\Session\Container;
use Apartment\SharedFunc\SharedFunctions;

class ApartmentController extends AbstractActionController {
	protected $apartmentTable;
	protected $roomTable;
	public function indexAction() {
		
		// Bauen des Containers und überprüfen der Session Einstellungen
		$user_session = new Container ( 'user_status' );
		$logged = $user_session->logged;
		$admin = $user_session->admin;
		
		if ($logged == "false" || $admin != "true") {
			$this->redirect ()->toRoute ( 'login' );
		}
		
		return new ViewModel ( 

		array (
				'apartments' => $this->getApartmentTable ()->fetchAll () 
		) );
	}
	public function logoutAction() {
		$sf = new SharedFunctions ();
		$sf->logOut ( $this );
	}
	public function addAction() {
		$form = new ApartmentForm ();
		$form->get ( 'submit' )->setValue ( 'Add' );
		
		$request = $this->getRequest ();
		
		if ($request->isPost ()) {
			
			$apartment = new Apartment ();
			$form->setInputFilter ( $apartment->getInputFilter () );
			$form->setData ( $request->getPost () );
			
			if ($form->isValid ()) {
				
				$apartment->exchangeArray ( $form->getData () );
				// eruecksichtigen des Heizungsverbrauchs
				$apartment->power = 2 * 20 + 20 * ($apartment->room_num);
				$id = $this->getApartmentTable ()->saveApartment ( $apartment );
				
				$this->makeRooms ( $apartment, $id );
				
				return $this->redirect ()->toRoute ( 'apartment' );
			}
		}
		
		return array (
				'form' => $form 
		);
	}
	public function deleteAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		
		if (! $id) {
			return $this->redirect ()->toRoute ( 'apartment' );
		}
		
		$request = $this->getRequest ();
		
		if ($request->isPost ()) {
			
			$del = $request->getPost ( 'del', 'Nein' );
			
			if ($del == 'Ja') {
				
				$this->getApartmentTable ()->deleteApartment ( $id );
				$this->getRoomTable ()->deleteRoom ( $id, null );
			}
			
			return $this->redirect ()->toRoute ( 'apartment' );
		}
		
		return array (
				
				'id' => $id,
				'apartment' => $this->getApartmentTable ()->getApartment ( $id ) 
		);
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
	
	// Funktion erschafft Zimmer bei der Erstellung einer Wohnung.
	// Es gibt immer eine Kueche und ein Bad und so viele Zimmer wie man angibt
	private function makeRooms($apartment, $id) {
		$gateway = $this->getRoomTable ();
		
		$data = array (
				
				'apartment_id' => $id,
				'temperature' => 20,
				'light' => false,
				'power_light' => 0,
				'power_temperature' => 20,
				'power_device' => 0,
				'name' => "Kueche",
				'temperature_outside' => 15 
		);
		
		$room = new Room ();
		$room->exchangeArray ( $data );
		
		$gateway->saveRoom ( $room );
		
		$data = array (
				
				'apartment_id' => $id,
				'temperature' => 20,
				'light' => false,
				'power_light' => 0,
				'power_temperature' => 20,
				'power_device' => 0,
				'name' => "Bad",
				'temperature_outside' => 15 
		);
		
		$room->exchangeArray ( $data );
		
		$gateway->saveRoom ( $room );
		
		for($i = 1; $i <= $apartment->room_num; $i ++) {
			
			$data = array (
					
					'apartment_id' => $id,
					'temperature' => 20,
					'light' => false,
					'power_light' => 0,
					'power_temperature' => 20,
					'power_device' => 0,
					'name' => "Zimmer_" . $i,
					'temperature_outside' => 15 
			);
			
			$room->exchangeArray ( $data );
			
			$gateway->saveRoom ( $room );
		}
	}
}

?>