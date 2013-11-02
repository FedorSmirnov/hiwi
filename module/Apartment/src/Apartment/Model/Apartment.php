<?php

namespace Apartment\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Apartment implements InputFilterAwareInterface {
	public $id;
	public $adress;
	public $room_num;
	public $floor;
	public $power;
	protected $inputFilter;
	public function exchangeArray($data) {
		if (! empty ( $data ['id'] )) {
			$this->id = $data ['id'];
		} else {
			$this->id = null;
		}
		
		if (! empty ( $data ['adress'] )) {
			$this->adress = $data ['adress'];
		} else {
			$this->adress = null;
		}
		
		if (! empty ( $data ['room_num'] )) {
			$this->room_num = $data ['room_num'];
		} else {
			$this->room_num = null;
		}
		
		if (! empty ( $data ['floor'] )) {
			$this->floor = $data ['floor'];
		} else {
			$this->floor = null;
		}
		if (! empty ( $data ['power'] )) {
			$this->power = $data ['power'];
		} else {
			$data ['power'] = null;
		}
	}
	public function getArrayCopy() {
		return get_object_vars ( $this );
	}
	public function setInputFilter(InputFilterInterface $inputFilter) {
		throw new \Exception ( "Not used" );
	}
	public function getInputFilter() {
		if (! $this->inputFilter) {
			
			$inputFilter = new InputFilter ();
			
			// ueberprueft einfach nur, ob es sich bei der Eingabe um Integer handelt
			$inputFilter->add ( array (
					
					'name' => 'id',
					'required' => 'true',
					'filters' => array (
							
							array (
									
									'name' => 'Int' 
							) 
					) 
			) );
			
			// Entfernt unerwünschte HTML Zeichen und Leerzeichen und sorgt ausserdem dafür,
			// dass nicht zu viele Zeichen eingegeben werden
			$inputFilter->add ( array (
					
					'name' => 'adress',
					'required' => 'true',
					'filters' => array (
							
							array (
									
									'name' => 'StripTags' 
							),
							array (
									
									'name' => 'StringTrim' 
							) 
					),
					
					'validators' => array (
							
							array (
									
									'name' => 'StringLength',
									'options' => array (
											
											'encoding' => 'UTF-8',
											'min' => 1,
											'max' => 100 
									) 
							) 
					) 
			) );
			
			$inputFilter->add ( array (
					
					'name' => 'room_num',
					'required' => 'true',
					'filters' => array (
							
							array (
									
									'name' => 'Int' 
							) 
					),
					
					'validators' => array (
							
							array (
									'name' => 'Between',
									'options' => array (
											'min' => '1',
											'max' => '30',
											'messages' => array (
													
													\Zend\Validator\Between::NOT_BETWEEN => "Geben sie bitte eine Zahl zwischen 1 und 30 ein." 
											) 
									) 
							) 
					) 
			) );
			
			$inputFilter->add ( array (
					
					'name' => 'floor',
					'required' => 'true',
					'filters' => array (
							
							array (
									
									'name' => 'Int' 
							) 
					),
					
					'validators' => array (
							
							array (
									'name' => 'Between',
									'options' => array (
											'min' => '1',
											'max' => '30',
											'messages' => array (
													
													\Zend\Validator\Between::NOT_BETWEEN => "Geben sie bitte eine Zahl zwischen 1 und 30 ein." 
											) 
									) 
							) 
					) 
			) );
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}

?>