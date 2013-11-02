<?php

namespace Apartment\Model;

use Zend\Db\TableGateway\TableGateway;

class ApartmentTable {
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	public function fetchAll() {
		$resultSet = $this->tableGateway->select ();
		return $resultSet;
	}
	public function getApartment($id) {
		$id = ( int ) $id;
		
		$rowset = $this->tableGateway->select ( array (
				'id' => $id 
		) );
		$row = $rowset->current ();
		
		if (! $row) {
			
			throw new \Exception ( "Could not find row $id" );
		}
		
		return $row;
	}
	public function saveApartment(Apartment $apartment) {
		$data = array (
				
				'adress' => $apartment->adress,
				'room_num' => $apartment->room_num,
				'floor' => $apartment->floor,
				'power' => $apartment->power 
		);
		
		$id = ( int ) $apartment->id;
		
		if ($id == 0) {
			
			  $this->tableGateway->insert ( $data );
			 return $this->tableGateway->lastInsertValue;
		} else {
			
			if ($this->getApartment ( $id )) {
				$this->tableGateway->update ( $data, array (
						'id' => $id 
				) );
				return $id;
			} else {
				throw new \Exception ( "Apartment id does not exist" );
			}
		}
	}
	public function deleteApartment($id) {
		$this->tableGateway->delete ( array (
				'id' => $id 
		) );
		
		
	}
	
}

?>