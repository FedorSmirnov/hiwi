<?php

namespace Apartment\Form;

use Zend\Form\Form;

class ApartmentForm extends Form {
	public function __construct($name = null) {
		parent::__construct ( 'apartment' );
		
		$this->add ( array (
				
				'name' => 'id',
				'type' => 'Hidden' 
		) );
		
		$this->add ( array (
				
				'name' => 'adress',
				'type' => 'Text',
				'options' => array (
						
						'label' => 'Adresse: ' 
				) 
		) );
		
		
		//Adresse      
		//Zimmer Anzahl
		//Stockwerk    
		
		$this->add ( array (
				
				'name' => 'room_num',
				'type' => 'Text',
				'options' => array (
						
						'label' => 'Anzahl der Zimmer: ' 
				) 
		)
		 );
		
		$this->add ( array (
				
				'name' => 'floor',
				'type' => 'Text',
				'options' => array (
						
						'label' => 'Stockwerk: ' 
				) 
		) );
		
		$this->add ( array (
				
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array (
						
						'value' => 'Los',
						'id' => 'submitbutton' 
				) 
		) );
	}
}

?>