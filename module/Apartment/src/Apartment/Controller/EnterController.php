<?php

namespace Apartment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Apartment\SharedFunc\SharedFunctions;

class EnterController extends AbstractActionController {
	protected $apartmentTable;
	protected $roomTable;
	public function indexAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		
		// Holen der Session variablen und ueberpruefen des User Status
		$user_session = new Container ( 'user_status' );
		$logged = $user_session->logged;
		$admin = $user_session->admin;
		
		// Rausschmeissen wenn nicht eingeloggt
		if ($logged != "funktional") {
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
		$vars = array (
				'apartment' => $apartment,
				'rooms' => $this->getRoomTable ()->getApartmentRooms ( $apartment->id ),
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
	public function toggleLightAction() {
		
		// Holen des Zimmernamen aus dem Post
		$request = $this->getRequest ();
		$name = $_POST ['zimmername'];
		$name = trim ( $name );
		
		// In der Datenbank schauen, ob das Licht grad an oder aus ist
		$id = $_POST ['wohnungsnummer'];
		
		$room = $this->getRoomTable ()->getRoom ( $id, $name );
		$light = $room->light;
		$power_light = $room->power_light;
		
		// Der derzeitige Verbrauch der Lichter wird von dem Gesamtverbrauch abgezogen
		$powerSum = $this->updateLightPower ( $id );
		$apartment = $this->getApartmentTable ()->getApartment ( $id );
		$apartment->power = $apartment->power - $powerSum;
		
		if ($light == null) {
			
			$light = 0;
		}
		if ($power_light == null) {
			$power_light = 0;
		}
		
		if ($light == 0) {
			
			// Licht ist aus, man muss es anmachen und den Verbrauch erhoehen
			
			$room->light = 1;
			$room->power_light = $power_light + 10;
			
			$this->getRoomTable ()->saveRoom ( $room );
			$button = "Aus";
			$status = "An";
		} else {
			
			// Licht ist an, man muss es ausmachen und den Verbrauch verkleinern
			
			$room->light = 0;
			$room->power_light = $power_light - 10;
			
			$this->getRoomTable ()->saveRoom ( $room );
			$button = "An";
			$status = "Aus";
		}
		
		$powerSum = $this->updateLightPower ( $id );
		
		// Der neue Lichtverbrauch wir wieder zum Gesamtverbrauch addiert
		$apartment->power = $apartment->power + $powerSum;
		$this->getApartmentTable ()->saveApartment ( $apartment );
		
		
		
		$result = new JsonModel ( array (
				
				'button' => $button,
				'status' => $status,
				'power_sum' => $powerSum,
				'power_apartment' => $apartment->power,
				'room_power_sum' => $room->getPowerSum() 
		) );
		
		return $result;
	}
	
	// Funktion, um alle Lichter in einem Apartment auszuschalten
	public function allLightsOutAction() {
		$id = $_POST ['wohnungsnummer'];
		
		$rooms = $this->getRoomTable ()->getApartmentRooms ( $id );
		// Der derzeitige Verbrauch der Lichter wird von dem Gesamtverbrauch abgezogen
		$powerSum = $this->updateLightPower ( $id );
		$apartment = $this->getApartmentTable ()->getApartment ( $id );
		$apartment->power = $apartment->power - $powerSum;
		$this->getApartmentTable ()->saveApartment ( $apartment );
		
		foreach ( $rooms as $room ) {
			// Wenn Licht an, muss man es ausschalten
			if ($room->light == true) {
				
				$room->light = 0;
				$room->power_light = 0;
				
				$this->getRoomTable ()->saveRoom ( $room );
			}
		}
		
		// Hier alle Lichter aus, jetzt muss man noch den Gesamtverbrauch anpassen
		
		$result = new JsonModel ( array (
				
				'gesamtverbrauch' => $apartment->power 
		) );
		return $result;
	}
	public function changeTempAction() {
		// Funktion setzt einen neuen Sollwert für die Temperatur der Wohnung um
		$id = $_POST ['wohnungsnummer'];
		$sollTemp = $_POST ['sollTemp'];
		$room_name = $_POST ['name'];
		$room_name = trim ( $room_name );
		
		$apartment = $this->getApartmentTable ()->getApartment ( $id );
		$room = $this->getRoomTable ()->getRoom ( $id, $room_name );
		
		$temp_aussen = $room->temperature_outside;
		
		// Jetziger Wert wird abgezogen
		$apartment->power = $apartment->power - $room->power_temperature;
		
		// Berechnen des neuen Verbrauchs
		$diff = $sollTemp - $temp_aussen;
		$verbrauch = 0;
		if ($diff > 0) {
			// Heizen
			$verbrauch = $diff * 4;
		} else {
			// Kuehlen
			$verbrauch = $diff * (- 3);
		}
		
		// Aktualisieren der Werte
		
		$room->power_temperature = $verbrauch;
		$room->temperature = $sollTemp;
		$apartment->power = $apartment->power + $verbrauch;
		
		// ... auch in der DB
		$this->getApartmentTable ()->saveApartment ( $apartment );
		$this->getRoomTable ()->saveRoom ( $room );
		
		// Bauen des return Wertes
		
		$result = new JsonModel ( array (
				
				'name' => "Name",
				'gesamtverbrauch' => $apartment->power,
				'tempverbrauch_zimmer' => $verbrauch,
				'tempverbrauch_wohnung' => $this->updateTempPower ( $id ),
				'room_power_sum' => $room->getPowerSum()
		) );
		
		return $result;
	}
	
	// Funktion um alle Heizungen/Klimaanlagen auszuschalten
	public function allTempOffAction() {
		// Post daten auslesen
		$id = $_POST ['id'];
		$temp_verbrauch = $_POST ['temp_verbrauch'];
		
		$apartment = $this->getApartmentTable ()->getApartment ( $id );
		
		// Gesamtverbrauch herabsetzen
		$apartment->power = $apartment->power - $temp_verbrauch;
		$this->getApartmentTable ()->saveApartment ( $apartment );
		
		// Tempverbrauch und Solltemperatur in den Zimmern anpassen
		$rooms = $this->getRoomTable ()->getApartmentRooms ( $id );
		
		foreach ( $rooms as $room ) {
			
			$room->temperature = $room->temperature_outside;
			$room->power_temperature = 0;
			$this->getRoomTable ()->saveRoom ( $room );
		}
		
		$result = new JsonModel ( array (
				
				'gesamtverbrauch' => $apartment->power 
		) );
		
		return $result;
	}
	
	// Funktion aktualisiert den Stromverbrauch der Lichter einer Wohnung
	private function updateLightPower($id) {
		$rooms = $this->getRoomTable ()->getApartmentRooms ( $id );
		$powerSum = 0;
		
		foreach ( $rooms as $room ) {
			
			$powerSum = $powerSum + $room->power_light;
		}
		
		return $powerSum;
	}
	// Funktion aktuallisiert den Stromverbrauch der Klimadinger
	private function updateTempPower($id) {
		$rooms = $this->getRoomTable ()->getApartmentRooms ( $id );
		$powerSum = 0;
		
		foreach ( $rooms as $room ) {
			
			if ($room->power_temperature != null) {
				$powerSum = $powerSum + $room->power_temperature;
			}
		}
		
		return $powerSum;
	}
}

?>