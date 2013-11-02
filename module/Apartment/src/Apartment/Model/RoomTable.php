<?php

namespace Apartment\Model;

use Zend\Db\TableGateway\TableGateway;

class RoomTable {
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	public function getApartmentRooms($id) {
		$id = ( int ) $id;
		
		$resultSet = $this->tableGateway->select ( array (
				'apartment_id' => $id 
		) );
		
		return $resultSet;
	}
	public function getRoom($id, $name) {
		$id = ( int ) $id;
		
		$rowSet = $this->tableGateway->select ( array (
				'apartment_id' => $id,
				'name' => $name 
		) );
		$row = $rowSet->current ();
		if (! $row) {
			
			return false;
		}
		
		$row->prepareData ();
		return $row;
	}
	public function saveRoom(Room $room) {
		$data = array (
				
				'apartment_id' => ( int ) $room->apartment_id,
				'name' => $room->name,
				'power_light' => $room->power_light,
				'power_temperature' => $room->power_temperature,
				'power_device' => $room->power_device,
				'light' => $room->light,
				'temperature' => $room->temperature,
				'temperature_outside' => $room->temperature_outside 
		);
		
		if (! $this->getRoom ( ( int ) $room->apartment_id, $room->name )) {
			$this->tableGateway->insert ( $data );
		} else {
			$this->tableGateway->update ( $data, array (
					'apartment_id' => ( int ) $room->apartment_id,
					'name' => $room->name 
			) );
		}
	}
	public function deleteRoom($id, $name) {
		$id = ( int ) $id;
		if ($name != null) {
			
			return $this->tableGateway->delete ( array (
					
					'apartment_id' => $id,
					'name' => $name 
			) );
		} else {
			return $this->tableGateway->delete ( array (
					
					'apartment_id' => $id 
			) );
		}
	}
}

?>